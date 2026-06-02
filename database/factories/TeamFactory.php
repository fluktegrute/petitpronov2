<?php

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Team>
 */
class TeamFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'           => fake()->unique()->country(),
            'code'           => strtoupper(fake()->unique()->lexify('???')),
            'api_id'         => null,
            'pool'           => fake()->randomElement(['GROUP_A', 'GROUP_B', 'GROUP_C', 'GROUP_D']),
            'position'       => null,
            'flag_path'      => 'storage/images/flags/default.png',
            'played'         => 0,
            'won'            => 0,
            'draw'           => 0,
            'lost'           => 0,
            'points'         => 0,
            'goals_for'      => 0,
            'goals_against'  => 0,
            'goal_diff'      => 0,
            'is_final_winner' => false,
        ];
    }

    public function winner(): static
    {
        return $this->state(fn () => ['is_final_winner' => true]);
    }
}
