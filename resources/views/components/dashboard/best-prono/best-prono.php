<?php

use App\Models\Prediction;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    #[Computed]
    public function myBest(): ?Prediction
    {
        return Prediction::with(['game.homeTeam', 'game.awayTeam'])
            ->where('user_id', auth()->id())
            ->where('points', '>', 0)
            ->orderBy('points', 'desc')
            ->first();
    }

    #[Computed]
    public function globalBest(): ?Prediction
    {
        return Prediction::with(['user', 'game.homeTeam', 'game.awayTeam'])
            ->where('points', '>', 0)
            ->orderBy('points', 'desc')
            ->first();
    }
};
