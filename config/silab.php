<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Identitas Sekolah & KOP Surat Resmi SiLab
    |--------------------------------------------------------------------------
    | Konfigurasi ini digunakan pada cetak laporan resmi PDF (Laporan Inventaris
    | Aset & Laporan Pemeliharaan/Perbaikan) serta informasi footer aplikasi.
    | Nilai dapat disesuaikan dengan mudah melalui berkas .env tanpa ubah kode.
    */

    'instansi' => env('SILAB_INSTANSI', 'DINAS PENDIDIKAN — PEMERINTAH DAERAH'),
    'nama_sekolah' => env('SILAB_NAMA_SEKOLAH', 'SMK NEGERI 1 — LABORATORIUM KOMPUTER TERPADU'),
    'alamat' => env('SILAB_ALAMAT', 'Jl. Pendidikan Terpadu No. 1 | Telp: (021) 7890123 | Email: silab@smkn1.sch.id'),
    'kota' => env('SILAB_KOTA', 'Indonesia'),

    'kepala_lab_nama' => env('SILAB_KEPALA_LAB_NAMA', 'Kepala Pengelola Laboratorium'),
    'kepala_lab_nip' => env('SILAB_KEPALA_LAB_NIP', 'NIP. 19780512 200312 1 002'),

    'koordinator_teknisi_nama' => env('SILAB_KOORDINATOR_TEKNISI_NAMA', 'Faisal Rahman, S.Kom.'),
    'koordinator_teknisi_nip' => env('SILAB_KOORDINATOR_TEKNISI_NIP', 'NIP. 19850314 201101 1 004'),
];
