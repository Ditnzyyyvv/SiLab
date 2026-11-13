<?php

namespace Database\Seeders;

use App\Models\Aset;
use App\Models\LogPenanganan;
use App\Models\Tiket;
use App\Models\User;
use Illuminate\Database\Seeder;

class TiketSeeder extends Seeder
{
    public function run(): void
    {
        $member = User::where('role', 'member')->first();
        $teknisi = User::where('role', 'teknisi')->first();
        $admin = User::where('role', 'admin')->first();

        $aset1 = Aset::first();
        $aset2 = Aset::skip(1)->first() ?? $aset1;
        $aset3 = Aset::skip(2)->first() ?? $aset1;

        if (! $aset1) {
            return;
        }

        $today = now()->format('Ymd');

        // 1. Tiket Guest / Tamu tanpa Login (Status: Pending)
        $t1 = Tiket::updateOrCreate(
            ['nomor_tiket' => "TKT-{$today}-0001"],
            [
                'aset_id' => $aset1->id,
                'pelapor_id' => null,
                'nama_pelapor' => 'Ahmad Fauzi (Siswa Tamu)',
                'kontak_pelapor' => '081299887766',
                'judul' => 'Keyboard tidak merespon saat ditekan tombol enter',
                'deskripsi' => 'Tombol enter dan space pada keyboard macet total, sudah dicoba colok ulang port USB tetap tidak jalan.',
                'prioritas' => null,
                'status' => 'Pending',
                'created_at' => now()->subHours(2),
            ]
        );

        LogPenanganan::firstOrCreate(
            [
                'tiket_id' => $t1->id,
                'status_ke' => 'Pending',
            ],
            [
                'user_id' => null,
                'status_dari' => null,
                'catatan' => 'Laporan dibuat oleh Ahmad Fauzi (Siswa Tamu) via QR Code tanpa login.',
            ]
        );

        if ($member) {
            // 2. Tiket Member (Status: Diproses)
            $t2 = Tiket::updateOrCreate(
                ['nomor_tiket' => "TKT-{$today}-0002"],
                [
                    'aset_id' => $aset2->id,
                    'pelapor_id' => $member->id,
                    'nama_pelapor' => $member->nama,
                    'kontak_pelapor' => $member->no_hp,
                    'judul' => 'Layar monitor flickering bergaris hijau',
                    'deskripsi' => 'Ketika dinyalakan monitor mengeluarkan garis-garis hijau horizontal dan berkedip terus menerus.',
                    'prioritas' => 'Sedang',
                    'status' => 'Diproses',
                    'teknisi_id' => $teknisi?->id,
                    'tanggal_disposisi' => now()->subHours(1),
                    'created_at' => now()->subHours(4),
                ]
            );

            LogPenanganan::firstOrCreate(
                [
                    'tiket_id' => $t2->id,
                    'status_ke' => 'Pending',
                ],
                [
                    'user_id' => $member->id,
                    'status_dari' => null,
                    'catatan' => 'Laporan dibuat oleh member ' . $member->nama,
                ]
            );

            LogPenanganan::firstOrCreate(
                [
                    'tiket_id' => $t2->id,
                    'status_ke' => 'Diproses',
                ],
                [
                    'user_id' => $admin?->id ?? $teknisi?->id,
                    'status_dari' => 'Pending',
                    'catatan' => 'Tiket divalidasi dan ditugaskan ke teknisi ' . ($teknisi?->nama ?? 'Teknisi'),
                ]
            );

            // 3. Tiket Member (Status: Selesai)
            $t3 = Tiket::updateOrCreate(
                ['nomor_tiket' => "TKT-{$today}-0003"],
                [
                    'aset_id' => $aset3->id,
                    'pelapor_id' => $member->id,
                    'nama_pelapor' => $member->nama,
                    'kontak_pelapor' => $member->no_hp,
                    'judul' => 'Kabel LAN tidak terhubung ke jaringan sekolah',
                    'deskripsi' => 'Konektor RJ45 patah penguncinya sehingga sering lepas dari port LAN PC.',
                    'prioritas' => 'Rendah',
                    'status' => 'Selesai',
                    'teknisi_id' => $teknisi?->id,
                    'tanggal_disposisi' => now()->subDays(1),
                    'tanggal_selesai' => now()->subHours(3),
                    'created_at' => now()->subDays(1)->subHours(2),
                ]
            );

            LogPenanganan::firstOrCreate(
                [
                    'tiket_id' => $t3->id,
                    'status_ke' => 'Pending',
                ],
                [
                    'user_id' => $member->id,
                    'status_dari' => null,
                    'catatan' => 'Laporan dibuat oleh member ' . $member->nama,
                ]
            );

            LogPenanganan::firstOrCreate(
                [
                    'tiket_id' => $t3->id,
                    'status_ke' => 'Diproses',
                ],
                [
                    'user_id' => $admin?->id ?? $teknisi?->id,
                    'status_dari' => 'Pending',
                    'catatan' => 'Ditugaskan ke teknisi ' . ($teknisi?->nama ?? 'Teknisi'),
                ]
            );

            LogPenanganan::firstOrCreate(
                [
                    'tiket_id' => $t3->id,
                    'status_ke' => 'Selesai',
                ],
                [
                    'user_id' => $teknisi?->id,
                    'status_dari' => 'Diproses',
                    'catatan' => 'Konektor RJ-45 sudah di-crimping ulang dan kabel LAN dites lancar 1 Gbps.',
                ]
            );
        }
    }
}

