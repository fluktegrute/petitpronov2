<?php

use App\Models\League;
use App\Models\LeagueUser;
use App\Models\User;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    public int $myGlobalRank;
    public int $myScore;
    public int $totalPlayers;
    public string $joinCode;

    public function mount(): void 
    {
        $user = auth()->user();

        $this->myScore = $user->total_points;
        $this->totalPlayers = User::count();

        $this->myGlobalRank = User::where('total_points', '>', $user->total_points)
            // Égalité de points, mais l'autre a plus de scores exacts
            ->orWhere(function ($query) use ($user) {
                $query->where('total_points', $user->total_points)
                    ->where('exact_count', '>', $user->exact_count);
            })
            // Égalité de points et de scores exacts, mais l'autre a de meilleures tendances
            ->orWhere(function ($query) use ($user) {
                $query->where('total_points', $user->total_points)
                    ->where('exact_count', $user->exact_count)
                    ->where('trend_count', '>', $user->trend_count);
            })
            // Égalité sur tout le reste, mais l'autre a été plus efficace (moins de pronos joués)
            ->orWhere(function ($query) use ($user) {
                $query->where('total_points', $user->total_points)
                    ->where('exact_count', $user->exact_count)
                    ->where('trend_count', $user->trend_count)
                    ->where('prono_count', '<', $user->prono_count);
            })
            ->count() + 1;
    }

    #[Computed]
    public function myLeagues(): Collection
    {
        $user = auth()->user();

        return $user->leagues()
            ->get()
            ->map(function($league) use ($user) {
                $league->my_rank = $league->users()
                    ->where(function($query) use ($user) {
                        $query->where('total_points', '>', $user->total_points)
                        ->orWhere(function ($q) use ($user) {
                            $q->where('total_points', $user->total_points)
                            ->where('exact_count', '>', $user->exact_count);
                        })
                        ->orWhere(function ($q) use ($user) {
                            $q->where('total_points', $user->total_points)
                            ->where('exact_count', $user->exact_count)
                            ->where('trend_count', '>', $user->trend_count);
                        })
                        ->orWhere(function ($q) use ($user) {
                            $q->where('total_points', $user->total_points)
                            ->where('exact_count', $user->exact_count)
                            ->where('trend_count', $user->trend_count)
                            ->where('prono_count', '<', $user->prono_count);
                        });
                    })
                    ->count() + 1;

                return $league;
            });
    }

    #[Computed]
    public function globalLeaderboard()
    {
        return User::with('winnerTeam') 
            ->orderBy('total_points', 'desc')
            ->orderBy('exact_count', 'desc')
            ->orderBy('trend_count', 'desc')
            ->orderBy('prono_count', 'asc')
            ->limit(10)
            ->get();
    }

    public function joinLeague()
    {
        $rules = ['joinCode' => 'required|string|max:12|exists:leagues,invite_code'];
        $userssages = [
            'joinCode.required' => 'Tu as oublié de mettre le code',
            'joinCode.max' => 'Le code doit faire :max caractères',
            'joinCode.exists' => 'Ce code ne correspond à aucune ligue',
        ];

        $this->validate($rules, $userssages);

        $league = League::where('invite_code', $this->joinCode)->first();

        LeagueUser::updateOrCreate([
            'league_id' => $league->id,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('leagues.private', [$league->invite_code]);
    }
};