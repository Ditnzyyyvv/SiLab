# PRD SiLab — Sistem Informasi Pemeliharaan dan Pencatatan Aset Laboratorium Sekolah

Versi 1.1 · 11 November 2026 · Target eksekusi: Antigravity IDE (agen Gemini Flash)

## 1. Ringkasan Produk

SiLab adalah aplikasi web untuk mencatat seluruh aset laboratorium sekolah (PC, mouse, keyboard, router/WiFi, switch, proyektor, dan lainnya) serta mengelola proses pelaporan kerusakan sampai perbaikan selesai. Saat ini pencatatan aset dan laporan kerusakan umumnya manual (buku/chat), sehingga sulit dilacak, sering terlambat ditangani, dan tidak punya riwayat.

SiLab menyatukan dua hal dalam satu sistem:

- **Pencatatan aset**: inventaris lengkap per lab, kondisi, QR code per aset, dan riwayat.
- **Pemeliharaan**: tiket pengaduan kerusakan (bisa lapor cepat via scan QR tanpa login maupun lewat akun login), disposisi ke teknisi, log perbaikan dengan foto bukti, dan jadwal pemeliharaan berkala.

## 2. Tujuan dan Indikator Keberhasilan

**Tujuan**

1. Semua aset lab tercatat digital dengan kode unik, label QR Code, dan kondisi terkini.
2. Setiap kerusakan punya tiket yang tercatat rapi; pelapor terdaftar dapat memantau tiket dari awal sampai selesai, sementara pelapor non-login dapat melapor secara instan via QR code fisik.
3. Admin punya gambaran real-time kondisi aset dan beban kerja teknisi.
4. Riwayat perbaikan per aset tersimpan sebagai dasar keputusan (perbaiki, ganti, atau hapus).

**Indikator keberhasilan**

- 100% aset lab terdata dalam sistem dan memiliki QR code label siap cetak.
- Setiap tiket punya timeline status lengkap dengan pencatat dan waktu.
- Admin dapat mengekspor laporan aset dan laporan perbaikan per periode dalam kurang dari 1 menit.
- Pelapor terdaftar tidak perlu bertanya manual "sudah sampai mana?" karena status terlihat di aplikasi; pelapor tamu di lab dapat melapor dalam waktu < 1 menit cukup dengan scan QR code aset.

## 3. Pengguna dan Role

**Admin** (kepala lab / pengelola aset)

- Mengelola master data: lab, kategori, aset (termasuk cetak label QR Code), pengguna.
- Memvalidasi pengaduan masuk (baik dari member terdaftar maupun tamu via scan QR), menetapkan prioritas, dan mendisposisikan ke teknisi.
- Memonitor dashboard, mencetak laporan, mengonfirmasi tiket tamu yang selesai, mengelola jadwal pemeliharaan.

**Teknisi**

- Melihat tugas perbaikan yang ditugaskan kepadanya.
- Memperbarui progres, menulis log penanganan, mengunggah foto bukti.

**Member & Pelapor** (siswa atau guru)

- **Pelapor Tamu (Guest / Tanpa Login)**:
  - Memindai QR Code fisik pada aset lab untuk membuka formulir pelaporan cepat tanpa perlu registrasi atau login.
  - Mengisi nama pelapor, kontak (opsional), judul, deskripsi, dan foto kerusakan.
  - *Catatan hak akses*: Pelapor tamu **tidak dapat** melihat rekapitulasi riwayat laporan atau dashboard.
- **Member Terdaftar (Login)**:
  - Registrasi dan login bersifat **opsional** bagi siswa/guru.
  - Jika memiliki akun dan login, member dapat:
    - Membuat tiket pengaduan (baik dari menu dashboard maupun scan QR).
    - Melihat rekapitulasi seluruh laporan miliknya di menu "Tiket Saya".
    - Memantau timeline progres penanganan tiket secara real-time.
    - Menerima notifikasi perkembangan status.
    - Mengonfirmasi hasil perbaikan atau mengajukan buka kembali tiket.

## 4. Ruang Lingkup

**Masuk lingkup (v1.0)**

