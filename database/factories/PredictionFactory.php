<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\Prediction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Prediction>
 */
class PredictionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'    => User::factory(),
            'game_id'    => Game::factory(),
            'home_score' => null,
            'away_score' => null,
            'is_boosted' => 0,
            'points'     => 0,
            'status'     => 'placed',
        ];
    }

    public function withScores(int $home = 1, int $away = 0): static
    {
        return $this->state(fn () => [
            'home_score' => $home,
            'away_score' => $away,
        ]);
    }

    public function exact(int $points = 30): static
    {
        return $this->state(fn () => [
            'status' => 'exact',
            'points' => $points,
        ]);
    }

    public function trend(int $points = 10): static
    {
        return $this->state(fn () => [
            'status' => 'trend',
            'points' => $points,
        ]);
    }

    public function boosted(): static
    {
        return $this->state(fn () => ['is_boosted' => 1]);
    }

    public function cheated(): static
    {
        return $this->state(fn () => ['status' => 'cheated', 'points' => 0]);
    }
}
