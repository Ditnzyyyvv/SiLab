<?php

namespace Database\Seeders;

use App\Models\Aset;
use App\Models\Kategori;
use App\Models\Lab;
use App\Models\User;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@silab.sch.id')->first() ?? User::first();
        $teknisi = User::where('email', 'teknisi@silab.sch.id')->first() ?? User::first();

        // 1. Seed Laboratorium
        $labRpl = Lab::updateOrCreate(
            ['kode' => 'RPL'],
            [
                'nama' => 'Lab Rekayasa Perangkat Lunak',
                'lokasi' => 'Gedung B Lantai 2 (R. 201)',
                'penanggung_jawab_id' => $admin->id,
            ]
        );

        $labTkj = Lab::updateOrCreate(
            ['kode' => 'TKJ'],
            [
                'nama' => 'Lab Teknik Komputer & Jaringan',
                'lokasi' => 'Gedung B Lantai 3 (R. 302)',
                'penanggung_jawab_id' => $teknisi->id,
            ]
        );

        $labMm = Lab::updateOrCreate(
            ['kode' => 'MM'],
            [
                'nama' => 'Lab Multimedia & Grafis',
                'lokasi' => 'Gedung C Lantai 1 (R. 104)',
                'penanggung_jawab_id' => $admin->id,
            ]
        );

        // 2. Seed Kategori
        $katPc = Kategori::firstOrCreate(['nama' => 'Komputer & PC']);
        $katPeriferal = Kategori::firstOrCreate(['nama' => 'Periferal & Input']);
        $katJaringan = Kategori::firstOrCreate(['nama' => 'Perangkat Jaringan']);
        $katMultimedia = Kategori::firstOrCreate(['nama' => 'Proyektor & Multimedia']);

        // 3. Seed Aset Contoh
        $asets = [
            [
                'kode_aset' => 'AST-RPL-0001',
                'nama' => 'PC Client 01',
                'lab_id' => $labRpl->id,
                'kategori_id' => $katPc->id,
                'merek' => 'Lenovo ThinkCentre Neo 50t',
                'spesifikasi' => "Intel Core i5-12400\nRAM 16GB DDR4\nSSD NVMe 512GB\nMonitor Lenovo 21.5 inch",
                'tanggal_perolehan' => '2025-01-15',
                'kondisi' => 'Baik',
            ],
            [
                'kode_aset' => 'AST-RPL-0002',
                'nama' => 'PC Client 02',
                'lab_id' => $labRpl->id,
                'kategori_id' => $katPc->id,
                'merek' => 'Lenovo ThinkCentre Neo 50t',
                'spesifikasi' => "Intel Core i5-12400\nRAM 16GB DDR4\nSSD NVMe 512GB\nMonitor Lenovo 21.5 inch",
                'tanggal_perolehan' => '2025-01-15',
                'kondisi' => 'Rusak Ringan',
            ],
            [
                'kode_aset' => 'AST-RPL-0003',
                'nama' => 'Switch Distribusi RPL',
                'lab_id' => $labRpl->id,
                'kategori_id' => $katJaringan->id,
                'merek' => 'Cisco Catalyst 2960',
                'spesifikasi' => '24 Port Gigabit Ethernet Managed Switch',
                'tanggal_perolehan' => '2024-07-20',
                'kondisi' => 'Baik',
            ],
            [
                'kode_aset' => 'AST-TKJ-0001',
                'nama' => 'Router Utama Lab TKJ',
                'lab_id' => $labTkj->id,
                'kategori_id' => $katJaringan->id,
                'merek' => 'MikroTik CCR1009',
                'spesifikasi' => 'Cloud Core Router 9-core CPU 1.2GHz, 1GB RAM, SFP port',
                'tanggal_perolehan' => '2024-03-10',
                'kondisi' => 'Baik',
            ],
            [
                'kode_aset' => 'AST-TKJ-0002',
                'nama' => 'PC Praktik Jaringan 01',
                'lab_id' => $labTkj->id,
                'kategori_id' => $katPc->id,
                'merek' => 'Dell OptiPlex 3080',
                'spesifikasi' => "Intel Core i3-10100\nRAM 8GB DDR4\nDual Gigabit NIC (LAN Praktik)",
                'tanggal_perolehan' => '2024-05-12',
                'kondisi' => 'Baik',
            ],
            [
                'kode_aset' => 'AST-MM-0001',
                'nama' => 'Proyektor Ruang Multimedia',
                'lab_id' => $labMm->id,
                'kategori_id' => $katMultimedia->id,
                'merek' => 'Epson EB-X400',
                'spesifikasi' => '3300 Lumens XGA 3LCD Projector dengan HDMI Input',
                'tanggal_perolehan' => '2023-11-05',
                'kondisi' => 'Dalam Perbaikan',
            ],
            [
                'kode_aset' => 'AST-MM-0002',
                'nama' => 'PC Render & Editing 01',
                'lab_id' => $labMm->id,
                'kategori_id' => $katPc->id,
                'merek' => 'Custom Workstation',
                'spesifikasi' => "AMD Ryzen 7 5700X\nGPU Nvidia RTX 3060 12GB\nRAM 32GB DDR4\nSSD 1TB NVMe",
                'tanggal_perolehan' => '2025-02-01',
                'kondisi' => 'Baik',
            ],
        ];

        foreach ($asets as $asetData) {
            Aset::updateOrCreate(
                ['kode_aset' => $asetData['kode_aset']],
                $asetData
            );
        }
    }
}

