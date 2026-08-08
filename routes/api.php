<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DonationController;
use App\Http\Controllers\Api\ParticipantController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\SponsorController;
use Illuminate\Support\Facades\Route;

// ─── Public ─────────────────────────────────────────────────────
Route::post('/login', [AuthController::class, 'login']);

// ─── Auth (mọi role đã đăng nhập) ──────────────────────────────
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Xem: cả 3 role đều được (mask áp dụng ở Resource, không phải ở route)
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::get('/projects/{project}', [ProjectController::class, 'show']);
    Route::get('/donations', [DonationController::class, 'index']);
    Route::get('/donations/{donation}', [DonationController::class, 'show']);
    Route::get('/participants', [ParticipantController::class, 'index']);
    Route::get('/participants/{participant}', [ParticipantController::class, 'show']);
    Route::get('/sponsors', [SponsorController::class, 'index']);
    Route::get('/sponsors/{sponsor}', [SponsorController::class, 'show']);

    // Thêm/sửa: admin + editor — dùng lại alias middleware đã có bên web
    Route::middleware('admin_or_editor')->group(function () {
        Route::post('/projects', [ProjectController::class, 'store']);
        Route::put('/projects/{project}', [ProjectController::class, 'update']);

        Route::post('/donations', [DonationController::class, 'store']);
        Route::put('/donations/{donation}', [DonationController::class, 'update']);

        Route::post('/participants', [ParticipantController::class, 'store']);
        Route::put('/participants/{participant}', [ParticipantController::class, 'update']);

        Route::post('/sponsors', [SponsorController::class, 'store']);
        Route::put('/sponsors/{sponsor}', [SponsorController::class, 'update']);
    });

    // Xoá: chỉ admin
    Route::middleware('admin')->group(function () {
        Route::delete('/projects/{project}', [ProjectController::class, 'destroy']);
        Route::delete('/donations/{donation}', [DonationController::class, 'destroy']);
        Route::delete('/participants/{participant}', [ParticipantController::class, 'destroy']);
        Route::delete('/sponsors/{sponsor}', [SponsorController::class, 'destroy']);
    });
});
