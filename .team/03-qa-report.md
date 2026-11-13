# 03 — QA Test & Verification Report (.team)

> **Pemilik Dokumen:** Divisi 4 (QA Tester)  
> **Terakhir Diperbarui:** 2026-11-13  
> **Hasil Pengujian Terakhir:** 80/80 Tests PASSED (348 Assertions) — Seluruh Fase 0 s.d. Fase 7 Lengkap & Tervalidasi

---

## 1. Ringkasan Eksekusi Test Suite (Automated PHPUnit / Pest)

| Test Suite File | Modul Pengujian | Total Tests | Assertions | Status |
| :--- | :--- | :---: | :---: | :---: |
| [`tests/Feature/RoleAccessTest.php`](file:///c:/Users/Lenovo/Documents/.ukk/tests/Feature/RoleAccessTest.php) | Fondasi RBAC 3 Role & Middleware | 10 | 28 | **PASS** |
| [`tests/Feature/MasterDataTest.php`](file:///c:/Users/Lenovo/Documents/.ukk/tests/Feature/MasterDataTest.php) | CRUD Labs, Kategori, Aset & QR Code | 14 | 56 | **PASS** |
| [`tests/Feature/TiketPengaduanTest.php`](file:///c:/Users/Lenovo/Documents/.ukk/tests/Feature/TiketPengaduanTest.php) | Scan QR Tamu vs Member Login | 12 | 46 | **PASS** |
| [`tests/Feature/TiketPengerjaanTest.php`](file:///c:/Users/Lenovo/Documents/.ukk/tests/Feature/TiketPengerjaanTest.php) | Disposisi, Pengerjaan Teknisi, Reopen | 14 | 64 | **PASS** |
| [`tests/Feature/DashboardDanNotifikasiTest.php`](file:///c:/Users/Lenovo/Documents/.ukk/tests/Feature/DashboardDanNotifikasiTest.php) | Chart.js 3 Role & In-App Notifications | 10 | 46 | **PASS** |
| [`tests/Feature/LaporanEksporTest.php`](file:///c:/Users/Lenovo/Documents/.ukk/tests/Feature/LaporanEksporTest.php) | Ekspor Dokumen Resmi PDF & Excel (FR-29, FR-30) | 8 | 56 | **PASS** |
| **TOTAL KESELURUHAN** | **Fase 0 s.d. Fase 7 (Final)** | **80** | **348** | **100% PASS** |

---

## 2. Checklist Verifikasi Skenario Fungsional End-to-End

- [x] **Skenario 1 (Pelapor Tamu / QR Scan):** Kamera HP scan stiker QR $\rightarrow$ Buka form `/lapor/{kode_aset}` tanpa login $\rightarrow$ Unggah keluhan & foto $\rightarrow$ Tiket terbit nomor `TKT-...` $\rightarrow$ Halaman konfirmasi sukses tampil.
- [x] **Skenario 2 (Validasi & Disposisi Admin):** Admin memfilter tiket Pending $\rightarrow$ Mengatur prioritas & menugaskan teknisi $\rightarrow$ Kondisi aset otomatis menjadi `Dalam Perbaikan` $\rightarrow$ Teknisi menerima notifikasi & tugas.
- [x] **Skenario 3 (Pengerjaan Teknisi):** Teknisi memperbarui ke `Menunggu Komponen/Sparepart` (wajib nama komponen) $\rightarrow$ Kembali ke `Diproses` $\rightarrow$ Menyelesaikan dengan status `Selesai` $\rightarrow$ Kondisi aset otomatis kembali `Baik`.
- [x] **Skenario 4 (Konfirmasi Member):** Member memeriksa hasil perbaikan di menu "Tiket Saya" $\rightarrow$ Klik "Setuju & Konfirmasi" $\rightarrow$ Status terkonfirmasi tersimpan.
- [x] **Skenario 5 (Buka Kembali oleh Member):** Member merasa perbaikan belum tuntas $\rightarrow$ Buka kembali dengan alasan $\rightarrow$ Tiket kembali ke status `Diproses` $\rightarrow$ Notifikasi masuk ke Teknisi dan Admin.
- [x] **Skenario 6 (Konfirmasi Tamu oleh Admin):** Tiket tamu yang diselesaikan teknisi diverifikasi langsung oleh Admin $\rightarrow$ Admin menutup konfirmasi tiket tamu (FR-19).
- [x] **Skenario 7 (Dashboard & Notifikasi):** 3 Dashboard menampilkan Chart.js interaktif $\rightarrow$ Lonceng notifikasi menampilkan badge unread count $\rightarrow$ Klik notifikasi mengarahkan ke tiket target dan otomatis menandai telah dibaca.
- [x] **Skenario 8 (Laporan & Rekapitulasi Ekspor):** Hub modul `/admin/laporan`, pratinjau tabel aset dengan filter kondisi & lab, pratinjau riwayat tiket dengan filter periode & teknisi, ekspor PDF landscape DomPDF ber-KOP resmi, ekspor Excel / CSV stream UTF-8 BOM untuk Microsoft Excel.
- [x] **Skenario 9 (Finalisasi & Responsivitas Mobile — Fase 7):** Form scan QR, tabel pratinjau, dashboard grafik, dropdown lonceng notifikasi, dan drawer navigasi samping teruji lancar pada viewport seluler 360 px tanpa pergeseran horizontal tak diinginkan. Seeder data demo realistis (`php artisan migrate:fresh --seed`) memuat 3 laboratorium, 11 aset dengan status beragam, 8 tiket demonstrasi siklus lengkap, dan notifikasi bawaan siap diuji.

---

## 3. Catatan Pengujian Tambahan & Edge Cases

### [TEST-009] Pengujian Responsivitas Mobile & Demo Seeder (Fase 7)
- **Komponen yang Diuji:** Layout seluler 360px (`/lapor/{kode_aset}`, `/dashboard`, drawer sidebar, dropdown notifikasi `w-72 sm:w-96`), eksekusi seeder terintegrasi (`DatabaseSeeder.php`).
- **Hasil:** PASS (80/80 PHPUnit Tests lolos, assets compile via Vite).
- **Catatan:** Dokumentasi panduan instalasi dan simulasi QR telah terbit di `README.md`.
