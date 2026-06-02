<?php

use App\Models\Game;
use App\Models\Team;

test('games attribute merges home and away games', function () {
    $team = Team::factory()->create();
    $opponent = Team::factory()->create();

    Game::factory()->create(['home_team_id' => $team->id, 'away_team_id' => $opponent->id]);
    Game::factory()->create(['home_team_id' => $opponent->id, 'away_team_id' => $team->id]);

    $team->load('homeGames', 'awayGames');

    expect($team->games)->toHaveCount(2);
});

test('team homeGames relationship returns only home games', function () {
    $team = Team::factory()->create();
    $opponent = Team::factory()->create();

    Game::factory()->create(['home_team_id' => $team->id, 'away_team_id' => $opponent->id]);
    Game::factory()->create(['home_team_id' => $opponent->id, 'away_team_id' => $team->id]);

    expect($team->homeGames)->toHaveCount(1);
});

test('team awayGames relationship returns only away games', function () {
    $team = Team::factory()->create();
    $opponent = Team::factory()->create();

    Game::factory()->create(['home_team_id' => $team->id, 'away_team_id' => $opponent->id]);
    Game::factory()->create(['home_team_id' => $opponent->id, 'away_team_id' => $team->id]);

    expect($team->awayGames)->toHaveCount(1);
});
