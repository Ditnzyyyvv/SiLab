# 03 — QA Test & Verification Report (.team)

> **Pemilik Dokumen:** Divisi 4 (QA Tester)  
> **Terakhir Diperbarui:** 2026-11-13  
> **Hasil Pengujian Terakhir:** 72/72 Tests PASSED (292 Assertions)

---

## 1. Ringkasan Eksekusi Test Suite (Automated PHPUnit / Pest)

| Test Suite File | Modul Pengujian | Total Tests | Assertions | Status |
| :--- | :--- | :---: | :---: | :---: |
| [`tests/Feature/RoleAccessTest.php`](file:///c:/Users/Lenovo/Documents/.ukk/tests/Feature/RoleAccessTest.php) | Fondasi RBAC 3 Role & Middleware | 10 | 28 | **PASS** |
| [`tests/Feature/MasterDataTest.php`](file:///c:/Users/Lenovo/Documents/.ukk/tests/Feature/MasterDataTest.php) | CRUD Labs, Kategori, Aset & QR Code | 14 | 56 | **PASS** |
| [`tests/Feature/TiketPengaduanTest.php`](file:///c:/Users/Lenovo/Documents/.ukk/tests/Feature/TiketPengaduanTest.php) | Scan QR Tamu vs Member Login | 12 | 46 | **PASS** |
| [`tests/Feature/TiketPengerjaanTest.php`](file:///c:/Users/Lenovo/Documents/.ukk/tests/Feature/TiketPengerjaanTest.php) | Disposisi, Pengerjaan Teknisi, Reopen | 14 | 64 | **PASS** |
| [`tests/Feature/DashboardDanNotifikasiTest.php`](file:///c:/Users/Lenovo/Documents/.ukk/tests/Feature/DashboardDanNotifikasiTest.php) | Chart.js 3 Role & In-App Notifications | 10 | 46 | **PASS** |
| **TOTAL KESELURUHAN** | **Fase 0 s.d. Fase 5 Selesai** | **72** | **292** | **100% PASS** |

---

## 2. Checklist Verifikasi Skenario Fungsional End-to-End

- [x] **Skenario 1 (Pelapor Tamu / QR Scan):** Kamera HP scan stiker QR $\rightarrow$ Buka form `/lapor/{kode_aset}` tanpa login $\rightarrow$ Unggah keluhan & foto $\rightarrow$ Tiket terbit nomor `TKT-...` $\rightarrow$ Halaman konfirmasi sukses tampil.
- [x] **Skenario 2 (Validasi & Disposisi Admin):** Admin memfilter tiket Pending $\rightarrow$ Mengatur prioritas & menugaskan teknisi $\rightarrow$ Kondisi aset otomatis menjadi `Dalam Perbaikan` $\rightarrow$ Teknisi menerima notifikasi & tugas.
- [x] **Skenario 3 (Pengerjaan Teknisi):** Teknisi memperbarui ke `Menunggu Komponen/Sparepart` (wajib nama komponen) $\rightarrow$ Kembali ke `Diproses` $\rightarrow$ Menyelesaikan dengan status `Selesai` $\rightarrow$ Kondisi aset otomatis kembali `Baik`.
- [x] **Skenario 4 (Konfirmasi Member):** Member memeriksa hasil perbaikan di menu "Tiket Saya" $\rightarrow$ Klik "Setuju & Konfirmasi" $\rightarrow$ Status terkonfirmasi tersimpan.
- [x] **Skenario 5 (Buka Kembali oleh Member):** Member merasa perbaikan belum tuntas $\rightarrow$ Buka kembali dengan alasan $\rightarrow$ Tiket kembali ke status `Diproses` $\rightarrow$ Notifikasi masuk ke Teknisi dan Admin.
- [x] **Skenario 6 (Konfirmasi Tamu oleh Admin):** Tiket tamu yang diselesaikan teknisi diverifikasi langsung oleh Admin $\rightarrow$ Admin menutup konfirmasi tiket tamu (FR-19).
- [x] **Skenario 7 (Dashboard & Notifikasi):** 3 Dashboard menampilkan Chart.js interaktif $\rightarrow$ Lonceng notifikasi menampilkan badge unread count $\rightarrow$ Klik notifikasi mengarahkan ke tiket target dan otomatis menandai telah dibaca.

---

## 3. Catatan Pengujian Tambahan & Edge Cases

```markdown
<!-- Format Pencatatan Pengujian Baru -->
### [TEST-XXX] Pengujian Modul Laporan & Ekspor (Fase 6)
- **Rute yang Diuji:** `/admin/laporan/aset` dan `/admin/laporan/perbaikan`
- **Metode:** Ekspor PDF (DomPDF) & Ekspor Excel
- **Input Edge-Cases:** Filter tanggal kosong, data nol hasil filter
- **Hasil:** PASS | FAIL
- **Catatan:** [Evaluasi performa render / kesesuaian cetak]
```
