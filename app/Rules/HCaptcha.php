<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class HCaptcha implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (app()->environment('testing')) {
            return;
        }

        $response = Http::asForm()->post('https://hcaptcha.com/siteverify', [
            'secret' => config('services.hcaptcha.secret'),
            'response' => $value,
            'remoteip' => request()->ip(),
        ]);

        if(!$response->json('success')){
            $fail("La vérification anti-robot a échoué. Allô allô, monsieur l'Ordinateur ?");
        }
    }
}