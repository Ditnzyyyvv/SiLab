<?php

use App\Http\Controllers\Admin\AsetController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\LabController;
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;
use App\Http\Controllers\Admin\TiketController as AdminTiketController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Member\TiketController as MemberTiketController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicLaporController;
use App\Http\Controllers\Teknisi\TugasController as TeknisiTugasController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicLaporController::class, 'home'])->name('home');

// Alur 1: Lapor Cepat Kerusakan Tanpa Login (Beranda & Scan QR - FR-13)
Route::middleware(['throttle:30,1'])->group(function () {
    Route::post('/lapor', [PublicLaporController::class, 'storePublic'])->name('lapor.store-public');
    Route::get('/lapor/{kode_aset}', [PublicLaporController::class, 'create'])->name('lapor.create');
    Route::post('/lapor/{kode_aset}', [PublicLaporController::class, 'store'])->name('lapor.store');
    Route::get('/lapor/sukses/{nomor_tiket}', [PublicLaporController::class, 'success'])->name('lapor.success');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'identity.completed'])
    ->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'identity.completed', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
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

    // Manajemen Pengguna (Admin, Teknisi, Member)
    Route::resource('users', AdminUserController::class)->except(['show']);

    // Pengaduan & Disposisi Tiket (FR-16, FR-17, FR-18, FR-19)
    Route::get('tikets', [AdminTiketController::class, 'index'])->name('tikets.index');
    Route::get('tikets/{tiket}', [AdminTiketController::class, 'show'])->name('tikets.show');
    Route::post('tikets/{tiket}/disposisi', [AdminTiketController::class, 'disposisi'])->name('tikets.disposisi');
    Route::post('tikets/{tiket}/tolak', [AdminTiketController::class, 'tolak'])->name('tikets.tolak');
    Route::post('tikets/{tiket}/konfirmasi-tamu', [AdminTiketController::class, 'konfirmasiTamu'])->name('tikets.konfirmasi-tamu');

    // Modul Laporan & Ekspor PDF / Excel (FR-29, FR-30)
    Route::get('laporan', [AdminLaporanController::class, 'index'])->name('laporan.index');
    Route::get('laporan/aset', [AdminLaporanController::class, 'aset'])->name('laporan.aset');
    Route::get('laporan/aset/pdf', [AdminLaporanController::class, 'asetPdf'])->name('laporan.aset.pdf');
    Route::get('laporan/aset/excel', [AdminLaporanController::class, 'asetExcel'])->name('laporan.aset.excel');
    Route::get('laporan/perbaikan', [AdminLaporanController::class, 'perbaikan'])->name('laporan.perbaikan');
    Route::get('laporan/perbaikan/pdf', [AdminLaporanController::class, 'perbaikanPdf'])->name('laporan.perbaikan.pdf');
    Route::get('laporan/perbaikan/excel', [AdminLaporanController::class, 'perbaikanExcel'])->name('laporan.perbaikan.excel');
});

// Teknisi Routes
Route::middleware(['auth', 'identity.completed', 'role:teknisi'])->prefix('teknisi')->name('teknisi.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'teknisi'])->name('dashboard');

    // Tugas Perbaikan Teknisi (FR-20, FR-21, FR-22, FR-23, FR-24)
    Route::get('tugas', [TeknisiTugasController::class, 'index'])->name('tugas.index');
    Route::get('tugas/{tiket}', [TeknisiTugasController::class, 'show'])->name('tugas.show');
    Route::post('tugas/{tiket}/status', [TeknisiTugasController::class, 'updateStatus'])->name('tugas.update-status');
});

// Member Routes
Route::middleware(['auth', 'identity.completed', 'role:member'])->prefix('member')->name('member.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'member'])->name('dashboard');

    // Pengaduan Kerusakan & Tiket Saya (FR-13, FR-15, FR-19)
    Route::resource('tikets', MemberTiketController::class)->only(['index', 'create', 'store', 'show']);
    Route::post('tikets/{tiket}/konfirmasi', [MemberTiketController::class, 'konfirmasi'])->name('tikets.konfirmasi');
    Route::post('tikets/{tiket}/reopen', [MemberTiketController::class, 'reopen'])->name('tikets.reopen');
});

Route::middleware(['auth', 'identity.completed'])->group(function () {
    // Notifikasi Dalam Aplikasi (FR-28)
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::get('/notifikasi/{id}/read', [NotifikasiController::class, 'read'])->name('notifikasi.read');
    Route::post('/notifikasi/mark-all-read', [NotifikasiController::class, 'markAllRead'])->name('notifikasi.mark-all-read');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
