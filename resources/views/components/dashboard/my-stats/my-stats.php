<?php

use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    #[Computed]
    public function user(): User
    {
        return auth()->user();
    }
};