- Autentikasi dan otorisasi 3 role (Admin, Teknisi, Member).
- Registrasi mandiri untuk Member (opsional).
- Generator QR Code unik per aset dan fitur cetak label QR.
- Formulir publik lapor cepat via scan QR Code aset tanpa login (Guest Report).
- CRUD master data (lab, kategori aset, aset, pengguna).
- Tiket pengaduan, validasi, disposisi, update progres, konfirmasi selesai.
- Log penanganan dengan foto bukti.
- Dashboard per role (Member hanya melihat tiket miliknya sendiri).
- Riwayat per aset.
- Laporan dan ekspor (PDF dan Excel).
- Notifikasi di dalam aplikasi.

**Opsional (v1.1)**

- Jadwal pemeliharaan berkala (preventif) dengan pengingat.
- Notifikasi email atau WhatsApp.

**Di luar lingkup**

- Pembelian dan pengadaan barang, penyusutan nilai aset (akuntansi), aplikasi mobile native, integrasi sistem sekolah lain.

## 5. Alur Utama

**Alur 1 — Pengaduan kerusakan**

1. Pelaporan kerusakan dapat dilakukan melalui 2 cara:
   - **Opsi A (Scan QR Tanpa Login / Guest)**: Siswa atau guru memindai stiker QR Code yang tertempel pada aset fisik di lab menggunakan kamera HP/pemindai QR. Sistem langsung membuka halaman formulir lapor cepat publik (`/lapor/{kode_aset}`) tanpa mewajibkan login. Pelapor mengisi nama, kontak (opsional), judul keluhan, deskripsi, dan foto bukti kerusakan, lalu mengirim tiket. Status awal: **Pending**. Pelapor menerima nomor tiket dan pesan sukses (tidak dapat mengakses riwayat/laporan karena tidak login).
   - **Opsi B (Member Login)**: Member login ke sistem, memilih aset dari daftar atau memindai QR Code, mengisi data tiket, lalu mengirim laporan. Tiket otomatis terhubung ke akun member (`pelapor_id`). Status awal: **Pending**. Member dapat memantau progres tiket di menu "Tiket Saya".
2. Admin menerima tiket, memvalidasi. Jika valid, admin mengatur prioritas dan menugaskan teknisi (disposisi). Jika tidak valid, tiket ditolak dengan alasan.
3. Teknisi menerima tugas, mengubah status ke **Diproses**, dan mencatat log penanganan.
4. Jika butuh komponen, teknisi mengubah status ke **Menunggu Komponen/Sparepart** dengan catatan komponen apa. Setelah komponen tersedia, kembali ke **Diproses**.
5. Teknisi menyelesaikan: status **Selesai** (aset kembali berkondisi Baik) atau **Rusak Total** (aset ditandai tidak layak pakai).
6. Konfirmasi hasil perbaikan:
   - Jika tiket berasal dari **Member terdaftar (login)**: Member memeriksa dan mengonfirmasi hasil (Setuju) atau membuka kembali tiket dengan alasan wajib jika belum tuntas.
   - Jika tiket berasal dari **Pelapor tamu (tanpa login)**: Admin melakukan verifikasi akhir dan menutup konfirmasi tiket.

**Alur 2 — Pencatatan aset**

Admin menambah aset baru (kode otomatis, kategori, lab, spesifikasi, tanggal perolehan, foto), mengubah data, memindahkan aset antar lab, atau menghapus aset dengan alasan. Setiap perubahan kondisi tercatat di riwayat.

**Alur 3 — Pemantauan dan pelaporan**

Admin membuka dashboard, memfilter data per lab, kategori, atau periode, lalu mengekspor laporan.

## 6. Kebutuhan Fungsional

### 6.1 Autentikasi dan Akses

- **FR-01** Login dan logout dengan email dan password.
- **FR-02** Member dapat mendaftar sendiri (pendaftaran bersifat opsional, hanya jika ingin memiliki akun untuk memantau tiket). Akun Teknisi dan Admin hanya dibuat oleh Admin.
- **FR-03** Akses dibatasi middleware per role. Pengguna tidak dapat membuka halaman role lain.
- **FR-04** Pengguna dapat mengubah profil dan password.

### 6.2 Master Data (Admin)

