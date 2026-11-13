<?php

namespace Tests\Feature;

use App\Models\Aset;
use App\Models\Kategori;
use App\Models\Lab;
use App\Models\Tiket;
use App\Models\User;
use App\Notifications\DisposisiTeknisiNotification;
use App\Notifications\StatusTiketUpdatedNotification;
use App\Notifications\TiketBaruNotification;
use App\Notifications\TiketReopenedNotification;
use App\Services\TiketService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardDanNotifikasiTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $teknisi;
    protected User $member;
    protected Lab $lab;
    protected Kategori $kategori;
    protected Aset $aset;
    protected TiketService $tiketService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin', 'email' => 'admin@silab.sch.id', 'nama' => 'Admin Utama']);
        $this->teknisi = User::factory()->create(['role' => 'teknisi', 'email' => 'teknisi@silab.sch.id', 'nama' => 'Teknisi Utama']);
        $this->member = User::factory()->create(['role' => 'member', 'email' => 'member@silab.sch.id', 'nama' => 'Siswa Pelapor']);

        $this->lab = Lab::factory()->create([
            'kode' => 'RPL',
            'nama' => 'Laboratorium Rekayasa Perangkat Lunak',
            'penanggung_jawab_id' => $this->admin->id,
        ]);

        $this->kategori = Kategori::factory()->create(['nama' => 'Komputer PC']);

        $this->aset = Aset::factory()->create([
            'kode_aset' => 'AST-RPL-0001',
            'nama' => 'PC Server RPL 01',
            'kategori_id' => $this->kategori->id,
            'lab_id' => $this->lab->id,
            'kondisi' => 'Baik',
        ]);

        $this->tiketService = app(TiketService::class);
    }

    public function test_admin_dashboard_displays_all_fr25_metrics_and_charts(): void
    {
        // Buat beberapa tiket dengan variasi status & prioritas
        Tiket::factory()->guest()->create([
            'aset_id' => $this->aset->id,
            'status' => 'Pending',
            'prioritas' => 'Tinggi',
            'judul' => 'Kerusakan Sangat Mendesak',
        ]);

        Tiket::factory()->diproses($this->teknisi)->create([
            'aset_id' => $this->aset->id,
            'prioritas' => 'Sedang',
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/dashboard');

        $response->assertOk();
        $response->assertSee('Dashboard Administrator');
        $response->assertSee('Total Aset');
        $response->assertSee('Grafik Tiket Masuk dan Selesai');
        $response->assertSee('Distribusi Kondisi Aset');
        $response->assertSee('Komposisi Status Tiket');
        $response->assertSee('Beban Kerja Teknisi (FR-25)');
        $response->assertSee('monthlyTrendChart');
        $response->assertSee('kondisiAsetChart');
        $response->assertSee('statusTiketChart');
        // Tiket prioritas tinggi yang belum selesai muncul
        $response->assertSee('Kerusakan Sangat Mendesak');
        $response->assertSee($this->teknisi->nama);
    }

    public function test_teknisi_dashboard_displays_fr26_metrics_and_charts(): void
    {
        Tiket::factory()->diproses($this->teknisi)->create([
            'aset_id' => $this->aset->id,
            'status' => 'Diproses',
            'judul' => 'Tugas Sedang Dikerjakan',
        ]);

        Tiket::factory()->diproses($this->teknisi)->create([
            'aset_id' => $this->aset->id,
            'status' => 'Menunggu Komponen/Sparepart',
        ]);

        $response = $this->actingAs($this->teknisi)->get('/teknisi/dashboard');

        $response->assertOk();
        $response->assertSee('Dashboard Teknisi');
        $response->assertSee('Tugas Aktif (Diproses)');
        $response->assertSee('Menunggu Sparepart');
        $response->assertSee('Selesai Bulan Ini');
        $response->assertSee('teknisiStatusChart');
        $response->assertSee('teknisiTrendChart');
        $response->assertSee('Tugas Sedang Dikerjakan');
    }

    public function test_member_dashboard_displays_fr27_metrics_and_charts(): void
    {
        Tiket::factory()->member($this->member)->create([
            'aset_id' => $this->aset->id,
            'status' => 'Pending',
            'judul' => 'Laporan Member Aktif',
        ]);

        $response = $this->actingAs($this->member)->get('/member/dashboard');

        $response->assertOk();
        $response->assertSee('Dashboard Member');
        $response->assertSee('Proporsi Status Tiket Saya');
        $response->assertSee('memberStatusChart');
        $response->assertSee('Laporan Member Aktif');
    }

    public function test_new_ticket_creation_notifies_admins(): void
    {
        $payload = [
            'aset_id' => $this->aset->id,
            'judul' => 'Power Supply Meledak',
            'deskripsi' => 'Tercium bau gosong dari bagian PSU PC.',
        ];

        $tiket = $this->tiketService->createTiket($payload, [], $this->member);

        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $this->admin->id,
            'notifiable_type' => User::class,
            'type' => TiketBaruNotification::class,
        ]);

        $notification = $this->admin->notifications()->first();
        $this->assertNotNull($notification);
        $this->assertEquals('Pengaduan Tiket Baru', $notification->data['judul']);
        $this->assertEquals($tiket->nomor_tiket, $notification->data['nomor_tiket']);
    }

    public function test_disposisi_notifies_technician_and_member_reporter(): void
    {
        $tiket = Tiket::factory()->member($this->member)->create([
            'aset_id' => $this->aset->id,
            'status' => 'Pending',
        ]);

        $this->tiketService->disposisi($tiket, $this->teknisi->id, 'Tinggi', 'Segera tangani sebelum praktikum.', $this->admin);

        // Notifikasi ke teknisi
        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $this->teknisi->id,
            'type' => DisposisiTeknisiNotification::class,
        ]);

        // Notifikasi ke member pelapor
        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $this->member->id,
            'type' => StatusTiketUpdatedNotification::class,
        ]);
    }

    public function test_technician_status_update_notifies_member(): void
    {
        $tiket = Tiket::factory()->member($this->member)->diproses($this->teknisi)->create([
            'aset_id' => $this->aset->id,
        ]);

        $this->tiketService->updateStatusTeknisi(
            $tiket,
            'Menunggu Komponen/Sparepart',
            'Butuh penggantian fan processor.',
            'Fan Cooler 120mm',
            null,
            $this->teknisi
        );

        $notif = $this->member->notifications()->latest()->first();
        $this->assertNotNull($notif);
        $this->assertEquals('Status Tiket: Menunggu Komponen/Sparepart', $notif->data['judul']);
    }

    public function test_member_reopen_ticket_notifies_technician_and_admins(): void
    {
        $tiket = Tiket::factory()->member($this->member)->selesai($this->teknisi)->create([
            'aset_id' => $this->aset->id,
        ]);

        $this->tiketService->bukaKembaliMember($tiket, 'Layar masih sering mati setelah 5 menit.', $this->member);

        // Notifikasi ke teknisi penanggung jawab
        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $this->teknisi->id,
            'type' => TiketReopenedNotification::class,
        ]);

        // Notifikasi ke admin
        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $this->admin->id,
            'type' => TiketReopenedNotification::class,
        ]);
    }

    public function test_user_can_view_notifications_page(): void
    {
        $tiket = Tiket::factory()->create(['aset_id' => $this->aset->id]);
        $this->admin->notify(new TiketBaruNotification($tiket));

        $response = $this->actingAs($this->admin)->get('/notifikasi');

        $response->assertOk();
        $response->assertSee('Notifikasi Dalam Aplikasi');
        $response->assertSee('Pengaduan Tiket Baru');
        $response->assertSee($tiket->nomor_tiket);
    }

    public function test_clicking_notification_marks_it_as_read_and_redirects(): void
    {
        $tiket = Tiket::factory()->create(['aset_id' => $this->aset->id]);
        $this->admin->notify(new TiketBaruNotification($tiket));

        $notification = $this->admin->unreadNotifications()->first();
        $this->assertNotNull($notification);

        $response = $this->actingAs($this->admin)->get("/notifikasi/{$notification->id}/read");

        $response->assertRedirect(route('admin.tikets.show', $tiket));
        $this->assertCount(0, $this->admin->unreadNotifications);
    }

    public function test_user_can_mark_all_notifications_as_read(): void
    {
        $tiket1 = Tiket::factory()->create(['aset_id' => $this->aset->id]);
        $tiket2 = Tiket::factory()->create(['aset_id' => $this->aset->id]);

        $this->admin->notify(new TiketBaruNotification($tiket1));
        $this->admin->notify(new TiketBaruNotification($tiket2));

        $this->assertEquals(2, $this->admin->unreadNotifications()->count());

        $response = $this->actingAs($this->admin)->post('/notifikasi/mark-all-read');

        $response->assertRedirect();
        $this->assertEquals(0, $this->admin->fresh()->unreadNotifications()->count());
    }
}
