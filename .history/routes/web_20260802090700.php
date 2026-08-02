<?php

use App\Http\Controllers\CollectionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LyricController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SongController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Authenticated routes
Route::middleware(['auth'])->group(function () {

    Route::resource('users', UserController::class);

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // AJAX endpoints (must be before resource routes to avoid conflicts)
    Route::post('/songs/{song}/transpose', [SongController::class, 'transposeAjax'])->name('songs.transpose.ajax');
    Route::post('/lyrics/preview', [LyricController::class, 'preview'])->name('lyrics.preview');

    // Resource controllers
    Route::resource('songs', SongController::class);
    Route::resource('lyrics', LyricController::class)->except(['index', 'show']);
    Route::resource('collections', CollectionController::class)->except(['show']);
});

require __DIR__.'/auth.php';
