<?php

use App\Models\League;
use App\Models\LeagueUser;
use App\Models\User;
use Livewire\Livewire;

// --- League creation ---

test('authenticated user can create a league', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test('leagues.create')
        ->set('name', 'Les Poneys Sauvages')
        ->set('description', 'La meilleure ligue du monde')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect();

    $this->assertDatabaseHas('leagues', [
        'name'     => 'Les Poneys Sauvages',
        'owner_id' => $user->id,
    ]);
});

test('creating a league automatically adds the creator as a member', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test('leagues.create')
        ->set('name', 'Ma Ligue')
        ->set('description', '')
        ->call('save')
        ->assertHasNoErrors();

    $league = League::where('owner_id', $user->id)->first();

    expect(LeagueUser::where('league_id', $league->id)->where('user_id', $user->id)->exists())->toBeTrue();
});

test('league name is required', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test('leagues.create')
        ->set('name', '')
        ->call('save')
        ->assertHasErrors(['name']);
});

test('league name must be at least 3 characters', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test('leagues.create')
        ->set('name', 'AB')
        ->call('save')
        ->assertHasErrors(['name']);
});

// --- Join league ---

test('user can join a league with a valid invite code', function () {
    $owner = User::factory()->create();
    $league = League::factory()->create(['owner_id' => $owner->id]);
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test('leagues')
        ->set('joinCode', $league->invite_code)
        ->call('joinLeague')
        ->assertHasNoErrors()
        ->assertRedirect(route('leagues.private', $league->invite_code));

    expect(LeagueUser::where('league_id', $league->id)->where('user_id', $user->id)->exists())->toBeTrue();
});

test('joining with an invalid code fails validation', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test('leagues')
        ->set('joinCode', 'INVALID')
        ->call('joinLeague')
        ->assertHasErrors(['joinCode']);
});

test('joining the same league twice is idempotent', function () {
    $owner = User::factory()->create();
    $league = League::factory()->create(['owner_id' => $owner->id]);
    $user = User::factory()->create();
    $league->users()->attach($user->id);

    $this->actingAs($user);

    Livewire::test('leagues')
        ->set('joinCode', $league->invite_code)
        ->call('joinLeague')
        ->assertHasNoErrors();

    expect(LeagueUser::where('league_id', $league->id)->where('user_id', $user->id)->count())->toBe(1);
});

// --- Admin actions ---

test('league admin can remove a member', function () {
    $owner = User::factory()->create();
    $league = League::factory()->create(['owner_id' => $owner->id]);
    $member = User::factory()->create();
    $league->users()->attach([$owner->id, $member->id]);

    $this->actingAs($owner);

    Livewire::test('leagues.private', ['code' => $league->invite_code])
        ->call('removeUser', $member->id)
        ->assertHasNoErrors();

    expect(LeagueUser::where('league_id', $league->id)->where('user_id', $member->id)->exists())->toBeFalse();
});

test('non-admin cannot remove a member', function () {
    $owner = User::factory()->create();
    $league = League::factory()->create(['owner_id' => $owner->id]);
    $member = User::factory()->create();
    $league->users()->attach([$owner->id, $member->id]);

    $this->actingAs($member);

    Livewire::test('leagues.private', ['code' => $league->invite_code])
        ->call('removeUser', $owner->id)
        ->assertStatus(403);

    // Owner should NOT have been removed
    expect(LeagueUser::where('league_id', $league->id)->where('user_id', $owner->id)->exists())->toBeTrue();
});

test('admin can update the league description', function () {
    $owner = User::factory()->create();
    $league = League::factory()->create(['owner_id' => $owner->id]);
    $league->users()->attach($owner->id);

    $this->actingAs($owner);

    Livewire::test('leagues.private', ['code' => $league->invite_code])
        ->set('description', 'Nouvelle description')
        ->assertHasNoErrors();

    expect($league->fresh()->description)->toBe('Nouvelle description');
});
