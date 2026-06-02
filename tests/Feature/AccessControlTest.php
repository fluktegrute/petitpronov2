<?php

use App\Models\Game;
use App\Models\League;
use App\Models\User;

// --- Guest redirects ---

test('guest is redirected to login when accessing dashboard', function () {
    $this->get(route('dashboard'))->assertRedirect(route('login'));
});

test('guest is redirected to login when accessing matches', function () {
    $this->get(route('matches'))->assertRedirect(route('login'));
});

test('guest is redirected to login when accessing standings', function () {
    $this->get(route('standings'))->assertRedirect(route('login'));
});

test('guest is redirected to login when accessing leagues', function () {
    $this->get(route('leagues'))->assertRedirect(route('login'));
});

test('guest is redirected to login when accessing profile', function () {
    $this->get(route('profile'))->assertRedirect(route('login'));
});

test('guest is redirected to login when accessing the admin panel', function () {
    $this->get(route('admin'))->assertRedirect(route('login'));
});

// --- Authenticated access ---

test('authenticated user can access the dashboard', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('dashboard'))
        ->assertOk();
});

test('authenticated user can access the matches page', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('matches'))
        ->assertOk();
});

test('authenticated user can access the leagues page', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('leagues'))
        ->assertOk();
});

test('authenticated user can access the profile page', function () {
    $user = User::factory()->create();
    Game::factory()->create(['kickoff_at' => now()->addDays(5)]);

    $this->actingAs($user)
        ->get(route('profile'))
        ->assertOk();
});

// --- Admin middleware ---

test('non-admin user is redirected from the admin panel', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('admin'))
        ->assertRedirect(route('dashboard'));
});

test('admin user can access the admin panel', function () {
    $this->actingAs(User::factory()->admin()->create())
        ->get(route('admin'))
        ->assertOk();
});

// --- Private league access ---

test('non-member cannot access a private league page', function () {
    $league = League::factory()->create();

    $this->actingAs(User::factory()->create())
        ->get(route('leagues.private', $league->invite_code))
        ->assertRedirect(route('leagues'));
});

test('member can access their private league page', function () {
    $owner = User::factory()->create();
    $league = League::factory()->create(['owner_id' => $owner->id]);
    $league->users()->attach($owner->id);

    $this->actingAs($owner)
        ->get(route('leagues.private', $league->invite_code))
        ->assertOk();
});
