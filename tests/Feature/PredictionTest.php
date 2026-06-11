<?php

use App\Models\Game;
use App\Models\Prediction;
use App\Models\User;
use Livewire\Livewire;

test('setting a score creates a new prediction', function () {
    $user = User::factory()->create();
    $game = Game::factory()->create(['kickoff_at' => now()->utc()->addHours(2)]);

    $this->actingAs($user);

    Livewire::test('matches')
        ->set("predictions.{$game->id}.home_score", 2)
        ->set("predictions.{$game->id}.away_score", 1);

    expect(Prediction::where('user_id', $user->id)->where('game_id', $game->id)->exists())->toBeTrue();
});

test('updating a score updates the existing prediction', function () {
    $user = User::factory()->create();
    $game = Game::factory()->create(['kickoff_at' => now()->utc()->addHours(2)]);
    Prediction::factory()->withScores(1, 0)->create(['user_id' => $user->id, 'game_id' => $game->id]);

    $this->actingAs($user);

    Livewire::test('matches')
        ->set("predictions.{$game->id}.home_score", 3)
        ->set("predictions.{$game->id}.away_score", 2);

    $prediction = Prediction::where('user_id', $user->id)->where('game_id', $game->id)->first();
    expect($prediction->home_score)->toBe(3);
    expect($prediction->away_score)->toBe(2);
    expect(Prediction::where('user_id', $user->id)->where('game_id', $game->id)->count())->toBe(1);
});

test('setting a score after kickoff marks prediction as cheated', function () {
    $user = User::factory()->create();
    $game = Game::factory()->create(['kickoff_at' => now()->utc()->subMinutes(5)]);

    $this->actingAs($user);

    Livewire::test('matches')
        ->set("predictions.{$game->id}.home_score", 1)
        ->set("predictions.{$game->id}.away_score", 0);

    $prediction = Prediction::where('user_id', $user->id)->where('game_id', $game->id)->first();
    expect($prediction->status)->toBe('cheated');
});

test('applying a boost creates prediction with is_boosted true and decrements user boosts', function () {
    $user = User::factory()->create(['boosts_remaining' => 3]);
    $game = Game::factory()->create(['kickoff_at' => now()->utc()->addHours(2)]);

    $this->actingAs($user);

    Livewire::test('matches')
        ->set("predictions.{$game->id}.home_score", 1)
        ->set("predictions.{$game->id}.away_score", 0)
        ->set("predictions.{$game->id}.is_boosted", true);

    $prediction = Prediction::where('user_id', $user->id)->where('game_id', $game->id)->first();
    expect((bool) $prediction->is_boosted)->toBeTrue();
    expect($user->fresh()->boosts_remaining)->toBe(2);
});

test('cannot apply boost when no boosts remaining', function () {
    $user = User::factory()->create(['boosts_remaining' => 0]);
    $game = Game::factory()->create(['kickoff_at' => now()->utc()->addHours(2)]);

    $this->actingAs($user);

    Livewire::test('matches')
        ->set("predictions.{$game->id}.home_score", 1)
        ->set("predictions.{$game->id}.away_score", 0)
        ->set("predictions.{$game->id}.is_boosted", true);

    $prediction = Prediction::where('user_id', $user->id)->where('game_id', $game->id)->first();
    expect((bool) $prediction->is_boosted)->toBeFalse();
    expect($user->fresh()->boosts_remaining)->toBe(0);
});

test('removing a boost returns it to the user', function () {
    $user = User::factory()->create(['boosts_remaining' => 2]);
    $game = Game::factory()->create(['kickoff_at' => now()->utc()->addHours(2)]);
    Prediction::factory()->withScores(1, 0)->boosted()->create(['user_id' => $user->id, 'game_id' => $game->id]);

    $this->actingAs($user);

    Livewire::test('matches')
        ->set("predictions.{$game->id}.is_boosted", false);

    expect($user->fresh()->boosts_remaining)->toBe(3);
    $prediction = Prediction::where('user_id', $user->id)->where('game_id', $game->id)->first();
    expect((bool) $prediction->is_boosted)->toBeFalse();
});
