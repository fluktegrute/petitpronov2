<?php

use App\Models\Game;
use App\Models\MatchHistory;
use App\Models\Prediction;
use App\Models\Team;
use Illuminate\Support\Collection;

use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
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
        $game = Game::findOrFail($gameId);
        $user = auth()->user();

        // Contrôle anti-triche
        if(now()->isAfter($game->kickoff_at)){
            Prediction::updateOrCreate(
                ['user_id' => $user->id, 'game_id' => $gameId],
                ['status' => 'cheated']
            );
            return; 
        }

        $pronoData = $this->predictions[$gameId];
        $prediction = Prediction::where('user_id', $user->id)->where('game_id', $gameId)->first();
        
        $wantsBoost = $pronoData['is_boosted'] ?? false;

        if(!$prediction){

            $canBoost = $wantsBoost && $user->boosts_remaining > 0;

            Prediction::create([
                'user_id'    => $user->id,
                'game_id'    => $gameId,
                'home_score' => $pronoData['home_score'] ?? null,
                'away_score' => $pronoData['away_score'] ?? null,
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
                'home_score' => $pronoData['home_score'] ?? null,
                'away_score' => $pronoData['away_score'] ?? null,
                'is_boosted' => $boostStatus,
            ]);
        }
    }
};