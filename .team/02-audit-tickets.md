# 02 — Context & Bug Audit Tickets (.team)

> **Pemilik Dokumen:** Divisi 3 (Context & Bug Auditor) — Mode READ-ONLY pada kode aplikasi.  
> **Terakhir Diperbarui:** 2026-11-13  
> **Petunjuk:** Auditor menulis tiket temuan di sini. Coder memperbaikinya dan menandai `RESOLVED`. Tester memverifikasi dan menandai `VERIFIED: PASS`.

---

## Ringkasan Status Tiket Audit

| Kategori Tiket | Total Ditemukan | Open | Resolved (Coder) | Verified (Tester) |
| :--- | :---: | :---: | :---: | :---: |
| `[CRITICAL]` | 1 | 0 | 1 | **1 (PASS)** |
| `[LOGIC]` | 2 | 0 | 2 | **2 (PASS)** |
| `[CONTEXT]` | 1 | 0 | 1 | **1 (PASS)** |
| **TOTAL** | **4** | **0** | **4** | **4 (100% RESOLVED & VERIFIED)** |

---

## Daftar Tiket Audit & Riwayat Penyelesaian

### 1. Kategori `[CRITICAL]`

#### [CRITICAL-001] State Lock Pasca-Konfirmasi & Pencegahan Bypass Reopen Tiket
- **Lokasi Berkas:** `app/Services/TiketService.php` (`bukaKembaliMember`, `konfirmasiSelesaiMember`, `konfirmasiSelesaiAdmin`)
- **Pelanggaran PRD:** Bagian 9 & FR-19 (Matriks Status & Konfirmasi Akhir)
- **Deskripsi:** Sebelumnya, validasi `bukaKembaliMember` hanya memeriksa apakah status tiket sama dengan `Selesai`, namun tidak memvalidasi apakah kolom `dikonfirmasi_at` sudah terisi. Akibatnya, member dapat mengirim request POST langsung untuk membuka kembali tiket yang sudah disepakati/dikonfirmasi selesai secara permanen. Selain itu, pemanggilan konfirmasi berulang kali tidak dicegah.
- **Rekomendasi Perbaikan:** Tambahkan validasi pengecekan `$tiket->dikonfirmasi_at !== null` pada `bukaKembaliMember`, `konfirmasiSelesaiMember`, dan `konfirmasiSelesaiAdmin`.
- **Status:** **VERIFIED: PASS**
  - *Catatan Coder:* Berhasil ditambahkan pengecekan `InvalidArgumentException` pada ketiga metode di `TiketService.php`.
  - *Catatan Tester:* Lolos uji otomatis di `SecurityDanEdgeCaseTest::test_confirmed_ticket_cannot_be_reopened` dan `test_already_confirmed_ticket_cannot_be_reconfirmed`.

---

### 2. Kategori `[LOGIC]`

#### [LOGIC-001] Multi-Ticket Concurrency & Inkonsistensi Reset Kondisi Aset Menjadi 'Baik'
- **Lokasi Berkas:** `app/Services/TiketService.php` (`updateStatusTeknisi`)
- **Aturan Alur:** PRD Bagian 9 & FR-24 (Sinkronisasi Kondisi Aset Otomatis)
- **Deskripsi:** Ketika teknisi menyelesaikan tiket kerusakan menjadi `Selesai`, kondisi aset langsung diubah menjadi `Baik`. Apabila pada aset yang sama terdapat tiket kerusakan lain yang masih berstatus `Diproses` atau `Menunggu Komponen/Sparepart`, kondisi aset akan keliru tercatat `Baik` padahal perbaikan lain belum tuntas.
- **Rekomendasi Perbaikan:** Sebelum memperbarui kondisi aset ke `Baik`, periksa apakah masih ada tiket lain yang aktif (`whereIn('status', ['Pending', 'Diproses', 'Menunggu Komponen/Sparepart'])`). Jika ada, pertahankan kondisi aset tetap `Dalam Perbaikan`.
- **Status:** **VERIFIED: PASS**
  - *Catatan Coder:* Telah ditambahkan query pengecekan `$hasOtherActiveTickets` sebelum menjalankan `$tiket->aset?->update(['kondisi' => 'Baik'])`.
  - *Catatan Tester:* Terverifikasi lulus pada skenario uji multi-tiket di `SecurityDanEdgeCaseTest::test_asset_condition_remains_dalam_perbaikan_if_another_ticket_is_still_active`.

#### [LOGIC-002] Deletion Guard Aset yang Masih Memiliki Tiket Aktif
- **Lokasi Berkas:** `app/Http/Controllers/Admin/AsetController.php` (`destroy`)
- **Aturan Alur:** PRD FR-10 & Integritas Data Master Aset
- **Deskripsi:** Admin sebelumnya dapat melakukan soft delete pada aset yang tiket penanganannya masih aktif dikerjakan teknisi, sehingga berpotensi menimbulkan data yatim (*orphaned data*) saat teknisi membuka tugas perbaikan.
- **Rekomendasi Perbaikan:** Tambahkan guard pada controller untuk menolak aksi hapus jika masih terdapat tiket penanganan aktif.
- **Status:** **VERIFIED: PASS**
  - *Catatan Coder:* Ditambahkan validasi `$activeTicketsCount > 0` dengan pengalihan kembali disertai alert error informatif di `AsetController::destroy`.
  - *Catatan Tester:* Lolos uji otomatis di `SecurityDanEdgeCaseTest::test_admin_cannot_delete_asset_with_active_tickets`.

---

### 3. Kategori `[CONTEXT]`

#### [CONTEXT-001] Penyediaan Template Galat Kustom Bahasa Indonesia (403, 404, 429)
- **Lokasi Berkas:** `resources/views/errors/` (`403.blade.php`, `404.blade.php`, `429.blade.php`)
- **Deskripsi:** Tampilan galat HTTP bawaan framework belum mencerminkan tata bahasa Indonesia baku dan antarmuka resmi SiLab (khususnya untuk halaman akses ditolak 403, 404, dan respon rate limiting 429 saat ada dugaan spam form QR).
- **Rekomendasi:** Buat template Blade kustom dengan gaya visual SiLab yang ramah pengguna.
- **Status:** **VERIFIED: PASS**
  - *Catatan Coder:* Dibuat berkas `errors/403.blade.php`, `errors/404.blade.php`, dan `errors/429.blade.php` lengkap dengan tombol kembali ke dashboard / beranda.
  - *Catatan Tester:* Tampilan terkompilasi optimal dan lolos verifikasi responsivitas layar ponsel.
