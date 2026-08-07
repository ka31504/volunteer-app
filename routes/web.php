<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\ParticipantController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\SponsorController;
use App\Http\Controllers\UserController;

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

// Xem — auth đủ (Admin + Editor + User)
Route::middleware(['auth'])->group(function () {
    Route::resource('projects', ProjectController::class)->only(['index', 'show']);
    Route::resource('donations', DonationController::class)->only(['index', 'show']);
    Route::resource('participants', ParticipantController::class)->only(['index', 'show']);
    Route::resource('sponsors', SponsorController::class)->only(['index', 'show']);
});

// Thêm/Sửa — Admin + Editor (không Delete)
Route::middleware(['auth', 'admin_or_editor'])->group(function () {
    Route::resource('projects', ProjectController::class)->only(['create', 'store', 'edit', 'update']);
    Route::resource('donations', DonationController::class)->only(['create', 'store', 'edit', 'update']);
    Route::resource('participants', ParticipantController::class)->only(['create', 'store', 'edit', 'update']);
    Route::resource('sponsors', SponsorController::class)->only(['create', 'store', 'edit', 'update']);

    Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics.index');
    Route::get('/statistics/export-pdf', [StatisticsController::class, 'exportPdf'])->name('statistics.export-pdf');
});

// Xoá + Quản lý tài khoản — chỉ Admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('projects', ProjectController::class)->only(['destroy']);
    Route::resource('donations', DonationController::class)->only(['destroy']);
    Route::resource('participants', ParticipantController::class)->only(['destroy']);
    Route::resource('sponsors', SponsorController::class)->only(['destroy']);
    Route::resource('users', UserController::class);
});

require __DIR__.'/auth.php';
