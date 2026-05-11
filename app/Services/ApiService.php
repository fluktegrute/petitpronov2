<?php

namespace App\Services;

use App\Models\Game;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ApiService {
    public static function getEndpoint(string $endpoint, bool $relatedToWC = true): array
    {
        $apiUrl = config('services.football_data.url');
        $apiKey = config('services.football_data.key');

        if($relatedToWC)
            $apiUrl .= 'competitions/WC/';

        $response = Http::withHeaders(
                [
                    'X-Auth-Token' => $apiKey,
                ]
            )
            ->get("$apiUrl/$endpoint");

        if($response->successful())
            return $response->json();

        Log::error("Erreur API Football-Data lors de l'appel à {$endpoint}", [
            'status' => $response->status(),
            'body'   => $response->body()
        ]);
        return [];
	}

    public static function getOdds(): array 
    {
        $params = [
            'regions' => 'eu', 
            'markets' => 'h2h', 
            'oddsFormat' => 'decimal',
            'apiKey' => config('services.odds_api.key'),
        ];

        $response = Http::get(config('services.odds_api.url'), $params);

        if($response->successful())
            return $response->json();

        Log::error("Erreur The Odds API lors de la récupération des cotes", [
            'status' => $response->status(),
            'body'   => $response->body()
        ]);
        return [];
    }
}