- **FR-05** CRUD Lab (nama, lokasi, penanggung jawab).
- **FR-06** CRUD Kategori Aset (misalnya Komputer, Periferal, Jaringan, Proyektor).
- **FR-07** CRUD Aset: kode aset unik otomatis (format `AST-{KODE_LAB}-{URUT}`), nama, kategori, lab, merek, spesifikasi, tanggal perolehan, kondisi, foto.
- **FR-07b** Setiap aset otomatis memiliki QR Code unik (mengarah ke URL lapor cepat `https://silab.../lapor/{kode_aset}`) yang dapat dipratinjau dan dicetak (label stiker satuan maupun massal) oleh Admin.
- **FR-08** Kondisi aset: Baik, Rusak Ringan, Rusak Berat, Dalam Perbaikan, Tidak Layak Pakai.
- **FR-09** Pencarian, filter (lab, kategori, kondisi), dan pagination pada daftar aset.
- **FR-10** Halaman detail aset menampilkan seluruh riwayat tiket dan perbaikan aset tersebut serta pratinjau QR Code aset.
- **FR-11** CRUD Pengguna dan pengaturan role.
- **FR-12** Hapus data memakai soft delete.

### 6.3 Tiket Pengaduan

- **FR-13** Pembuatan tiket pengaduan dapat dilakukan melalui dua cara:
  - *Tanpa login via QR Code*: membuka halaman publik lapor cepat `/lapor/{kode_aset}`, mengisi nama pelapor (wajib), kontak/no. WA (opsional), judul, deskripsi, dan foto kerusakan (maksimal 3, format jpg/png, maksimal 2 MB per foto). Sistem menyimpan dengan `pelapor_id = null`.
  - *Dengan login Member*: memilih aset dari daftar atau scan QR, judul, deskripsi, foto kerusakan (maksimal 3, format jpg/png, maksimal 2 MB). Sistem menautkan tiket ke akun member (`pelapor_id`).
- **FR-14** Nomor tiket otomatis, format `TKT-YYYYMMDD-XXXX`.
- **FR-15** Member yang login dapat melihat rekapitulasi daftar dan detail tiket miliknya beserta timeline status lengkap di menu "Tiket Saya". Pengguna tanpa login tidak memiliki akses ke halaman riwayat ini.
- **FR-16** Admin melihat semua tiket dengan filter status, prioritas, lab, dan tanggal (baik tiket dari member login maupun pelapor tamu via QR).
- **FR-17** Admin memvalidasi tiket: terima (isi prioritas Rendah/Sedang/Tinggi) atau tolak (alasan wajib).
- **FR-18** Admin mendisposisikan tiket ke satu teknisi, dan dapat memindahkan penugasan.
- **FR-19** Konfirmasi hasil perbaikan:
  - Tiket dari Member login: Member dapat mengonfirmasi hasil (Setuju) atau membuka kembali tiket (alasan wajib).
  - Tiket dari pelapor tamu (tanpa login): Admin melakukan konfirmasi akhir penutupan tiket setelah teknisi selesai.

### 6.4 Pengerjaan Perbaikan (Teknisi)

- **FR-20** Teknisi hanya melihat tiket yang ditugaskan kepadanya.
- **FR-21** Teknisi mengubah status sesuai alur yang diizinkan (lihat bagian 9).
- **FR-22** Setiap perubahan status mewajibkan catatan penanganan dan boleh menyertakan foto bukti.
- **FR-23** Saat status Menunggu Komponen/Sparepart, teknisi wajib mengisi nama komponen yang dibutuhkan.
- **FR-24** Saat status Selesai, kondisi aset otomatis menjadi Baik. Saat Rusak Total, kondisi aset otomatis menjadi Tidak Layak Pakai.

### 6.5 Dashboard dan Notifikasi

- **FR-25** Dashboard Admin: total aset, jumlah per kondisi, tiket per status, tiket prioritas tinggi yang belum selesai, beban kerja per teknisi, grafik tiket per bulan.
- **FR-26** Dashboard Teknisi: tugas aktif, tugas menunggu sparepart, tugas selesai bulan ini.
- **FR-27** Dashboard Member (khusus member login): rekapitulasi tiket saya per status.
- **FR-28** Notifikasi dalam aplikasi untuk: tiket baru (Admin), disposisi baru (Teknisi), perubahan status (Member terdaftar), tiket diminta dibuka kembali (Teknisi dan Admin).

### 6.5b Laporan

- **FR-29** Laporan Aset (filter lab, kategori, kondisi) ekspor PDF dan Excel.
- **FR-30** Laporan Perbaikan (filter periode, lab, teknisi, status) ekspor PDF dan Excel.
- **FR-31** (v1.1) Jadwal pemeliharaan berkala per aset atau kategori, dengan pengingat di dashboard saat jatuh tempo.

