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
        $teknisi2 = User::where('email', 'teknisi2@silab.sch.id')->first() ?? $teknisi;

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
                'penanggung_jawab_id' => $teknisi2->id,
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

        // 3. Seed Aset Realistis
        $asets = [
            [
                'kode_aset' => 'AST-RPL-0001',
                'nama' => 'PC Client RPL 01',
                'lab_id' => $labRpl->id,
                'kategori_id' => $katPc->id,
                'merek' => 'Lenovo ThinkCentre Neo 50t',
                'spesifikasi' => "Intel Core i5-12400\nRAM 16GB DDR4\nSSD NVMe 512GB\nMonitor Lenovo 21.5 inch",
                'tanggal_perolehan' => '2025-01-15',
                'kondisi' => 'Baik',
            ],
            [
                'kode_aset' => 'AST-RPL-0002',
                'nama' => 'PC Client RPL 02',
                'lab_id' => $labRpl->id,
                'kategori_id' => $katPc->id,
                'merek' => 'Lenovo ThinkCentre Neo 50t',
                'spesifikasi' => "Intel Core i5-12400\nRAM 16GB DDR4\nSSD NVMe 512GB\nMonitor Lenovo 21.5 inch",
                'tanggal_perolehan' => '2025-01-15',
                'kondisi' => 'Rusak Ringan',
            ],
            [
                'kode_aset' => 'AST-RPL-0003',
                'nama' => 'Switch Distribusi Utama RPL',
                'lab_id' => $labRpl->id,
                'kategori_id' => $katJaringan->id,
                'merek' => 'Cisco Catalyst 2960',
                'spesifikasi' => '24 Port Gigabit Ethernet Managed Switch, Rackmount 1U',
                'tanggal_perolehan' => '2024-07-20',
                'kondisi' => 'Baik',
            ],
            [
                'kode_aset' => 'AST-RPL-0004',
                'nama' => 'Smart Display Interaktif RPL',
                'lab_id' => $labRpl->id,
                'kategori_id' => $katMultimedia->id,
                'merek' => 'Samsung Flip Pro 65 Inch',
                'spesifikasi' => 'Interactive 4K UHD Touchscreen Display with Wireless Casting',
                'tanggal_perolehan' => '2025-03-10',
                'kondisi' => 'Baik',
            ],
            [
                'kode_aset' => 'AST-TKJ-0001',
                'nama' => 'Router Core Lab TKJ',
                'lab_id' => $labTkj->id,
                'kategori_id' => $katJaringan->id,
                'merek' => 'MikroTik CCR1009-7G-1C-1S+',
                'spesifikasi' => 'Cloud Core Router 9-core CPU 1.2GHz, 1GB RAM, SFP+ 10G Port, RouterOS Level 6',
                'tanggal_perolehan' => '2024-03-10',
                'kondisi' => 'Baik',
            ],
            [
                'kode_aset' => 'AST-TKJ-0002',
                'nama' => 'PC Praktik Jaringan TKJ 01',
                'lab_id' => $labTkj->id,
                'kategori_id' => $katPc->id,
                'merek' => 'Dell OptiPlex 3080 Tower',
                'spesifikasi' => "Intel Core i3-10100 3.6GHz\nRAM 8GB DDR4\nDual Gigabit Ethernet NIC (LAN Praktik)",
                'tanggal_perolehan' => '2024-05-12',
                'kondisi' => 'Dalam Perbaikan',
            ],
            [
                'kode_aset' => 'AST-TKJ-0003',
                'nama' => 'Access Point Enterprise TKJ',
                'lab_id' => $labTkj->id,
                'kategori_id' => $katJaringan->id,
                'merek' => 'Cisco Catalyst 9115AX',
                'spesifikasi' => 'Wi-Fi 6 (802.11ax) Dual-Band Access Point with PoE+ Support',
                'tanggal_perolehan' => '2024-09-01',
                'kondisi' => 'Baik',
            ],
            [
                'kode_aset' => 'AST-TKJ-0004',
                'nama' => 'UPS Server Lab TKJ',
                'lab_id' => $labTkj->id,
                'kategori_id' => $katPeriferal->id,
                'merek' => 'APC Smart-UPS 1500VA',
                'spesifikasi' => '1000W / 1500VA LCD 230V SmartConnect Pure Sine Wave',
                'tanggal_perolehan' => '2023-08-15',
                'kondisi' => 'Tidak Layak Pakai',
            ],
            [
                'kode_aset' => 'AST-MM-0001',
                'nama' => 'Proyektor Ruang Multimedia',
                'lab_id' => $labMm->id,
                'kategori_id' => $katMultimedia->id,
                'merek' => 'Epson EB-2250U',
                'spesifikasi' => '5000 Lumens Full HD WUXGA 3LCD Projector dengan HDMI Input',
                'tanggal_perolehan' => '2023-11-05',
                'kondisi' => 'Dalam Perbaikan',
            ],
            [
                'kode_aset' => 'AST-MM-0002',
                'nama' => 'Workstation Render Multimedia 01',
                'lab_id' => $labMm->id,
                'kategori_id' => $katPc->id,
                'merek' => 'Custom Workstation Pro',
                'spesifikasi' => "AMD Ryzen 7 5700X\nGPU Nvidia RTX 3060 12GB\nRAM 32GB DDR4\nSSD 1TB NVMe Gen4",
                'tanggal_perolehan' => '2025-02-01',
                'kondisi' => 'Baik',
            ],
            [
                'kode_aset' => 'AST-MM-0003',
                'nama' => 'Pen Tablet Grafis MM 01',
                'lab_id' => $labMm->id,
                'kategori_id' => $katPeriferal->id,
                'merek' => 'Wacom Intuos Pro Medium',
                'spesifikasi' => '8192 Pressure Levels, Pro Pen 2 with Multi-touch Support',
                'tanggal_perolehan' => '2024-11-18',
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
