<?php

use App\Models\League;
use App\Models\User;

test('league owner relationship resolves correctly', function () {
    $owner = User::factory()->create();
    $league = League::factory()->create(['owner_id' => $owner->id]);

    expect($league->owner)->toBeInstanceOf(User::class);
    expect($league->owner->id)->toBe($owner->id);
});

test('usersCount returns the number of members in the league', function () {
    $owner = User::factory()->create();
    $league = League::factory()->create(['owner_id' => $owner->id]);
    $members = User::factory()->count(3)->create();
    $league->users()->attach($members->pluck('id'));

    expect($league->fresh()->usersCount)->toBe(3);
});

test('usersCount is zero for a league with no members', function () {
    $league = League::factory()->create();
    expect($league->usersCount)->toBe(0);
});

test('deleting a league cascades to league_user pivot records', function () {
    $owner = User::factory()->create();
    $league = League::factory()->create(['owner_id' => $owner->id]);
    $member = User::factory()->create();
    $league->users()->attach($member->id);

    $league->delete();

    expect(\DB::table('league_user')->where('league_id', $league->id)->count())->toBe(0);
});

test('deleting the owner cascades to their leagues', function () {
    $owner = User::factory()->create();
    League::factory()->count(2)->create(['owner_id' => $owner->id]);

    $owner->delete();

    expect(League::count())->toBe(0);
});