## 7. Kebutuhan Non-Fungsional

- **Keamanan**: password di-hash, proteksi CSRF, validasi input di sisi server, otorisasi lewat middleware dan policy, validasi tipe dan ukuran file unggahan, file disimpan di storage yang tidak dapat dieksekusi. Rate-limiting pada endpoint publik lapor cepat via QR untuk mencegah spam.
- **Kinerja**: halaman utama termuat di bawah 3 detik pada jaringan sekolah. Gunakan eager loading untuk menghindari N+1 query.
- **Responsif**: nyaman dipakai di HP (Member/tamu melapor dari HP dengan membuka kamera/QR) dan desktop.
- **Keterlacakan**: setiap perubahan status tiket dan kondisi aset tercatat (siapa, kapan, apa). Untuk tiket tamu, tercatat nama pelapor tamu.
- **Bahasa**: seluruh antarmuka dalam Bahasa Indonesia, format tanggal `dd-mm-yyyy`, zona waktu Asia/Jakarta.
- **Maintainability**: kode mengikuti konvensi Laravel, logika bisnis di Service class, bukan di Controller.

## 8. Tech Stack

- **Backend**: Laravel 11 (PHP 8.2+)
- **Database**: MySQL 8
- **Frontend**: Blade + Tailwind CSS + Alpine.js (tanpa SPA agar sederhana dan mudah dirawat)
- **Autentikasi**: Laravel Breeze (Blade)
- **Role**: kolom `role` pada tabel users (enum `admin`, `teknisi`, `member`) dengan middleware kustom `role:admin`
- **QR Code Generator**: simplesoftwareio/simple-qrcode (menghasilkan SVG/PNG QR Code per aset)
- **Ekspor**: barryvdh/laravel-dompdf dan maatwebsite/excel
- **Grafik dashboard**: Chart.js
- **Penyimpanan foto**: Laravel Storage (disk `public`)
- **Lingkungan lokal**: Laragon atau XAMPP, Composer, Node.js

## 9. Model Data

**users**: id, nama, email, password, role (admin/teknisi/member), no\_hp, timestamps.

**labs**: id, kode, nama, lokasi, penanggung\_jawab\_id (users), timestamps, soft delete.

**kategoris**: id, nama, timestamps.

**asets**: id, kode\_aset (unik), nama, kategori\_id, lab\_id, merek, spesifikasi, tanggal\_perolehan, kondisi, foto, timestamps, soft delete.

**tikets**: id, nomor\_tiket (unik), aset\_id, pelapor\_id (nullable, foreign key ke users), nama\_pelapor, kontak\_pelapor (nullable), judul, deskripsi, prioritas (nullable), status, teknisi\_id (nullable), alasan\_penolakan (nullable), tanggal\_disposisi, tanggal\_selesai, dikonfirmasi\_at, timestamps.

**tiket\_fotos**: id, tiket\_id, path.

**log\_penanganans**: id, tiket\_id, user\_id, status\_dari, status\_ke, catatan, komponen\_dibutuhkan (nullable), foto\_bukti (nullable), timestamps.

**notifikasis**: id, user\_id, judul, pesan, tiket\_id (nullable), dibaca\_at, timestamps.

**jadwal\_pemeliharaans** (v1.1): id, aset\_id atau kategori\_id, interval\_hari, terakhir\_dilakukan, berikutnya, catatan.

**Relasi utama**

- Lab memiliki banyak Aset. Kategori memiliki banyak Aset.
- Aset memiliki banyak Tiket. Tiket milik satu Aset, satu Pelapor (User, opsional/nullable jika tamu tanpa login), dan satu Teknisi (User, opsional).
- Tiket memiliki banyak TiketFoto dan banyak LogPenanganan.

**Aturan transisi status tiket**

- Pending → Diproses (setelah admin menyetujui dan mendisposisikan, lalu teknisi mulai) atau Ditolak (oleh Admin).
- Diproses → Menunggu Komponen/Sparepart, Selesai, atau Rusak Total.
- Menunggu Komponen/Sparepart → Diproses.
- Selesai → dibuka kembali oleh Member menjadi Diproses.
- Rusak Total dan Ditolak adalah status akhir.
- Transisi di luar aturan ini harus ditolak oleh sistem.

## 10. Daftar Halaman

