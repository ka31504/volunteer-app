<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\ParticipantController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\WelcomeController;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// Profile (Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Dashboard — auth đủ, không cần admin
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// CRUD — chỉ Admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('projects', ProjectController::class);
    Route::resource('donations', DonationController::class);
    Route::resource('participants', ParticipantController::class);
});

// Xem — auth đủ (cả Admin lẫn User)
Route::middleware(['auth'])->group(function () {
    Route::resource('projects', ProjectController::class)->only(['index', 'show']);
    Route::resource('donations', DonationController::class)->only(['index', 'show']);
    Route::resource('participants', ParticipantController::class)->only(['index', 'show']);
});

// CRUD — chỉ Admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('projects', ProjectController::class)->except(['index', 'show']);
    Route::resource('donations', DonationController::class)->except(['index', 'show']);
    Route::resource('participants', ParticipantController::class)->except(['index', 'show']);
    Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics.index');
    Route::get('/statistics/export-pdf', [StatisticsController::class, 'exportPdf'])
    ->name('statistics.export-pdf')
    ->middleware('auth');
});

require __DIR__.'/auth.php';