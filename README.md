# SiLab — Sistem Informasi Pemeliharaan & Pencatatan Aset Laboratorium Sekolah

[![Laravel 13](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=flat&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP 8.3+](https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=flat&logo=php&logoColor=white)](https://php.net)
[![Tailwind CSS 3.4](https://img.shields.io/badge/Tailwind_CSS-3.4-06B6D4?style=flat&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![Tests Passing](https://img.shields.io/badge/PHPUnit%20Tests-101%2F101%20PASS-10B981?style=flat)](file:///c:/Users/Lenovo/Documents/.ukk/tests)

**SiLab** adalah platform web modern untuk manajemen inventaris laboratorium sekolah, penanganan tiket pengaduan kerusakan, pelacakan proses perbaikan teknisi, serta penyusunan laporan audit sarana prasarana resmi.

Dikembangkan dengan penyesuaian khusus **Alur 1 PRD**: Siswa, guru, atau pengguna di laboratorium dapat langsung mengajukan pengaduan kerusakan dari **Formulir Beranda** ataupun memindai stiker **QR Code** pada aset fisik menggunakan kamera ponsel pintar **tanpa kewajiban registrasi atau login (Guest Mode)**. Pendaftaran akun member bersifat opsional bagi yang ingin melacak riwayat personal di dashboard "Tiket Saya".

---

## 🌟 Fitur Utama

1. **Alur Lapor Cepat Tanpa Login (Beranda & Scan QR — FR-13)**:
   - Pengunjung dapat langsung mengisi formulir pengaduan kerusakan di halaman utama (`/`) dengan pilihan Lab & Aset terfilter, atau scan stiker QR fisik menuju `/lapor/{kode_aset}`.
   - Mendukung unggah foto bukti kerusakan (maksimal 3 foto JPG/PNG, maks 2 MB/foto).
   - Tiket terbit otomatis dengan nomor unik `TKT-YYYYMMDD-XXXX` dan halaman konfirmasi sukses yang terproteksi berbasis session/pemilik/Admin.
2. **Portal Member / Siswa Terdaftar (FR-02, FR-15, FR-19)**:
   - Dashboard analitis grafik status tiket milik sendiri.
   - Menu "Tiket Saya" untuk memantau timeline penanganan secara transparan.
   - Fitur konfirmasi penyelesaian ("Setuju & Konfirmasi") atau membuka kembali tiket ("Buka Kembali") sebelum dikonfirmasi tuntas.
3. **Master Data, Manajemen Pengguna & Cetak Stiker QR Code (FR-05 s.d. FR-10)**:
   - Manajemen Laboratorium, Kategori Aset (dengan guard penghapusan termasuk arsip soft-delete), Data Aset, dan **Manajemen Pengguna (CRUD Admin/Teknisi/Member dengan guard keamanan)**.
   - Otomatisasi penomoran kode aset (`AST-{LAB}-{NOMOR}`).
   - Cetak label stiker QR satuan maupun cetak massal siap tempel di meja/perangkat lab.
4. **Validasi & Disposisi Admin (FR-16, FR-17, FR-19)**:
   - Validasi kelayakan aduan (`Pending` → `Diproses` atau `Ditolak`).
   - Penugasan teknisi dan penetapan skala prioritas (`Rendah`, `Sedang`, `Tinggi`).
   - Otomatisasi sinkronisasi kondisi aset menjadi `Dalam Perbaikan` dengan proteksi multi-tiket (*concurrency guard*).
   - Konfirmasi penutupan akhir untuk tiket pengaduan dari pelapor tamu (Guest).
5. **Pengerjaan & Log Kronologis Teknisi (FR-20 s.d. FR-24)**:
   - Halaman khusus daftar tugas teknisi yang login.
   - Pembaruan status berkala (`Diproses`, `Menunggu Komponen/Sparepart`, `Selesai`, `Rusak Total`).
   - Form dinamis pencatatan komponen sparepart yang dibutuhkan dan unggah foto bukti perbaikan.
   - Tiket selesai otomatis mengembalikan kondisi aset menjadi `Baik` (jika tidak ada tiket aktif lain pada aset tersebut); `Rusak Total` mengubah kondisi aset menjadi `Tidak Layak Pakai`.
6. **Dashboard Interaktif & Notifikasi Dalam Aplikasi (FR-25 s.d. FR-28)**:
   - 3 Dashboard khusus per role didukung visualisasi grafik **Chart.js** (Line Chart bulanan, Doughnut Chart kondisi aset & tiket, Bar Chart produktivitas teknisi).
   - Widget pantauan beban kerja teknisi dan peringatan dini tiket prioritas tinggi.
   - Lonceng notifikasi real-time di navigasi atas dengan badge unread count.
7. **Laporan & Ekspor Dokumen Resmi (FR-29, FR-30)**:
   - Pratinjau tabel interaktif Laporan Aset dan Laporan Perbaikan dengan multi-filter.
   - Ekspor berkas **PDF A4 Landscape** bertenaga DomPDF ber-KOP surat resmi dan logo SiLab (identitas sekolah & pejabat penanda tangan dapat diubah lewat [`config/silab.php`](file:///c:/Users/Lenovo/Documents/.ukk/config/silab.php) / `.env`).
   - Ekspor berkas **Spreadsheet / Excel (CSV UTF-8 BOM)** yang ringan dan langsung terbuka rapi per kolom di Microsoft Excel tanpa memerlukan ekstensi `ext-zip`.

---

## 🛠️ Tech Stack & Persyaratan Sistem

- **Backend Framework:** Laravel 13.x (PHP 8.3 atau lebih tinggi)
- **Database Engine:** MySQL 8.0+ / MariaDB 10.4+ (SQLite `:memory:` untuk test suite otomatis)
- **Frontend / UI:** Blade Templates, Tailwind CSS 3.4, Alpine.js 3, Chart.js 4, Vite 8
- **Paket Tambahan:**
  - `simplesoftwareio/simple-qrcode`: Generasi SVG QR Code per aset.
  - `barryvdh/laravel-dompdf`: Cetak dokumen PDF resmi.
- **Node.js & NPM:** Node.js v18+ dan NPM v9+

---

## 🚀 Panduan Instalasi & Menjalankan Proyek

### 1. Persiapan Repositori & Dependensi

Buka terminal di direktori proyek:

```bash
# Instal dependensi backend PHP via Composer
composer install

# Instal dependensi frontend JavaScript & CSS
npm install
```

### 2. Konfigurasi Lingkungan (`.env`)

Salin file contoh konfigurasi dan buat application key:

```bash
copy .env.example .env
php artisan key:generate
```

Sesuaikan konfigurasi database dan identitas sekolah (KOP Surat PDF) pada `.env`:

```env
APP_NAME=SiLab
APP_ENV=local
APP_DEBUG=true
APP_TIMEZONE=Asia/Jakarta
APP_LOCALE=id

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=silab
DB_USERNAME=root
DB_PASSWORD=

# Kustomisasi KOP Surat PDF Resmi
SILAB_INSTANSI="DINAS PENDIDIKAN — PEMERINTAH DAERAH"
SILAB_NAMA_SEKOLAH="SMK NEGERI 1 — LABORATORIUM KOMPUTER TERPADU"
SILAB_ALAMAT="Jl. Pendidikan Terpadu No. 1 | Telp: (021) 7890123 | Email: silab@smkn1.sch.id"
SILAB_KOTA="Indonesia"
SILAB_KEPALA_LAB_NAMA="Dr. Ir. H. Bambang Hartono, M.T."
SILAB_KEPALA_LAB_NIP="NIP. 19780512 200312 1 002"
SILAB_KOORDINATOR_TEKNISI_NAMA="Faisal Rahman, S.Kom."
SILAB_KOORDINATOR_TEKNISI_NIP="NIP. 19850314 201101 1 004"
```

### 3. Tautan Penyimpanan Berkas & Database Seeding

Pastikan layanan **MySQL** (misalnya via **Laragon → Start All**) sudah aktif terlebih dahulu, lalu jalankan:

```bash
# Buat symlink public storage untuk foto aset & bukti penanganan
php artisan storage:link

# Jalankan migrasi dan isi database dengan data demo lengkap
php artisan migrate:fresh --seed
```

### 4. Kompilasi Aset & Jalankan Server Lokal

```bash
# Kompilasi frontend Tailwind & JS
npm run build

# Jalankan server pengembangan Laravel
php artisan serve
```

Aplikasi SiLab dapat diakses melalui:  
👉 **`http://127.0.0.1:8000`**

---

## 👥 Akun Uji Coba Demo (Default Credentials)

Seluruh akun demo memiliki kata sandi yang sama: **`password`**

| Role | Nama Pengguna | Alamat Email | Akses Utama |
| :--- | :--- | :--- | :--- |
| **Admin** | Dr. Ir. H. Bambang Hartono, M.T. | `admin@silab.sch.id` | Dashboard Utama, Master Data Lab/Aset/Kategori, Manajemen Pengguna, Validasi & Disposisi, Pusat Laporan PDF/Excel |
| **Teknisi 1** | Faisal Rahman, S.Kom. | `teknisi@silab.sch.id` | Dashboard Teknisi, Daftar Tugas Saya, Form Update Progres & Sparepart |
| **Teknisi 2** | Rian Hidayat, A.Md. | `teknisi2@silab.sch.id` | Pengujian perbandingan beban kerja teknisi di grafik admin |
| **Member 1** | Ahmad Fauzi (Siswa RPL) | `member@silab.sch.id` | Dashboard Member, Buat Tiket Internal, "Tiket Saya", Konfirmasi Hasil Reparasi |
| **Member 2** | Siti Aisyah (Siswa TKJ) | `siswa2@silab.sch.id` | Pengujian pelaporan multi-siswa dan notifikasi timeline |

---

## 📱 Panduan Simulasi Alur Pengaduan Tanpa Login (Beranda & QR Code)

1. Pastikan server lokal (`php artisan serve`) dan MySQL aktif.
2. **Via Beranda:** Buka `http://127.0.0.1:8000`, pilih Laboratorium & Aset yang rusak, isi keluhan, lalu klik **Kirim Laporan Pengaduan**.
3. **Via Simulasi Scan QR:** Buka langsung `http://127.0.0.1:8000/lapor/AST-RPL-0001` tanpa login — perangkat langsung terpilih otomatis sesuai stiker QR.
4. Sistem menerbitkan nomor tiket unik (`TKT-YYYYMMDD-XXXX`) dan menampilkan halaman konfirmasi sukses.
5. Masuk sebagai **Admin** (`admin@silab.sch.id`) → buka menu **Validasi & Tiket** → validasi dan disposisikan tiket ke teknisi.

---

## 🧪 Eksekusi Pengujian Otomatis (Automated Tests)

Aplikasi dilengkapi rangkaian Feature Test otomatis menyeluruh yang mencakup seluruh Functional Requirements (`FR-01` s.d. `FR-30`), manajemen pengguna, serta pengujian keamanan & *edge-cases*:

```bash
php artisan test
```

### Rincian Modul Pengujian:
- `tests/Feature/RoleAccessTest.php`: Fondasi autentikasi, isolasi RBAC 3 role, proteksi URL silang.
- `tests/Feature/MasterDataTest.php`: CRUD Laboratorium, Kategori, Aset, penomoran kode aset, dan cetak QR Code.
- `tests/Feature/UserManagementTest.php`: CRUD Pengguna oleh Admin, filter peran, dan guard anti-hapus diri sendiri/Admin terakhir/Teknisi bertugas aktif.
- `tests/Feature/TiketPengaduanTest.php`: Alur lapor cepat dari Beranda & Scan QR tanpa login (Guest) vs Member login.
- `tests/Feature/TiketPengerjaanTest.php`: Validasi admin, disposisi, transisi status teknisi, sparepart, reopen, dan konfirmasi.
- `tests/Feature/DashboardDanNotifikasiTest.php`: Metrik Chart.js 3 role dan pengiriman notifikasi dalam aplikasi.
- `tests/Feature/LaporanEksporTest.php`: Pratinjau filter data, ekspor berkas PDF resmi ber-KOP & logo, dan streaming file CSV/Excel UTF-8 BOM.
- `tests/Feature/SecurityDanEdgeCaseTest.php`: Proteksi IDOR, concurrency multi-tiket, lock konfirmasi, deletion guard aset & kategori, proteksi Admin terakhir di profil, privasi halaman sukses, dan validasi upload berkas.

---

## 📄 Lisensi & Hak Cipta

Proyek ini dibangun untuk kebutuhan Uji Kompetensi Keahlian (UKK) Sistem Informasi Laboratorium Sekolah.  
Hak Cipta &copy; 2026 SiLab. Lisensi terbuka di bawah [MIT License](LICENSE).
