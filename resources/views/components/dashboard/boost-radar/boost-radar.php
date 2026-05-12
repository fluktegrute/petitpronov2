<?php

use App\Models\Game;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    #[Computed]
    public function boostsRemaining(): int
    {
        return auth()->user()->boosts_remaining ?? 0;
    }

    #[Computed]
    public function crazyBet(): ?array
    {
        $game = Game::with(['homeTeam', 'awayTeam'])
            ->where('kickoff_at', '>', now())
            ->whereNotNull('odds_home')
            ->whereNotNull('odds_away')
            ->orderByRaw('GREATEST(odds_home, odds_away) DESC')
            ->first();

        if (!$game || !$game->homeTeam || !$game->awayTeam) {
            return null;
        }

        $isHomeUnderdog = $game->odds_home > $game->odds_away;
        $underdogTeam = $isHomeUnderdog ? $game->homeTeam : $game->awayTeam;
        $highestOdd = $isHomeUnderdog ? $game->odds_home : $game->odds_away;

        $exactScoreMultiplier = config('app.exact_score_odds_multiplier');
        $boosterMultiplier = config('app.booster_multiplier');
        $potentialPoints = round($highestOdd * $exactScoreMultiplier * $boosterMultiplier);

        return [
            'game' => $game,
            'underdog' => $underdogTeam,
            'odd' => $highestOdd,
            'potential' => $potentialPoints,
        ];
    }
};