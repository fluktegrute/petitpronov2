<?php

use App\Models\Game;
use App\Models\Team;
use App\Models\User;
use Livewire\Livewire;

test('profile page requires authentication', function () {
    $this->get(route('profile'))->assertRedirect(route('login'));
});

test('authenticated user can view their profile page', function () {
    Game::factory()->create(['kickoff_at' => now()->addDays(5)]);

    $this->actingAs(User::factory()->create())
        ->get(route('profile'))
        ->assertOk();
});

test('settings routes redirect to profile', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get('/settings')->assertRedirect('/profile');
    $this->actingAs($user)->get('/settings/profile')->assertRedirect('/profile');
});

test('user can update their name', function () {
    $user = User::factory()->create();
    Game::factory()->create(['kickoff_at' => now()->addDays(5)]);

    $this->actingAs($user);

    Livewire::test('profile')
        ->set('name', 'Nouveau Pseudo')
        ->set('email', $user->email)
        ->call('updateProfile')
        ->assertHasNoErrors();

    expect($user->fresh()->name)->toBe('Nouveau Pseudo');
});

test('user cannot take an already used username', function () {
    $existing = User::factory()->create(['name' => 'NomPris']);
    $user = User::factory()->create();
    Game::factory()->create(['kickoff_at' => now()->addDays(5)]);

    $this->actingAs($user);

    Livewire::test('profile')
        ->set('name', 'NomPris')
        ->set('email', $user->email)
        ->call('updateProfile')
        ->assertHasErrors(['name']);
});

test('user can update their password', function () {
    $user = User::factory()->create();
    Game::factory()->create(['kickoff_at' => now()->addDays(5)]);

    $this->actingAs($user);

    Livewire::test('profile')
        ->set('current_password', 'password')
        ->set('new_password', 'newpassword')
        ->set('new_password_confirmation', 'newpassword')
        ->call('updatePassword')
        ->assertHasNoErrors();

    expect(\Illuminate\Support\Facades\Hash::check('newpassword', $user->fresh()->password))->toBeTrue();
});

test('wrong current password is rejected when updating password', function () {
    $user = User::factory()->create();
    Game::factory()->create(['kickoff_at' => now()->addDays(5)]);

    $this->actingAs($user);

    Livewire::test('profile')
        ->set('current_password', 'mauvais-mot-de-passe')
        ->set('new_password', 'newpassword')
        ->set('new_password_confirmation', 'newpassword')
        ->call('updatePassword')
        ->assertHasErrors(['current_password']);
});

test('winner team can be set before the tournament starts', function () {
    $team = Team::factory()->create();
    $user = User::factory()->create();
    Game::factory()->create(['kickoff_at' => now()->addDays(5)]);

    $this->actingAs($user);

    Livewire::test('profile')
        ->set('name', $user->name)
        ->set('email', $user->email)
        ->set('winner_team_id', $team->id)
        ->call('updateProfile')
        ->assertHasNoErrors();

    expect($user->fresh()->winner_team_id)->toBe($team->id);
});
