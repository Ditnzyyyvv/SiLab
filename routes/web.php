<?php

use App\Http\Controllers\Admin\AsetController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\LabController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Models\Aset;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Halaman Publik Tujuan QR Code Lapor Cepat (Fase 3 akan melengkapi form pengaduan)
Route::get('/lapor/{kode_aset}', function (string $kode_aset) {
    $aset = Aset::with(['lab', 'kategori'])->where('kode_aset', $kode_aset)->firstOrFail();
    return view('lapor.scan-preview', compact('aset'));
})->name('lapor.scan');

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
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
