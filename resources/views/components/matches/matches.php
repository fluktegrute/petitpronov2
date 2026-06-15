<?php

use App\Concerns\HasTournamentState;
use App\Models\Game;
use App\Models\MatchHistory;
use App\Models\Prediction;
use App\Models\Team;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    use HasTournamentState;
    public array $predictions = [];
    public string $tab = 'upcoming';
    public bool $h2hModalOpened = false;
    public bool $statsModalOpened = false;
    public ?Collection $currentH2h;
    public ?Collection $currentStats;
    public ?int $currentStatsTeamId = null;

    public function mount(): void
    {
        $this->predictions = Prediction::where('user_id', auth()->id())->get()->keyBy('game_id')->toArray();
    }

    #[Computed]
    public function tournamentStarted(): bool
    {
        $first = Game::orderBy('kickoff_at')->value('kickoff_at');
        return $first !== null && now()->isAfter($first);
    }

    #[Computed]
    public function games(): Collection
    {
        if ($this->tab === 'upcoming') {
            return Game::where('status', '!=', 'FINISHED')
                ->orderBy('kickoff_at')
                ->get();
        }

        return Game::where('status', 'FINISHED')
            ->orderBy('kickoff_at', 'DESC')
            ->get();
    }

    public function openH2hModal(int $gameId): void 
    {
        $game = Game::findOrFail($gameId);

        $this->currentH2h = MatchHistory::query()
            ->with(['homeTeam', 'awayTeam']) 
            ->where(function ($query) use ($game) {
                $query->where('home_team_id', $game->home_team_id)
                    ->where('away_team_id', $game->away_team_id)
                    ->orWhere(function ($subQuery) use ($game) {
                        $subQuery->where('home_team_id', $game->away_team_id)
                                ->where('away_team_id', $game->home_team_id);
                    });
            })
            ->orderBy('date', 'DESC')
            ->limit(5)
            ->get();

        $this->h2hModalOpened = true;
    }

    public function openStatsModal(int $teamId): void 
    {
        $this->currentStats = MatchHistory::query()
            ->with(['homeTeam', 'awayTeam']) 
            ->where(function ($query) use ($teamId) {
                $query->where('home_team_id', $teamId)
                    ->orWhere(function ($subQuery) use ($teamId) {
                        $subQuery->where('away_team_id', $teamId);
                    });
            })
            ->orderBy('date', 'DESC')
            ->limit(10)
            ->get();

        $this->currentStatsTeamId = $teamId;
        $this->statsModalOpened = true;
    }

    #[Computed]
    public function otherPredictionsByGame(): array
    {
        $leagueMateIds = auth()->user()
            ->leagues()
            ->with('users:id')
            ->get()
            ->flatMap(fn($league) => $league->users->pluck('id'))
            ->unique()
            ->reject(fn($id) => $id === auth()->id())
            ->values();

        if ($leagueMateIds->isEmpty()) {
            return [];
        }

        $startedGameIds = $this->games
            ->filter(fn($game) => now()->isAfter($game->kickoff_at))
            ->pluck('id');

        if ($startedGameIds->isEmpty()) {
            return [];
        }

        return Prediction::with('user:id,name,avatar_path')
            ->whereIn('user_id', $leagueMateIds)
            ->whereIn('game_id', $startedGameIds)
            ->where('status', '!=', 'cheated')
            ->whereNotNull('home_score')
            ->whereNotNull('away_score')
            ->get()
            ->groupBy('game_id')
            ->map(fn($preds) => $preds->sortByDesc('points')->values())
            ->toArray();
    }

    #[Computed]
    public function currentStatsTeam(): ?Team
    {
        if (!$this->currentStatsTeamId) {
            return null;
        }

        return Team::find($this->currentStatsTeamId);
    }

    public function updated(string $property, $value): void 
    {
        if (str_starts_with($property, 'predictions.')) {
            
            $parts = explode('.', $property);
            $gameId = (int) $parts[1];

            $this->saveSinglePrediction($gameId);
        }
    }

    private function saveSinglePrediction(int $gameId): void
    {
        $this->validate(
            [
                "predictions.{$gameId}.home_score" => 'nullable|integer|min:0|max:99',
                "predictions.{$gameId}.away_score" => 'nullable|integer|min:0|max:99',
            ],
            [
                "predictions.{$gameId}.home_score.integer" => 'Le score doit être un entier.',
                "predictions.{$gameId}.home_score.min"     => 'Le score ne peut pas être négatif.',
                "predictions.{$gameId}.home_score.max"     => 'Le score ne peut pas dépasser 99.',
                "predictions.{$gameId}.away_score.integer" => 'Le score doit être un entier.',
                "predictions.{$gameId}.away_score.min"     => 'Le score ne peut pas être négatif.',
                "predictions.{$gameId}.away_score.max"     => 'Le score ne peut pas dépasser 99.',
            ]
        );

        $game = Game::findOrFail($gameId);
        $pronoData = $this->predictions[$gameId];

        // Contrôle anti-triche
        if(now()->isAfter($game->kickoff_at)){
            Prediction::updateOrCreate(
                ['user_id' => auth()->id(), 'game_id' => $gameId],
                ['status' => 'cheated']
            );
            return;
        }

        $wantsBoost = $pronoData['is_boosted'] ?? false;
        $homeScore  = isset($pronoData['home_score']) && $pronoData['home_score'] !== '' ? (int) $pronoData['home_score'] : null;
        $awayScore  = isset($pronoData['away_score']) && $pronoData['away_score'] !== '' ? (int) $pronoData['away_score'] : null;

        DB::transaction(function() use ($gameId, $wantsBoost, $homeScore, $awayScore) {
            $user = \App\Models\User::where('id', auth()->id())->lockForUpdate()->first();

            $prediction = Prediction::where('user_id', $user->id)->where('game_id', $gameId)->first();

            if(!$prediction){
                $canBoost = $wantsBoost && $user->boosts_remaining > 0;

                Prediction::create([
                    'user_id'    => $user->id,
                    'game_id'    => $gameId,
                    'home_score' => $homeScore,
                    'away_score' => $awayScore,
                    'is_boosted' => $canBoost,
                    'status'     => 'placed',
                ]);

                if($canBoost){
                    $user->decrement('boosts_remaining');
                }
            }
            else{
                $boostStatus = $prediction->is_boosted;

                if($prediction->is_boosted && !$wantsBoost){
                    $user->increment('boosts_remaining');
                    $boostStatus = false;
                }
                elseif(!$prediction->is_boosted && $wantsBoost){
                    if($user->boosts_remaining > 0){
                        $user->decrement('boosts_remaining');
                        $boostStatus = true;
                    }
                    else{
                        $boostStatus = false;
                    }
                }

                $prediction->update([
                    'home_score' => $homeScore,
                    'away_score' => $awayScore,
                    'is_boosted' => $boostStatus,
                ]);
            }
        });
    }
};