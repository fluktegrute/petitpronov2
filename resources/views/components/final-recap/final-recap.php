<?php

use App\Models\Game;
use App\Models\League;
use App\Models\Prediction;
use App\Models\Team;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    #[Computed]
    public function finalGame(): ?Game
    {
        return Game::with(['homeTeam', 'awayTeam'])
            ->where('stage', 'FINAL')
            ->whereNotNull('home_score')
            ->first();
    }

    #[Computed]
    public function shouldShow(): bool
    {
        return auth()->check() && $this->finalGame !== null && !auth()->user()->seen_final_modal;
    }

    #[Computed]
    public function championTeam(): ?Team
    {
        if (!$this->finalGame || !$this->finalGame->winner_team_id) return null;
        return $this->finalGame->winner_team_id === $this->finalGame->home_team_id
            ? $this->finalGame->homeTeam
            : $this->finalGame->awayTeam;
    }

    #[Computed]
    public function userRank(): int
    {
        return User::where('total_points', '>', auth()->user()->total_points)->count() + 1;
    }

    #[Computed]
    public function bestPrediction(): ?Prediction
    {
        return Prediction::with(['game.homeTeam', 'game.awayTeam'])
            ->where('user_id', auth()->id())
            ->where('points', '>', 0)
            ->orderBy('points', 'desc')
            ->first();
    }

    #[Computed]
    public function pickedWinner(): bool
    {
        $user = auth()->user();
        return $this->finalGame !== null
            && $user->winner_team_id !== null
            && $user->winner_team_id === $this->finalGame->winner_team_id;
    }

    #[Computed]
    public function globalStats(): object
    {
        $evaluated = Prediction::whereIn('status', ['exact', 'trend', 'lost', 'cheated'])->count();
        $exact     = Prediction::where('status', 'exact')->count();
        $trend     = Prediction::where('status', 'trend')->count();

        return (object) [
            'players'      => User::count(),
            'leagues'      => League::count(),
            'evaluated'    => $evaluated,
            'exact'        => $exact,
            'trend'        => $trend,
            'exactPercent' => $evaluated > 0 ? round($exact / $evaluated * 100) : 0,
            'goodPercent'  => $evaluated > 0 ? round(($exact + $trend) / $evaluated * 100) : 0,
        ];
    }

    public function dismiss(): void
    {
        auth()->user()->update(['seen_final_modal' => true]);
    }
};
