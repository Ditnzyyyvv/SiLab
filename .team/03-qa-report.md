# 03 — QA Test & Verification Report (.team)

> **Pemilik Dokumen:** Divisi 4 (QA Tester)  
> **Terakhir Diperbarui:** 2026-11-13  
> **Hasil Pengujian Terakhir:** 80/80 Tests PASSED (348 Assertions)

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
| **TOTAL KESELURUHAN** | **Fase 0 s.d. Fase 6 Selesai** | **80** | **348** | **100% PASS** |

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

---

## 3. Catatan Pengujian Tambahan & Edge Cases

### [TEST-008] Pengujian Modul Laporan & Ekspor (Fase 6)
- **Rute yang Diuji:** `/admin/laporan/aset`, `/admin/laporan/perbaikan`, `/admin/laporan/aset/pdf`, `/admin/laporan/aset/excel`, `/admin/laporan/perbaikan/pdf`, `/admin/laporan/perbaikan/excel`.
- **Metode:** Ekspor PDF (DomPDF landscape) & Ekspor Excel (StreamedResponse UTF-8 BOM).
- **Hasil:** PASS (8 tests, 56 assertions).
- **Catatan:** Format CSV ber-BOM UTF-8 terbuka rapi di Microsoft Excel tanpa karakter rusak. Layout PDF landscape memenuhi standar KOP dinas/sekolah dengan blok tanda tangan pejabat berwenang.
