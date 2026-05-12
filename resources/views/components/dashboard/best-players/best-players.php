<?php

use App\Models\User;

use Illuminate\Support\Collection;

use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    public int $userRank;
    public int $totalPlayers;

    public function mount(): void 
    {
        $user = auth()->user();
        $this->userRank = User::where('total_points', '>', $user->total_points)
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
        
        $this->totalPlayers = User::count();
    }

    #[Computed]
    public function top3(): Collection
    {
        return User::orderBy('total_points', 'DESC')
            ->orderBy('exact_count', 'desc')
            ->orderBy('trend_count', 'desc')
            ->orderBy('prono_count', 'asc')
            ->take(3)
            ->get();
    }
};