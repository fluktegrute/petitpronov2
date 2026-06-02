<?php

namespace Database\Factories;

use App\Models\League;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<League>
 */
class LeagueFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'        => fake()->words(3, true),
            'owner_id'    => User::factory(),
            'description' => '',
            'invite_code' => strtolower(fake()->unique()->bothify('????######')),
        ];
    }
}
