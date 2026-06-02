<?php

use App\Models\User;

test('user can log out', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('logout'))
        ->assertRedirect(route('home'));

    $this->assertGuest();
});

test('unauthenticated request to logout redirects to login', function () {
    $this->post(route('logout'))->assertRedirect(route('login'));
});

test('login fails with wrong password', function () {
    $user = User::factory()->create();

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'wrong-password',
    ])->assertSessionHasErrors();

    $this->assertGuest();
});

test('login fails with unknown email', function () {
    $this->post(route('login.store'), [
        'email' => 'inconnu@example.com',
        'password' => 'password',
    ])->assertSessionHasErrors();

    $this->assertGuest();
});

test('login succeeds with correct credentials', function () {
    $user = User::factory()->create();

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ])->assertSessionHasNoErrors()
      ->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticated();
});

test('authenticated user can access the dashboard', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk();
});
