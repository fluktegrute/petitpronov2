<?php

use Illuminate\Support\Facades\Route;

use App\Http\Middleware\EnsureUserIsAdmin;

Route::get('/', function(){
    return view('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::livewire('matches', 'matches')->name('matches');
    Route::livewire('standings', 'standings')->name('standings');
    Route::livewire('leagues', 'leagues')->name('leagues');
    Route::livewire('leagues/global', 'leagues.global')->name('leagues.global');
    Route::livewire('leagues/create', 'leagues.create')->name('leagues.create');
    Route::livewire('leagues/{code}', 'leagues.private')->name('leagues.private');
    Route::livewire('profile', 'profile')->name('profile');

    Route::livewire('admin', 'admin.index')->name('admin')->middleware(EnsureUserIsAdmin::class);
});

require __DIR__.'/settings.php';
