<?php

use App\Models\Message;

use Illuminate\Support\Collection;

use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mews\Purifier\Facades\Purifier;

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

        if (strlen($this->body) > 20000) {
            $this->addError('body', 'Message trop long.');
            return;
        }

        $formattedBody = preg_replace(
            '/<a[^>]*href="([^"]+\.(?:png|jpg|jpeg|gif|webp)(?:\?[^"]*)?)"[^>]*>.*?<\/a>/i',
            '<img src="$1" class="rounded-lg max-h-64 object-contain mt-2 mb-2" loading="lazy">',
            $this->body
        );

        $sanitized = Purifier::clean($formattedBody, 'ponybet');

        Message::create([
            'league_id' => $this->leagueId,
            'user_id' => auth()->id(),
            'content' => $sanitized,
        ]);

        $this->body = '';
        $this->dispatch('message-sent');
    }
};