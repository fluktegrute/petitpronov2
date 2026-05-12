<?php

use App\Models\Game;

use Illuminate\Support\Collection;

use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    #[Computed]
    public function nextMatches(): Collection
    {
        return Game::where('kickoff_at', '>', now())
            ->orderBy('kickoff_at')
            ->take(2)
            ->get();
    }
};