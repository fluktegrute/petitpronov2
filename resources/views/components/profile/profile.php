<?php

use App\Models\Game;
use App\Models\Team;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public int $userId;
    public ?int $winner_team_id = null;
    public string $current_password = '';
    public string $new_password = '';
    public string $new_password_confirmation = '';
    public Game $firstGame;
    public $photo;

    public function mount()
    {
        $user = auth()->user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->userId = $user->id;
        $this->winner_team_id = $user->winner_team_id;
        $this->firstGame = Game::orderBy('kickoff_at')->first();
    }

    public function updateProfile()
    {
        $this->validate(
            [
                'name' => [
                    'required',
                    'max:30',
                    Rule::unique('users')->ignore($this->userId), 
                ],
                'email' => 'required|email',
                'winner_team_id' => 'nullable|numeric|exists:teams,id',
                'photo' => 'nullable|image|max:2048'
            ],
            [
                'name.required' => 'Ton nom est obligatoire',
                'name.unique' => 'Ce nom est déjà pris',
                'name.max' => 'Ton nom ne peut pas faire plus de :max caractères',
                'email.required' => 'Une adresse mail est obligatoire',
                'email.email' => 'Doit être une adresse mail valide',
                'winner_team_id.numeric' => 'Cette équipe n\'existe pas',
                'winner_team_id.exists' => 'Cette équipe n\'existe pas',
                'photo.image' => 'Le fichier doit être une image au format jpg, png ou webp',
                'photo.max' => 'Le fichier ne doit pas faire plus de 2 Mo',
            ]
        );

        $dataToUpdate = [
            'name' => $this->name,
            'email' => $this->email,
            'winner_team_id' => $this->winner_team_id,
        ];

        if ($this->photo) {
            $dataToUpdate['avatar_path'] = $this->photo->store('avatars', 'public');
        }

        auth()->user()->update($dataToUpdate);

        session()->flash('status', 'Profil mis à jour avec succès.');
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required|current_password',
            'new_password' => ['required', 'confirmed', Password::min(8)],
        ]);

        auth()->user()->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);

        session()->flash('status', 'Mot de passe modifié avec succès.');
    }

    #[Computed]
    public function teams()
    {
        return Team::all()->sortBy('nameFr');
    }

    public function updatedPhoto(): void
    {
        $this->validate(
            [
                'photo' => 'image|max:2048'
            ],
            [
                'photo.image' => 'Le fichier doit être une image au format jpg, png ou webp',
                'photo.max' => 'Le fichier ne doit pas faire plus de 2 Mo',
            ]
        );
    }
};