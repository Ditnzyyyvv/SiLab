<?php

use App\Http\Controllers\Admin\AsetController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\LabController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Member\TiketController as MemberTiketController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicLaporController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Alur 1: Lapor Cepat Kerusakan via Scan QR Code Tanpa Login (FR-13)
Route::middleware(['throttle:30,1'])->group(function () {
    Route::get('/lapor/{kode_aset}', [PublicLaporController::class, 'create'])->name('lapor.create');
    Route::post('/lapor/{kode_aset}', [PublicLaporController::class, 'store'])->name('lapor.store');
    Route::get('/lapor/sukses/{nomor_tiket}', [PublicLaporController::class, 'success'])->name('lapor.success');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

    // Master Data Lab
    Route::resource('labs', LabController::class)->except(['show']);

    // Master Data Kategori
    Route::resource('kategoris', KategoriController::class)->except(['show']);

    // Cetak QR Code Aset
    Route::get('asets/print-all-qr', [AsetController::class, 'printAllQr'])->name('asets.print-all-qr');
    Route::get('asets/{aset}/print-qr', [AsetController::class, 'printQr'])->name('asets.print-qr');

    // Master Data Aset
    Route::resource('asets', AsetController::class);
});

// Teknisi Routes
Route::middleware(['auth', 'role:teknisi'])->prefix('teknisi')->name('teknisi.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'teknisi'])->name('dashboard');
});

// Member Routes
Route::middleware(['auth', 'role:member'])->prefix('member')->name('member.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'member'])->name('dashboard');

    // Pengaduan Kerusakan & Tiket Saya (FR-13, FR-15)
    Route::resource('tikets', MemberTiketController::class)->only(['index', 'create', 'store', 'show']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
