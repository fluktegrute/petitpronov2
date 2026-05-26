<?php

use App\Models\Message;

use Illuminate\Support\Collection;

use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    #[Locked]
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

        $formattedBody = preg_replace(
            '/<a[^>]*href="([^"]+\.(?:png|jpg|jpeg|gif|webp)(?:\?[^"]*)?)"[^>]*>.*?<\/a>/i',
            '<img src="$1" class="rounded-lg max-h-64 object-contain mt-2 mb-2" loading="lazy">',
            $this->body
        );

        Message::create([
            'league_id' => $this->leagueId,
            'user_id' => auth()->id(),
            'content' => $formattedBody,
        ]);

        $this->body = '';
        $this->dispatch('message-sent');
    }
};