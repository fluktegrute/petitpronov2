<?php

use App\Models\League;
use App\Models\User;

use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    public function mount()
    {
        if(!auth()->user()->isAdmin)
            return redirect()->route('dashboard');
    }

    #[Computed]
    public function globalStats(): array
    {
        $totalPronos = User::sum('prono_count');
        $totalExacts = User::sum('exact_count');
        $totalTrends = User::sum('trend_count');
        
        $totalLost = $totalPronos - ($totalExacts + $totalTrends);

        return [
            'players' => User::count(),
            'leagues' => League::count(),
            'pronos_total' => $totalPronos,
            'pronos_exacts' => $totalExacts,
            'pronos_trends' => $totalTrends,
            'pronos_lost' => max(0, $totalLost),
        ];
    }

    #[Computed]
    public function leaguesList()
    {
        return League::withCount('users')
            ->orderBy('users_count', 'desc')
            ->get();
    }
};