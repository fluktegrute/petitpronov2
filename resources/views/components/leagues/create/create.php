<?php

use App\Models\League;
use App\Models\LeagueUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

use Livewire\Component;
use Livewire\Features\SupportRedirects\Redirector;

new class extends Component
{
    public string $name;
    public string $description;

    public function save(): RedirectResponse|Redirector
    {
        $rules = [
            'name' => 'required|string|min:3|max:100',
            'description' => 'max:1000',
        ];
        $messages = [
            'name.required' => 'Le nom de ta ligue est requis',
            'name.min' => 'Le nom de ta ligue doit faire au moins :min caractères',
            'name.max' => 'Le nom de ta ligue ne doit pas faire plus de :max caractères',
            'description.max' => 'La description ne peut pas faire plus de :max caractères',
        ];

        $this->validate($rules, $messages);

        $league = League::create([
            'name' => $this->name,
            'description' => $this->description ?? '',
            'invite_code' => $this->createInviteCode(),
            'owner_id' => auth()->id(),
        ]);

        LeagueUser::create([
            'league_id' => $league->id,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('leagues.private', [$league->invite_code]);
    }

    protected function createInviteCode(): string 
    {
        do{
            $code = strtolower(Str::password(12, symbols:false, ));
        } while (League::where('invite_code', $code)->exists());
        return $code;
    }
};