**Publik**:
- Login dan Registrasi (opsional bagi pelapor).
- Halaman Lapor Cepat Aset via QR Code (`/lapor/{kode_aset}`): menampilkan info ringkas aset (nama lab, nama aset, kode) dan form pengaduan tanpa login.
- Halaman Sukses Pengiriman Tiket (menampilkan nomor tiket dan konfirmasi bahwa laporan telah diterima).

**Admin**: dashboard, lab, kategori, aset (daftar, tambah, ubah, detail, cetak label/QR Code), pengguna, tiket (daftar, detail, validasi, disposisi, konfirmasi tiket tamu), laporan, notifikasi, profil.

**Teknisi**: dashboard, tugas saya (daftar, detail, form update progres), notifikasi, profil.

**Member (Wajib Login)**: dashboard, buat tiket, tiket saya (daftar riwayat laporan, detail dengan timeline status, konfirmasi perbaikan), notifikasi, profil.

## 11. Panduan UI/UX

- Layout dengan sidebar (desktop) yang berubah menjadi menu hamburger di HP.
- Halaman publik lapor cepat via QR dibuat ringkas, cepat, dan mobile-first (ramah layar sentuh HP).
- Warna status konsisten: Pending abu-abu, Diproses biru, Menunggu Sparepart kuning, Selesai hijau, Rusak Total merah, Ditolak merah muda.
- Tampilan detail tiket memakai timeline vertikal berisi log penanganan dan foto bukti.
- Form pendek dengan validasi inline dan pesan error berbahasa Indonesia.
- Konfirmasi (modal) sebelum aksi destruktif seperti hapus.
- Tabel memiliki pencarian, filter, dan pagination.

## 12. Roadmap Implementasi untuk Antigravity (Gemini Flash)

Gemini Flash bekerja paling baik dengan tugas kecil, spesifik, dan satu fokus per sesi. Kerjakan fase berurutan, jalankan dan uji hasil tiap fase sebelum lanjut, lalu commit ke Git.

**Fase 0 — Persiapan**

- Simpan dokumen ini sebagai `docs/PRD.md` di root proyek, lalu buat file aturan agen (lihat bagian 13).
- Inisialisasi proyek Laravel, konfigurasi `.env` (MySQL, timezone, locale `id`), `git init`.

**Fase 1 — Fondasi dan Autentikasi**

- Install Breeze (Blade), tambah kolom `role`, buat middleware `role`, buat seeder 1 admin, 1 teknisi, 1 member.
- Layout utama dengan sidebar menyesuaikan role.
- Selesai jika: tiap role login dan masuk ke dashboard masing-masing, dan tidak bisa membuka halaman role lain.

**Fase 2 — Master Data & QR Code Aset**

- Migration, model, factory, dan seeder untuk labs, kategoris, asets.
- CRUD Lab, Kategori, Aset (dengan upload foto, kode otomatis, pencarian dan filter).
- Integrasi QR Code generator (`simplesoftwareio/simple-qrcode`) dan halaman cetak stiker label QR aset (satuan & massal).
- Selesai jika: Admin dapat mengelola seluruh master data, melihat detail aset lengkap, serta mengunduh/mencetak stiker QR Code aset.

**Fase 3 — Tiket Pengaduan (Guest QR & Member)**

- Migration tikets (`pelapor_id` nullable, `nama_pelapor`, `kontak_pelapor`), tiket\_fotos, log\_penanganans.
- Halaman publik lapor cepat via scan QR Code aset (`/lapor/{kode_aset}`) tanpa kewajiban login.
- Halaman buat tiket untuk Member login, serta menu "Tiket Saya" (daftar riwayat tiket dan detail timeline).
- Selesai jika: Pengguna dapat melapor tanpa login lewat URL QR aset, dan Member yang login dapat melapor serta memantau riwayat laporannya di dashboard.

**Fase 4 — Validasi, Disposisi, dan Pengerjaan**

- Halaman Admin untuk validasi dan disposisi. Halaman Teknisi untuk update progres. Terapkan aturan transisi status di `TiketService`. Sinkronkan kondisi aset otomatis.
- Timeline status pada detail tiket. Konfirmasi dan buka kembali oleh Member (untuk tiket member) atau konfirmasi oleh Admin (untuk tiket tamu).
- Selesai jika: satu tiket dapat dijalankan dari Pending sampai Selesai, dan dari Pending sampai Rusak Total, dengan seluruh log tercatat.

**Fase 5 — Dashboard dan Notifikasi**

