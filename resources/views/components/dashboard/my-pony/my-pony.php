<?php

use App\Models\Game;
use App\Models\Team;
use Livewire\Component;

new class extends Component
{
    public ?Team $userPony;
    public int $teamPoolRanking = 0;
    public ?Game $nextGame;

    public function mount(): void 
    {
        $this->userPony = auth()->user()->winnerTeam;

        if($this->userPony){
            $this->teamPoolRanking = Team::where('pool', $this->userPony->pool)
                ->orderBy('pool', 'ASC')
                ->orderBy('position', 'DESC')
                ->orderBy('points', 'DESC')
                ->orderBy('goal_diff', 'DESC')
                ->orderBy('goals_for', 'DESC')
                ->get()
                ->search(fn($team) => $team->id === $this->userPony->id) + 1;

            $this->nextGame = Game::where(function($query) {
                    $query->where('home_team_id', $this->userPony->id)
                          ->orWhere('away_team_id', $this->userPony->id);
                })
                ->where('kickoff_at', '>', now())
                ->orderBy('kickoff_at', 'ASC')
                ->first();
        }
    }
};