# Aturan Kerja Agen SiLab

- **Proyek**: SiLab (Sistem Informasi Pemeliharaan dan Pencatatan Aset Laboratorium Sekolah), Laravel 11, MySQL, Blade, Tailwind CSS, Alpine.js. Antarmuka Bahasa Indonesia.
- **Rujukan Utama**: `docs/PRD.md`. Jangan menambah fitur di luar PRD tanpa persetujuan pengguna.
- **Fokus Bertahap**: Kerjakan satu fase per sesi. Jangan mengubah file di luar cakupan fase yang sedang dikerjakan.
- **Arsitektur & Kode**:
  - Logika bisnis dipusatkan di **Service class** (misal `TiketService`, `AsetService`).
  - Controller tetap tipis (thin controller).
  - Validasi input wajib menggunakan **Form Request**.
  - Otorisasi menggunakan **middleware** (misal `role:admin`) dan **policy**.
  - Gunakan **eager loading** (`with(...)`) untuk mencegah masalah N+1 query.
  - Nama tabel dan kolom memakai bahasa Indonesia sesuai PRD (`snake_case`). Nama kelas, model, dan method mengikuti konvensi Laravel.
- **Pengujian & Data**:
  - Setiap fitur baru harus disertai database seeder atau data contoh agar dapat diverifikasi dan diuji secara langsung.
- **Keamanan**:
  - Jangan menyimpan kredensial di kode. Gunakan variabel lingkungan `.env`.
- **Pelaporan**:
  - Di akhir setiap fase/tugas, tuliskan ringkasan perubahan file yang dibuat/diubah dan langkah uji manual yang jelas.
- **Klarifikasi**:
  - Jika terdapat instruksi yang ambigu atau belum disepakati, ajukan pertanyaan singkat sebelum menulis atau mengubah kode.