- Dashboard 3 role dengan Chart.js (dashboard Member hanya menampilkan riwayat tiket miliknya), notifikasi dalam aplikasi, badge jumlah belum dibaca.

**Fase 6 — Laporan**

- Laporan Aset dan Laporan Perbaikan dengan filter, ekspor PDF dan Excel.

**Fase 7 — Penyempurnaan**

- Responsif di HP, uji seluruh alur (termasuk simulasi scan QR di HP), rapikan pesan error, tambah seeder data demo, tulis README (cara instalasi dan akun demo).
- Opsional v1.1: jadwal pemeliharaan berkala (preventif).

**Contoh prompt pembuka tiap fase (tempel ke agen)**

> Baca `docs/PRD.md` dan `.agent/rules` terlebih dahulu. Kerjakan HANYA Fase 2 (Master Data). Buat migration, model, controller, request validation, dan view Blade untuk Lab, Kategori, dan Aset sesuai FR-05 sampai FR-10. Jangan mengubah fitur fase lain. Setelah selesai, jalankan migrate dan seeder, lalu ringkas file apa saja yang dibuat dan cara mengujinya.

## 13. Aturan Kerja untuk Agen

Simpan sebagai `.agent/rules/silab.md` (atau pengaturan Rules di Antigravity) agar berlaku di setiap sesi:

- Proyek: SiLab, Laravel 11, MySQL, Blade, Tailwind, Alpine.js. Antarmuka Bahasa Indonesia.
- Rujukan utama adalah `docs/PRD.md`. Jangan menambah fitur di luar PRD tanpa persetujuan.
- Kerjakan satu fase per sesi. Jangan mengubah file di luar cakupan fase.
- Logika bisnis di Service class. Controller tipis. Validasi memakai Form Request. Otorisasi memakai middleware dan policy.
- Gunakan eager loading, hindari query di dalam loop.
- Nama tabel dan kolom memakai bahasa Indonesia seperti di PRD (snake\_case). Nama kelas memakai konvensi Laravel.
- Setiap fitur baru harus disertai seeder atau data contoh agar dapat diuji.
- Jangan menyimpan kredensial di kode. Gunakan `.env`.
- Di akhir tugas, tuliskan ringkasan perubahan dan langkah uji manual.
- Jika instruksi ambigu, ajukan pertanyaan singkat sebelum menulis kode.

## 14. Kriteria Penerimaan (Definition of Done)

- Seluruh FR berprioritas v1.0 berfungsi dan lolos uji manual pada 3 role.
- Pengguna umum dapat mengakses halaman lapor cepat via QR Code (`/lapor/{kode_aset}`) tanpa login dan berhasil mengirim tiket pengaduan.
- Akses ke menu rekapitulasi riwayat tiket ("Tiket Saya") dan dashboard hanya dapat dilihat oleh pengguna yang terautentikasi (member login); tamu tanpa login tidak dapat melihat daftar tiket.
- Admin dapat melihat dan mencetak label QR Code per aset.
- Alur lengkap Pending → Diproses → Menunggu Sparepart → Diproses → Selesai dapat dijalankan dan setiap langkah tercatat di log.
- Alur Rusak Total mengubah kondisi aset menjadi Tidak Layak Pakai.
- Role tidak dapat mengakses halaman atau data role lain (termasuk lewat URL langsung).
- Unggahan foto hanya menerima jpg dan png maksimal 2 MB.
- Ekspor PDF dan Excel menghasilkan data sesuai filter.
- Tampilan dapat dipakai di layar 360 px (termasuk form lapor cepat di ponsel).
- README berisi langkah instalasi dan akun demo.

## 15. Risiko dan Mitigasi

- **Agen melebar ke fitur lain**: batasi per fase dan gunakan file aturan agen.
- **Aturan transisi status tidak konsisten**: pusatkan di `TiketService` dan uji dengan data demo.
- **Spam pada form lapor cepat tanpa login**: pasang rate limiting pada route publik pengaduan QR.
- **Foto terlalu besar memenuhi penyimpanan**: batasi ukuran, pertimbangkan kompresi otomatis.
- **Data aset awal belum rapi**: sediakan fitur impor Excel untuk aset (opsional v1.1).
- **Pengguna enggan memakai**: stiker QR code ditempel di setiap meja/aset sehingga pelapor cukup memindai dengan kamera ponsel tanpa repot mendaftar.
