<?php

use Laravel\Fortify\Http\Controllers\RegisteredUserController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

// Register
Route::get('/register', [RegisteredUserController::class, 'create'])
        ->middleware(['guest'])
        ->name('register');

Route::post('/register', [RegisteredUserController::class, 'store'])
        ->middleware(['guest']);

// Login (popup)
Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware(['guest'])
        ->name('login');

// Logout
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
