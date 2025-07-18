<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Apps\HomeController;
use App\Http\Controllers\Apps\SackMovementsController;

Route::middleware(['guest'])->group(function () {
    Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle']);
    Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

    Route::get('/', [AuthController::class, 'index']);

    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::middleware(['admin'])->prefix('apps')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('apps.index');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');

    Route::get('/sack-movements', [SackMovementsController::class, 'index'])->name('apps.sack_movements');
    Route::get('/cameras', [HomeController::class, 'cameras'])->name('apps.cameras');
});