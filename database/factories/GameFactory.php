<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Game>
 */
class GameFactory extends Factory
{
    public function definition(): array
    {
        return [
            'api_id'       => fake()->unique()->randomNumber(6),
            'kickoff_at'   => now()->utc()->addDays(fake()->numberBetween(1, 30)),
            'status'       => 'SCHEDULED',
            'stage'        => 'GROUP_STAGE',
            'group'        => fake()->randomElement(['GROUP_A', 'GROUP_B', 'GROUP_C', 'GROUP_D']),
            'bracket_slot' => null,
            'matchday'     => null,
            'home_team_id' => Team::factory(),
            'away_team_id' => Team::factory(),
            'home_score'   => null,
            'away_score'   => null,
            'odds_home'    => null,
            'odds_draw'    => null,
            'odds_away'    => null,
            'winner_team_id' => null,
        ];
    }

    public function finished(int $homeScore = 1, int $awayScore = 0): static
    {
        return $this->state(fn () => [
            'status'     => 'FINISHED',
            'kickoff_at' => now()->utc()->subDays(1),
            'home_score' => $homeScore,
            'away_score' => $awayScore,
        ]);
    }

    public function inProgress(): static
    {
        return $this->state(fn () => [
            'status'     => 'IN_PLAY',
            'kickoff_at' => now()->utc()->subMinutes(30),
        ]);
    }
}
