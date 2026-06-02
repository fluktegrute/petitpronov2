<?php

use App\Models\Game;
use App\Models\Prediction;
use App\Models\User;

test('prediction belongs to a user', function () {
    $prediction = Prediction::factory()->create();

    expect($prediction->user)->toBeInstanceOf(User::class);
});

test('prediction belongs to a game', function () {
    $prediction = Prediction::factory()->create();

    expect($prediction->game)->toBeInstanceOf(Game::class);
});

test('deleting a user cascades to their predictions', function () {
    $user = User::factory()->create();
    $game = Game::factory()->create();
    Prediction::factory()->create(['user_id' => $user->id, 'game_id' => $game->id]);

    $user->delete();

    expect(Prediction::where('user_id', $user->id)->count())->toBe(0);
});

test('deleting a game cascades to its predictions', function () {
    $user = User::factory()->create();
    $game = Game::factory()->create();
    Prediction::factory()->create(['user_id' => $user->id, 'game_id' => $game->id]);

    $game->delete();

    expect(Prediction::where('game_id', $game->id)->count())->toBe(0);
});
