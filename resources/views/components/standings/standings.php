<?php

use App\Models\Game;
use App\Models\Team;

use Illuminate\Support\Collection;

use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    public string $tab = "poolPhase";

    #[Computed]
    public function groups(): Collection
    {
        return Team::whereNotNull('pool')
            ->orderBy('pool', 'ASC')
            ->orderBy('position', 'DESC')
            ->orderBy('points', 'DESC')
            ->orderBy('goal_diff', 'DESC')
            ->orderBy('goals_for', 'DESC')
            ->get()
            ->groupBy('pool');
    }

    #[Computed]
    public function thirds(): Collection 
    {
        return Team::where('position', 3)
            ->orderBy('points', 'DESC')
            ->orderBy('goal_diff', 'DESC')
            ->orderBy('goals_for', 'DESC')
            ->get();
    }

    #[Computed]
    public function knockoutGames(): Collection 
    {
        return Game::whereNotNull('bracket_slot')
            ->with(['homeTeam', 'awayTeam'])
            ->get()
            ->keyBy('bracket_slot');
    }
};