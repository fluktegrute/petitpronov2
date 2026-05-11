<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DummyUsers extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for($i = 1; $i <= 50; $i++){
            User::factory()->create([
                'name' => "User $i",
                'email' => "test$i@example.com",
                'total_points' => rand(0, 1000),
                'prono_count' => rand(1, 20),
                'trend_count' => rand(1, 20),
                'exact_count' => rand(1, 20),
            ]);
        }
    }
}
