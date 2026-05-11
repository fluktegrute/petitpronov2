<?php

use Illuminate\Support\Collection;
use Livewire\Component;

use App\Models\User;
use Livewire\Attributes\Computed;

new class extends Component
{
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

    #[Computed]
    public function next7(): Collection
    {
        return User::orderBy('total_points', 'DESC')
            ->orderBy('exact_count', 'desc')
            ->orderBy('trend_count', 'desc')
            ->orderBy('prono_count', 'asc')
            ->offset(3)
            ->take(7)
            ->get();
    }

    #[Computed]
    public function contextPlayers(): Collection
    {
        $user = auth()->user();

        $userRank = User::where('total_points', '>', $user->total_points)
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
            })
            ->count() + 1;

        if ($userRank <= 10) {
            return collect();
        }

        $offset = $userRank - 4;
        $limit = 7; 

        if ($offset < 10) {
            $overlap = 10 - $offset;
            $offset = 10;
            $limit = $limit - $overlap;
        }

        $players = User::orderBy('total_points', 'DESC')
            ->orderBy('exact_count', 'desc')
            ->orderBy('trend_count', 'desc')
            ->orderBy('prono_count', 'asc')
            ->offset($offset)
            ->take($limit)
            ->get();

        return $players->map(function ($user, $index) use ($offset) {
            $user->rank = $offset + $index + 1;
            return $user;
        });
    }
};