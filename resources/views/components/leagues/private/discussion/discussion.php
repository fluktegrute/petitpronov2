<?php

use App\Models\Message;

use Illuminate\Support\Collection;

use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    public int $leagueId;
    public string $body = '';

    public function mount(int $leagueId): void 
    {
        $this->leagueId = $leagueId;
    }

    #[Computed]
    public function messages(): Collection
    {
        return Message::with('user')
            ->where('league_id', $this->leagueId)
            ->orderBy('created_at', 'DESC')
            ->get();
    }

    public function sendMessage(): void
    {
        $this->validate(
            [
                'body' => 'required|string|max:5000',
            ],
            [
                'body.required' => "Tu as oublié d'écrire quelque-chose",
                'body.max' => "Le message ne peut pas faire plus de :max caractères"
            ]
        );

        Message::create([
            'league_id' => $this->leagueId,
            'user_id' => auth()->id(),
            'content' => $this->body,
        ]);

        $this->body = '';
        $this->dispatch('message-sent');
    }
};