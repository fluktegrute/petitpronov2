<?php

use App\Models\Game;
use App\Models\League;
use App\Models\Prediction;
use App\Models\Team;
use App\Models\User;

test('initials returns first letter of each word for a full name', function () {
    $user = User::factory()->make(['name' => 'Master Pony']);
    expect($user->initials())->toBe('MP');
});

test('initials returns single letter for a single-word name', function () {
    $user = User::factory()->make(['name' => 'Pony']);
    expect($user->initials())->toBe('P');
});

test('initials takes only the first two words', function () {
    $user = User::factory()->make(['name' => 'Ultimate Master Pony']);
    expect($user->initials())->toBe('UM');
});

test('isAdmin is false for a regular user', function () {
    $user = User::factory()->create();
    expect($user->isAdmin)->toBeFalse();
});

test('isAdmin is true for an admin user', function () {
    $user = User::factory()->admin()->create();
    expect($user->isAdmin)->toBeTrue();
});

test('total_score sums prediction points from the relation', function () {
    $user = User::factory()->create();
    $game1 = Game::factory()->create();
    $game2 = Game::factory()->create();

    Prediction::factory()->create(['user_id' => $user->id, 'game_id' => $game1->id, 'points' => 30]);
    Prediction::factory()->create(['user_id' => $user->id, 'game_id' => $game2->id, 'points' => 10]);

    expect((int) $user->fresh()->total_score)->toBe(40);
});

test('total_score is zero when user has no predictions', function () {
    $user = User::factory()->create();
    expect($user->total_score)->toBe(0);
});

test('user has a predictions relationship', function () {
    $user = User::factory()->create();
    $game = Game::factory()->create();

    Prediction::factory()->create(['user_id' => $user->id, 'game_id' => $game->id]);

    expect($user->predictions)->toHaveCount(1);
    expect($user->predictions->first())->toBeInstanceOf(Prediction::class);
});

test('user has a leagues relationship', function () {
    $user = User::factory()->create();
    $league = League::factory()->create();
    $user->leagues()->attach($league->id);

    expect($user->leagues)->toHaveCount(1);
});

test('user has an ownedLeagues relationship', function () {
    $user = User::factory()->create();
    League::factory()->count(2)->create(['owner_id' => $user->id]);

    expect($user->ownedLeagues)->toHaveCount(2);
});

test('user winner team relationship resolves correctly', function () {
    $team = Team::factory()->create();
    $user = User::factory()->create(['winner_team_id' => $team->id]);

    expect($user->winnerTeam)->toBeInstanceOf(Team::class);
    expect($user->winnerTeam->id)->toBe($team->id);
});
