<?php
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\CheckOutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KamarController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\TamuController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ── Redirect root ─────────────────────────────────────────────
Route::get('/', fn () => redirect()->route('login'));

// ── Autentikasi ───────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ── Dashboard Resepsionis ─────────────────────────────────────
Route::middleware(['auth', 'role:resepsionis|admin'])
    ->get('/dashboard', [DashboardController::class, 'resepsionis'])
    ->name('dashboard');

// ── Grup Resepsionis & Admin ──────────────────────────────────
Route::middleware(['auth', 'role:resepsionis|admin'])->group(function () {
    Route::resource('reservasi', ReservasiController::class);
    Route::post('reservasi/{reservasi}/checkin',  [CheckInController::class,  'store'])->name('checkin.store');
    Route::post('reservasi/{reservasi}/checkout', [CheckOutController::class, 'store'])->name('checkout.store');
    Route::post('reservasi/{reservasi}/pembayaran', [PembayaranController::class, 'store'])->name('pembayaran.store');
    Route::get('reservasi/{reservasi}/invoice',     [PembayaranController::class, 'invoice'])->name('pembayaran.invoice');
    Route::resource('tamu', TamuController::class)->except(['destroy']);
    Route::resource('kamar', KamarController::class);
    Route::patch('kamar/{kamar}/status', [KamarController::class, 'updateStatus'])->name('kamar.status');
});

// ── Grup Admin saja ───────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
    Route::get('/laporan',        [LaporanController::class, 'index'])->name('laporan');
    Route::get('/laporan/export', [LaporanController::class, 'export'])->name('laporan.export');
    Route::resource('pengeluaran', PengeluaranController::class);
    Route::resource('users', UserController::class);
    Route::patch('users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');
});