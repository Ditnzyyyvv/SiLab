# SiLab — Sistem Informasi Pemeliharaan & Pencatatan Aset Laboratorium Sekolah

[![Laravel 11](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=flat&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP 8.5](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=flat&logo=php&logoColor=white)](https://php.net)
[![Tailwind CSS v4](https://img.shields.io/badge/Tailwind_CSS-v4-06B6D4?style=flat&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![Tests Passing](https://img.shields.io/badge/PHPUnit%20Tests-80%2F80%20PASS-10B981?style=flat)](file:///c:/Users/Lenovo/Documents/.ukk/tests)

**SiLab** adalah platform web modern untuk manajemen inventaris laboratorium sekolah, penanganan tiket pengaduan kerusakan, pelacakan proses perbaikan teknisi, serta penyusunan laporan audit sarana prasarana resmi.

Dikembangkan dengan penyesuaian khusus **Alur 1 PRD**: Siswa, guru, atau pengguna di laboratorium dapat langsung memindai stiker **QR Code** pada aset fisik menggunakan kamera ponsel pintar untuk mengajukan aduan kerusakan secara cepat **tanpa kewajiban registrasi atau login (Guest Mode)**. Pendaftaran akun member bersifat opsional bagi yang ingin melacak riwayat personal di dashboard "Tiket Saya".

---

## 🌟 Fitur Utama

1. **Alur Lapor Cepat via Scan QR (Guest Tanpa Login — FR-13)**:
   - Kamera HP scan stiker QR $\rightarrow$ Otomatis membuka form publik `/lapor/{kode_aset}`.
   - Mengisi nama, kontak (opsional), gejala kerusakan, dan lampiran foto (maks 3 foto).
   - Tiket terbit dengan nomor unik `TKT-YYYYMMDD-XXXX` dan tampil halaman konfirmasi.
2. **Portal Member / Siswa Terdaftar (FR-02, FR-15, FR-19)**:
   - Dashboard analitis grafik status tiket milik sendiri.
   - Menu "Tiket Saya" untuk memantau timeline penanganan secara transparan.
   - Fitur konfirmasi penyelesaian ("Setuju & Konfirmasi") atau membuka kembali tiket ("Buka Kembali") jika hasil reparasi belum tuntas.
3. **Master Data & Cetak Stiker QR Code (FR-05 s.d. FR-10)**:
   - Manajemen Laboratorium, Kategori Aset, dan Data Aset Komprehensif.
   - Otomatisasi penomoran kode aset (`AST-{LAB}-{NOMOR}`).
   - Cetak label stiker QR satuan maupun cetak massal siap tempel di meja/perangkat lab.
4. **Validasi & Disposisi Admin (FR-16, FR-17, FR-19)**:
   - Validasi kelayakan aduan (status `Pending` $\rightarrow$ `Diproses` atau `Ditolak`).
   - Penugasan teknisi dan penetapan skala prioritas (`Rendah`, `Sedang`, `Tinggi`).
   - Otomatisasi sinkronisasi kondisi aset menjadi `Dalam Perbaikan`.
   - Konfirmasi penutupan akhir untuk tiket pengaduan dari pelapor tamu (Guest).
5. **Pengerjaan & Log Kronologis Teknisi (FR-20 s.d. FR-24)**:
   - Halaman khusus daftar tugas teknisi yang login.
   - Pembaruan status berkala (`Diproses`, `Menunggu Komponen/Sparepart`, `Selesai`, `Rusak Total`).
   - Form dinamis pencatatan komponen sparepart yang dibutuhkan dan unggah foto bukti perbaikan.
   - Tiket selesai otomatis mengembalikan kondisi aset menjadi `Baik`; Rusak Total mengubah kondisi aset menjadi `Tidak Layak Pakai`.
6. **Dashboard Interaktif & Notifikasi Dalam Aplikasi (FR-25 s.d. FR-28)**:
   - 3 Dashboard khusus per role didukung visualisasi grafik **Chart.js** (Line Chart bulanan, Doughnut Chart kondisi aset & tiket, Bar Chart produktivitas teknisi).
   - Widget pantauan beban kerja teknisi dan peringatan dini tiket prioritas tinggi.
   - Lonceng notifikasi real-time di navigasi atas dengan badge unread count.
7. **Laporan & Ekspor Dokumen Resmi (FR-29, FR-30)**:
   - Pratinjau tabel interaktif Laporan Aset dan Laporan Perbaikan dengan multi-filter.
   - Ekspor berkas **PDF A4 Landscape** bertenaga DomPDF ber-KOP surat resmi sekolah/dinas dan tanda tangan pejabat berwenang.
   - Ekspor berkas **Excel / Spreadsheet (CSV UTF-8 BOM)** yang langsung terbuka rapi per kolom di Microsoft Excel.

---

## 🛠️ Tech Stack & Persyaratan Sistem

- **Backend Framework:** Laravel 11.x (PHP 8.2 atau lebih tinggi)
- **Database Engine:** MySQL 8.0+ / MariaDB 10.4+
- **Frontend / UI:** Blade Templates, Tailwind CSS v4, Alpine.js, Chart.js v4
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
# Salin konfigurasi lingkungan jika belum ada
copy .env.example .env

# Buat APP_KEY Laravel
php artisan key:generate
```

Sesuaikan konfigurasi database pada `.env`:

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
```

### 3. Tautan Penyimpanan Berkas & Database Seeding

Jalankan migrasi tabel database dan masukkan data simulasi realistis:

```bash
# Buat symlink public storage untuk foto aset & bukti penanganan
php artisan storage:link

# Jalankan migrasi dan isi database dengan data demo lengkap
php artisan migrate:fresh --seed
```

### 4. Kompilasi Aset & Jalankan Server Lokal

Jalankan perintah berikut:

```bash
# Kompilasi frontend Tailwind & JS
npm run build

# Jalankan server pengembangan Laravel
php artisan serve
```

Aplikasi SiLab dapat diakses di peramban (browser) melalui:  
👉 **`http://127.0.0.1:8000`**

---

## 👥 Akun Uji Coba Demo (Default Credentials)

Seluruh akun demo memiliki password yang sama: **`password`**

| Role | Nama Pengguna | Alamat Email | Akses Utama |
| :--- | :--- | :--- | :--- |
| **Admin** | Dr. Ir. H. Bambang Hartono, M.T. | `admin@silab.sch.id` | Dashboard Utama, Master Data Lab/Aset/Kategori, Validasi & Disposisi, Pusat Laporan PDF/Excel |
| **Teknisi 1** | Faisal Rahman, S.Kom. | `teknisi@silab.sch.id` | Dashboard Teknisi, Daftar Tugas Saya, Form Update Progres & Sparepart |
| **Teknisi 2** | Rian Hidayat, A.Md. | `teknisi2@silab.sch.id` | Pengujian perbandingan beban kerja teknisi di grafik admin |
| **Member 1** | Ahmad Fauzi (Siswa RPL) | `member@silab.sch.id` | Dashboard Member, Buat Tiket Internal, "Tiket Saya", Konfirmasi Hasil Reparasi |
| **Member 2** | Siti Aisyah (Siswa TKJ) | `siswa2@silab.sch.id` | Pengujian pelaporan multi-siswa dan notifikasi timeline |

---

## 📱 Panduan Simulasi Alur QR Code Tanpa Login

Untuk menguji fitur unggulan pemindaian stiker QR tanpa memerlukan perangkat kamera fisik:

1. Pastikan server lokal aktif (`php artisan serve`).
2. Buka URL formulir publik aset contoh berikut di browser atau browser seluler:
   👉 **`http://127.0.0.1:8000/lapor/AST-RPL-0001`**
3. Anda langsung diarahkan ke halaman **Formulir Lapor Cepat Kerusakan** tanpa diminta login.
4. Isi form:
   - Nama Pelapor: `Contoh Siswa Lab`
   - Kontak: `081234567890` (opsional)
   - Judul & Kronologi kerusakan.
   - Lampirkan foto bukti (opsional, maks 3 foto JPG/PNG).
5. Klik **"Kirim Laporan Pengaduan"**.
6. Sistem menerbitkan nomor tiket unik (contoh: `TKT-20261113-XXXX`) dan menampilkan halaman sukses konfirmasi tamu.
7. Masuk sebagai **Admin** (`admin@silab.sch.id`) $\rightarrow$ Buka menu **Validasi & Tiket** $\rightarrow$ Tiket tamu tersebut akan tampil berstatus `Pending` siap divalidasi dan didisposisikan ke teknisi.

---

## 🧪 Eksekusi Pengujian Otomatis (Automated Tests)

Aplikasi dilengkapi dengan rangkaian Feature Test otomatis menyeluruh (80 test cases, 348 assertions) yang mencakup seluruh Functional Requirements (FR-01 s.d. FR-30):

```bash
# Menjalankan seluruh test suite otomatis
php artisan test
```

### Rincian Modul Pengujian:
- `tests/Feature/RoleAccessTest.php`: Fondasi autentikasi, isolasi RBAC 3 role, proteksi URL silang.
- `tests/Feature/MasterDataTest.php`: CRUD Laboratorium, Kategori, Aset, penomoran kode aset, dan cetak QR Code.
- `tests/Feature/TiketPengaduanTest.php`: Alur lapor cepat via QR tanpa login (Guest) vs Member login.
- `tests/Feature/TiketPengerjaanTest.php`: Validasi admin, disposisi, transisi status teknisi, sparepart, reopen, dan konfirmasi.
- `tests/Feature/DashboardDanNotifikasiTest.php`: Metrik Chart.js 3 role dan pengiriman notifikasi dalam aplikasi.
- `tests/Feature/LaporanEksporTest.php`: Pratinjau filter data, ekspor berkas PDF resmi ber-KOP, dan streaming file Excel (CSV UTF-8 BOM).

---

## 📄 Lisensi & Hak Cipta

Proyek ini dibangun untuk kebutuhan Uji Kompetensi Keahlian (UKK) Sistem Informasi Laboratorium Sekolah.  
Hak Cipta &copy; 2026 SiLab. Lisensi terbuka di bawah [MIT License](LICENSE).
