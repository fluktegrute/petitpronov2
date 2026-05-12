<?php

use Illuminate\Support\Collection;

use Livewire\Component;
use Livewire\Attributes\Computed;

use App\Models\League;
use App\Models\LeagueUser;
use App\Models\User;

new class extends Component
{
    public League $league;
    public bool $isUserAdmin = false;
    public string $description = '';

    public function mount(string $code)
    {
        $this->league = League::where('invite_code', $code)->firstOrFail();
        
        if(!LeagueUser::where('user_id', auth()->id())->where('league_id', $this->league->id)->exists()) {
            session()->flash('error', 'Tu ne fais pas partie de cette ligue !');
            return redirect()->route('leagues');
        }

        if(auth()->id() === $this->league->owner_id)
            $this->isUserAdmin = true;

        $this->description = $this->league->description ?? '';
    }

    #[Computed]
    public function top3(): Collection
    {
        return $this->league->users()
            ->orderBy('users.total_points', 'desc')
            ->orderBy('users.exact_count', 'desc')
            ->orderBy('users.trend_count', 'desc')
            ->orderBy('users.prono_count', 'asc')
            ->take(3)
            ->get();
    }

    #[Computed]
    public function next7(): Collection
    {
        return $this->league->users()
            ->orderBy('total_points', 'DESC')
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

        $players = $this->league->users()
            ->orderBy('total_points', 'DESC')
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

    #[Computed]
    public function allMembers(): Collection
    {
        return $this->league->users()->orderBy('name')->get();
    }

    public function removeUser(int $userId)
    {
        if(!$this->isUserAdmin)
            abort(403, 'Action non autorisée.');

        if($userId === auth()->id()){
            session()->flash('error', 'Tu ne peux pas quitter ta propre ligue ici.');
            return;
        }

        $this->league->users()->detach($userId);

        session()->flash('success', 'Joueur expulsé de la ligue avec succès.');        
        return redirect()->route('leagues.private', ['code' => $this->league->invite_code]);
    }

    public function updatedDescription(): void
    {
        $this->league->update(['description' => $this->description]);
    }
};