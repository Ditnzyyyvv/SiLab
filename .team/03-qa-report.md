# 03 — QA Test & Verification Report (.team)

> **Pemilik Dokumen:** Divisi 4 (QA Tester)  
> **Terakhir Diperbarui:** 2026-11-13  
> **Hasil Pengujian Terakhir:** 88/88 Tests PASSED (362 Assertions) — 100% PASS, Zero Failure

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
| [`tests/Feature/SecurityDanEdgeCaseTest.php`](file:///c:/Users/Lenovo/Documents/.ukk/tests/Feature/SecurityDanEdgeCaseTest.php) | IDOR, Concurrency Aset, Lock Konfirmasi, Deletion Guard | 8 | 14 | **PASS** |
| `tests/Feature/ProfileTest.php` & `ExampleTest.php` | Pengujian Bawaan Profil & Landing Page | 12 | 52 | **PASS** |
| **TOTAL KESELURUHAN** | **Seluruh Modul & Hardening Keamanan SiLab** | **88** | **362** | **100% PASS** |

---

## 2. Checklist Verifikasi Skenario Fungsional End-to-End

- [x] **Skenario 1 (Pelapor Tamu / QR Scan):** Kamera HP scan stiker QR $\rightarrow$ Buka form `/lapor/{kode_aset}` tanpa login $\rightarrow$ Unggah keluhan & foto $\rightarrow$ Tiket terbit nomor `TKT-...` $\rightarrow$ Halaman konfirmasi sukses tampil.
- [x] **Skenario 2 (Validasi & Disposisi Admin):** Admin memfilter tiket Pending $\rightarrow$ Mengatur prioritas & menugaskan teknisi $\rightarrow$ Kondisi aset otomatis menjadi `Dalam Perbaikan` $\rightarrow$ Teknisi menerima notifikasi & tugas.
- [x] **Skenario 3 (Pengerjaan Teknisi):** Teknisi memperbarui ke `Menunggu Komponen/Sparepart` (wajib nama komponen) $\rightarrow$ Kembali ke `Diproses` $\rightarrow$ Menyelesaikan dengan status `Selesai` $\rightarrow$ Kondisi aset otomatis kembali `Baik`.
- [x] **Skenario 4 (Konfirmasi Member):** Member memeriksa hasil perbaikan di menu "Tiket Saya" $\rightarrow$ Klik "Setuju & Konfirmasi" $\rightarrow$ Status terkonfirmasi tersimpan secara permanen (terkunci dari manipulasi reopen).
- [x] **Skenario 5 (Buka Kembali oleh Member):** Member merasa perbaikan belum tuntas $\rightarrow$ Buka kembali dengan alasan $\rightarrow$ Tiket kembali ke status `Diproses` $\rightarrow$ Notifikasi masuk ke Teknisi dan Admin.
- [x] **Skenario 6 (Konfirmasi Tamu oleh Admin):** Tiket tamu yang diselesaikan teknisi diverifikasi langsung oleh Admin $\rightarrow$ Admin menutup konfirmasi tiket tamu (FR-19).
- [x] **Skenario 7 (Dashboard & Notifikasi):** 3 Dashboard menampilkan Chart.js interaktif $\rightarrow$ Lonceng notifikasi menampilkan badge unread count $\rightarrow$ Klik notifikasi mengarahkan ke tiket target dan otomatis menandai telah dibaca.
- [x] **Skenario 8 (Laporan & Rekapitulasi Ekspor):** Hub modul `/admin/laporan`, pratinjau tabel aset dengan filter kondisi & lab, pratinjau riwayat tiket dengan filter periode & teknisi, ekspor PDF landscape DomPDF ber-KOP resmi, ekspor Excel / CSV stream UTF-8 BOM untuk Microsoft Excel.
- [x] **Skenario 9 (Keamanan & Anti-Bypass IDOR):** Percobaan Member B membuka atau mengonfirmasi tiket Member A ditolak dengan HTTP 403 Forbidden. Percobaan Teknisi B meng-update tugas Teknisi A ditolak dengan HTTP 403 Forbidden.
- [x] **Skenario 10 (Concurrency Multi-Tiket pada Satu Aset):** Jika satu aset memiliki 2 tiket penanganan yang sedang berjalan, penyelesaian tiket pertama tidak mengubah aset ke `Baik` melainkan tetap `Dalam Perbaikan` sampai seluruh tiket tuntas.

---

## 3. Catatan Pengujian Tambahan & Edge Cases

### [TEST-010] Pengujian Keamanan & Mitigasi Edge Cases (Auto-Team Hardening)
- **Rute yang Diuji:** `/member/tikets/{tiket}`, `/teknisi/tugas/{tiket}`, `/admin/asets/{aset}`, `/lapor/{kode_aset}`.
- **Payload Uji:** IDOR cross-member, IDOR cross-technician, upload fake script `.php`, direct POST reopen pada tiket terkonfirmasi, delete aset ber-tiket aktif.
- **Hasil:** **100% PASS (8 test cases, 14 assertions)**.
- **Evaluasi Keamanan:** Sistem memiliki proteksi berlapis pada tingkat Form Request, Controller Policy check, dan Service Layer Transaction Guard. Seluruh penolakan hak akses menghasilkan pesan galat bahasa Indonesia yang ramah pengguna.
