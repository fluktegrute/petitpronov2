<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'profile');
    Route::redirect('/settings/profile', '/profile');
});