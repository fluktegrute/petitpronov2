<?php

namespace App\Concerns;

use App\Models\Game;
use Livewire\Attributes\Computed;

trait HasTournamentState
{
    #[Computed]
    public function tournamentStarted(): bool
    {
        $first = Game::orderBy('kickoff_at')->value('kickoff_at');
        return $first !== null && now()->isAfter($first);
    }
}
