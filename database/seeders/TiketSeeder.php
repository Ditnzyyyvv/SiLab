<?php

namespace Database\Seeders;

use App\Models\Aset;
use App\Models\LogPenanganan;
use App\Models\Tiket;
use App\Models\User;
use App\Notifications\DisposisiTeknisiNotification;
use App\Notifications\StatusTiketUpdatedNotification;
use App\Notifications\TiketBaruNotification;
use Illuminate\Database\Seeder;

class TiketSeeder extends Seeder
{
    public function run(): void
    {
        $member1 = User::where('email', 'member@silab.sch.id')->first();
        $member2 = User::where('email', 'siswa2@silab.sch.id')->first() ?? $member1;
        $teknisi1 = User::where('email', 'teknisi@silab.sch.id')->first();
        $teknisi2 = User::where('email', 'teknisi2@silab.sch.id')->first() ?? $teknisi1;
        $admin = User::where('email', 'admin@silab.sch.id')->first();

        $asetRpl1 = Aset::where('kode_aset', 'AST-RPL-0001')->first() ?? Aset::first();
        $asetRpl2 = Aset::where('kode_aset', 'AST-RPL-0002')->first() ?? Aset::skip(1)->first();
        $asetRpl3 = Aset::where('kode_aset', 'AST-RPL-0003')->first() ?? Aset::first();
        $asetTkj1 = Aset::where('kode_aset', 'AST-TKJ-0001')->first() ?? Aset::first();
        $asetTkj2 = Aset::where('kode_aset', 'AST-TKJ-0002')->first() ?? Aset::first();
        $asetTkj4 = Aset::where('kode_aset', 'AST-TKJ-0004')->first() ?? Aset::first();
        $asetMm1 = Aset::where('kode_aset', 'AST-MM-0001')->first() ?? Aset::first();
        $asetMm2 = Aset::where('kode_aset', 'AST-MM-0002')->first() ?? Aset::first();

        if (! $asetRpl1) {
            return;
        }

        $today = now()->format('Ymd');

        // 1. TIKET GUEST / SCAN QR TANPA LOGIN (Status: Pending)
        $t1 = Tiket::updateOrCreate(
            ['nomor_tiket' => "TKT-{$today}-0001"],
            [
                'aset_id' => $asetRpl1->id,
                'pelapor_id' => null,
                'nama_pelapor' => 'Budi Santoso (Siswa Tamu)',
                'kontak_pelapor' => '081299887711',
                'judul' => 'Keyboard USB tidak merespon saat ditekan',
                'deskripsi' => 'Tombol enter dan spasi pada keyboard PC Client RPL 01 macet total saat praktikum coding web.',
                'prioritas' => null,
                'status' => 'Pending',
                'created_at' => now()->subHours(2),
            ]
        );

        LogPenanganan::firstOrCreate(
            ['tiket_id' => $t1->id, 'status_ke' => 'Pending'],
            [
                'user_id' => null,
                'status_dari' => null,
                'catatan' => 'Laporan dibuat oleh Budi Santoso (Siswa Tamu) via QR Code tanpa login.',
            ]
        );

        // Notifikasi ke admin untuk tiket baru tamu
        if ($admin) {
            $admin->notify(new TiketBaruNotification($t1));
        }

        // 2. TIKET MEMBER LOGIN (Status: Pending, Prioritas: Tinggi) -> Muncul di Alert Admin Dashboard
        $t2 = Tiket::updateOrCreate(
            ['nomor_tiket' => "TKT-{$today}-0002"],
            [
                'aset_id' => $asetTkj1->id,
                'pelapor_id' => $member2->id,
                'nama_pelapor' => $member2->nama,
                'kontak_pelapor' => $member2->no_hp,
                'judul' => 'Koneksi internet gateway lab sering drop timeout',
                'deskripsi' => 'Satu ruangan lab TKJ tidak dapat akses gateway internet saat latihan routing BGP, indikator lampu SFP berkedip merah.',
                'prioritas' => 'Tinggi',
                'status' => 'Pending',
                'created_at' => now()->subHours(1),
            ]
        );

        LogPenanganan::firstOrCreate(
            ['tiket_id' => $t2->id, 'status_ke' => 'Pending'],
            [
                'user_id' => $member2->id,
                'status_dari' => null,
                'catatan' => 'Laporan dibuat oleh member ' . $member2->nama . ' via dashboard.',
            ]
        );

        if ($admin) {
            $admin->notify(new TiketBaruNotification($t2));
        }

        // 3. TIKET MEMBER LOGIN (Status: Diproses, Teknisi: Faisal Rahman)
        $t3 = Tiket::updateOrCreate(
            ['nomor_tiket' => "TKT-{$today}-0003"],
            [
                'aset_id' => $asetRpl2->id,
                'pelapor_id' => $member1->id,
                'nama_pelapor' => $member1->nama,
                'kontak_pelapor' => $member1->no_hp,
                'judul' => 'Layar monitor flickering bergaris hijau horizontal',
                'deskripsi' => 'Ketika PC dinyalakan, monitor mengeluarkan garis berkedip hijau horizontal dan font buram.',
                'prioritas' => 'Sedang',
                'status' => 'Diproses',
                'teknisi_id' => $teknisi1?->id,
                'tanggal_disposisi' => now()->subHours(3),
                'created_at' => now()->subHours(5),
            ]
        );

        LogPenanganan::firstOrCreate(
            ['tiket_id' => $t3->id, 'status_ke' => 'Pending'],
            [
                'user_id' => $member1->id,
                'status_dari' => null,
                'catatan' => 'Laporan dibuat oleh member ' . $member1->nama,
            ]
        );

        LogPenanganan::firstOrCreate(
            ['tiket_id' => $t3->id, 'status_ke' => 'Diproses'],
            [
                'user_id' => $admin?->id,
                'status_dari' => 'Pending',
                'catatan' => 'Tiket divalidasi dan ditugaskan ke teknisi ' . ($teknisi1?->nama ?? 'Teknisi 1'),
            ]
        );

        if ($teknisi1) {
            $teknisi1->notify(new DisposisiTeknisiNotification($t3));
        }
        if ($member1) {
            $member1->notify(new StatusTiketUpdatedNotification(
                $t3,
                'Pending',
                'Tiket divalidasi dan ditugaskan ke teknisi ' . ($teknisi1?->nama ?? 'Teknisi')
            ));
        }

        // 4. TIKET MEMBER (Status: Menunggu Komponen/Sparepart, Teknisi: Rian Hidayat)
        $t4 = Tiket::updateOrCreate(
            ['nomor_tiket' => "TKT-{$today}-0004"],
            [
                'aset_id' => $asetTkj2->id,
                'pelapor_id' => $member2->id,
                'nama_pelapor' => $member2->nama,
                'kontak_pelapor' => $member2->no_hp,
                'judul' => 'PC Praktik Jaringan mati total power tidak masuk',
                'deskripsi' => 'Setelah dicek teknisi, unit Power Supply terbakar karena korsleting listrik lab.',
                'prioritas' => 'Tinggi',
                'status' => 'Menunggu Komponen/Sparepart',
                'teknisi_id' => $teknisi2?->id,
                'tanggal_disposisi' => now()->subHours(8),
                'created_at' => now()->subHours(10),
            ]
        );

        LogPenanganan::firstOrCreate(
            ['tiket_id' => $t4->id, 'status_ke' => 'Menunggu Komponen/Sparepart'],
            [
                'user_id' => $teknisi2?->id,
                'status_dari' => 'Diproses',
                'komponen_dibutuhkan' => 'Power Supply 500W 80+ Bronze ATX',
                'catatan' => 'Unit PSU rusak terbakar kapasitornya. Menunggu pengadaan sparepart PSU 500W Bronze.',
            ]
        );

        // 5. TIKET MEMBER (Status: Selesai, Terkonfirmasi oleh Member)
        $t5 = Tiket::updateOrCreate(
            ['nomor_tiket' => "TKT-{$today}-0005"],
            [
                'aset_id' => $asetRpl3->id,
                'pelapor_id' => $member1->id,
                'nama_pelapor' => $member1->nama,
                'kontak_pelapor' => $member1->no_hp,
                'judul' => 'Kabel patch cord switch longgar lepas pengunci',
                'deskripsi' => 'Konektor RJ45 patah penguncinya sehingga sering lepas dari port uplink switch.',
                'prioritas' => 'Rendah',
                'status' => 'Selesai',
                'teknisi_id' => $teknisi1?->id,
                'tanggal_disposisi' => now()->subDays(1),
                'tanggal_selesai' => now()->subHours(4),
                'dikonfirmasi_at' => now()->subHours(2),
                'created_at' => now()->subDays(1)->subHours(2),
            ]
        );

        LogPenanganan::firstOrCreate(
            ['tiket_id' => $t5->id, 'status_ke' => 'Selesai'],
            [
                'user_id' => $teknisi1?->id,
                'status_dari' => 'Diproses',
                'catatan' => 'Konektor RJ-45 sudah di-crimping ulang dan kabel LAN dites menggunakan LAN tester dengan hasil lancar 1 Gbps.',
            ]
        );

        // 6. TIKET GUEST (Status: Selesai, Terkonfirmasi oleh Admin)
        $t6 = Tiket::updateOrCreate(
            ['nomor_tiket' => "TKT-{$today}-0006"],
            [
                'aset_id' => $asetMm2->id,
                'pelapor_id' => null,
                'nama_pelapor' => 'Dewi Lestari (Guru Multimedia)',
                'kontak_pelapor' => '085712345678',
                'judul' => 'Driver GPU Nvidia crash saat rendering Blender 3D',
                'deskripsi' => 'Aplikasi rendering force close tiba-tiba dengan pesan driver timeout.',
                'prioritas' => 'Sedang',
                'status' => 'Selesai',
                'teknisi_id' => $teknisi2?->id,
                'tanggal_disposisi' => now()->subDays(2),
                'tanggal_selesai' => now()->subDay(),
                'dikonfirmasi_at' => now()->subHours(6),
                'created_at' => now()->subDays(2)->subHours(3),
            ]
        );

        LogPenanganan::firstOrCreate(
            ['tiket_id' => $t6->id, 'status_ke' => 'Selesai'],
            [
                'user_id' => $teknisi2?->id,
                'status_dari' => 'Diproses',
                'catatan' => 'Driver Nvidia Studio di-update bersih (clean install) ke versi terbaru. Uji rendering Cycles 15 menit berjalan normal.',
            ]
        );

        // 7. TIKET RUSAK TOTAL (Aset menjadi Tidak Layak Pakai)
        $t7 = Tiket::updateOrCreate(
            ['nomor_tiket' => "TKT-{$today}-0007"],
            [
                'aset_id' => $asetTkj4->id,
                'pelapor_id' => null,
                'nama_pelapor' => 'Petugas Piket Lab',
                'judul' => 'Baterai UPS bocor dan inverter mengeluarkan asap',
                'deskripsi' => 'Terjadi overheat internal baterai aki kering, casing membengkak dan PCB hangus terbakar.',
                'prioritas' => 'Tinggi',
                'status' => 'Rusak Total',
                'teknisi_id' => $teknisi1?->id,
                'tanggal_disposisi' => now()->subDays(3),
                'tanggal_selesai' => now()->subDays(2),
                'created_at' => now()->subDays(3)->subHours(4),
            ]
        );

        LogPenanganan::firstOrCreate(
            ['tiket_id' => $t7->id, 'status_ke' => 'Rusak Total'],
            [
                'user_id' => $teknisi1?->id,
                'status_dari' => 'Diproses',
                'catatan' => 'Kondisi komponen internal rusak parah di atas 80%. Biaya reparasi melampaui harga unit baru. Rekomendasi afkir/penghapusan aset.',
            ]
        );

        // 8. TIKET DITOLAK (Status: Ditolak oleh Admin)
        $t8 = Tiket::updateOrCreate(
            ['nomor_tiket' => "TKT-{$today}-0008"],
            [
                'aset_id' => $asetMm1->id,
                'pelapor_id' => $member1->id,
                'nama_pelapor' => $member1->nama,
                'kontak_pelapor' => $member1->no_hp,
                'judul' => 'Minta instal game balap di proyektor lab',
                'deskripsi' => 'Tolong instal aplikasi simulator game untuk refreshing siswa setelah ujian.',
                'prioritas' => 'Rendah',
                'status' => 'Ditolak',
                'alasan_penolakan' => 'Pengaduan tidak sesuai fungsi laboratorium sekolah dan melanggar tata tertib penggunaan sarana prasarana.',
                'created_at' => now()->subDays(4),
            ]
        );

        LogPenanganan::firstOrCreate(
            ['tiket_id' => $t8->id, 'status_ke' => 'Ditolak'],
            [
                'user_id' => $admin?->id,
                'status_dari' => 'Pending',
                'catatan' => 'Ditolak: Pengaduan tidak sesuai fungsi laboratorium sekolah dan melanggar tata tertib.',
            ]
        );

        if ($member1) {
            $member1->notify(new StatusTiketUpdatedNotification(
                $t8,
                'Pending',
                'Ditolak: Pengaduan tidak sesuai fungsi laboratorium sekolah dan melanggar tata tertib.'
            ));
        }
    }
}
