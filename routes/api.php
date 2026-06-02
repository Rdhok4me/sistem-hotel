<?php
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\KamarApiController;
use App\Http\Controllers\Api\TamuApiController;
use App\Http\Controllers\Api\ReservasiApiController;
use App\Http\Controllers\Api\DashboardApiController;
use Illuminate\Support\Facades\Route;

// ── Publik (tanpa autentikasi) ────────────────────────────
Route::post('/login', [AuthApiController::class, 'login']);

// ── Dilindungi token Sanctum ──────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthApiController::class, 'logout']);
    Route::get('/me', [AuthApiController::class, 'me']);

    // Kamar
    Route::get('/kamar', [KamarApiController::class, 'index']);
    Route::get('/kamar/tersedia', [KamarApiController::class, 'tersedia']);
    Route::get('/kamar/{id}', [KamarApiController::class, 'show']);

    // Tamu
    Route::get('/tamu', [TamuApiController::class, 'index']);
    Route::post('/tamu', [TamuApiController::class, 'store']);
    Route::get('/tamu/{id}', [TamuApiController::class, 'show']);

    // Reservasi
    Route::get('/reservasi', [ReservasiApiController::class, 'index']);
    Route::post('/reservasi', [ReservasiApiController::class, 'store']);
    Route::get('/reservasi/{id}', [ReservasiApiController::class, 'show']);

    // Dashboard
    Route::get('/dashboard', [DashboardApiController::class, 'index']);
});