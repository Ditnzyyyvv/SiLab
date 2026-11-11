<?php

namespace Tests\Feature;

use App\Models\Aset;
use App\Models\Kategori;
use App\Models\Lab;
use App\Models\Tiket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TiketPengaduanTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $teknisi;
    protected User $member;
    protected User $otherMember;
    protected Lab $lab;
    protected Kategori $kategori;
    protected Aset $aset;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin', 'email' => 'admin@silab.sch.id']);
        $this->teknisi = User::factory()->create(['role' => 'teknisi', 'email' => 'teknisi@silab.sch.id']);
        $this->member = User::factory()->create(['role' => 'member', 'email' => 'member@silab.sch.id', 'nama' => 'Budi Santoso']);
        $this->otherMember = User::factory()->create(['role' => 'member', 'email' => 'other@silab.sch.id', 'nama' => 'Siti Nurhaliza']);

        $this->lab = Lab::factory()->create([
            'kode' => 'RPL',
            'nama' => 'Laboratorium Rekayasa Perangkat Lunak',
            'lokasi' => 'Gedung B Lantai 2',
            'penanggung_jawab_id' => $this->admin->id,
        ]);

        $this->kategori = Kategori::factory()->create([
            'nama' => 'Komputer PC',
        ]);

        $this->aset = Aset::factory()->create([
            'kode_aset' => 'AST-RPL-0001',
            'nama' => 'PC Client 01',
            'kategori_id' => $this->kategori->id,
            'lab_id' => $this->lab->id,
            'merek' => 'Lenovo ThinkCentre',
            'spesifikasi' => 'Core i5-12400, 16GB RAM, 512GB SSD',
            'tanggal_perolehan' => '2024-01-15',
            'kondisi' => 'Baik',
        ]);
    }

    public function test_guest_can_access_lapor_form_via_qr_url(): void
    {
        $response = $this->get('/lapor/' . $this->aset->kode_aset);

        $response->assertOk();
        $response->assertSee('Lapor Kerusakan Aset');
        $response->assertSee($this->aset->nama);
        $response->assertSee($this->aset->kode_aset);
        $response->assertSee($this->lab->nama);
    }

    public function test_lapor_form_returns_404_for_invalid_asset_code(): void
    {
        $response = $this->get('/lapor/AST-TIDAK-ADA');

        $response->assertNotFound();
    }

    public function test_guest_can_submit_report_without_login(): void
    {
        Storage::fake('public');

        $payload = [
            'nama_pelapor' => 'Dewi Sartika (Siswa)',
            'kontak_pelapor' => '081234567899',
            'judul' => 'Mouse tidak bisa klik kiri',
            'deskripsi' => 'Klik kiri keras dan tidak terdeteksi oleh sistem operasi.',
            'fotos' => [
                UploadedFile::fake()->image('bukti1.jpg'),
            ],
        ];

        $response = $this->post('/lapor/' . $this->aset->kode_aset, $payload);

        $tiket = Tiket::first();
        $this->assertNotNull($tiket);

        $response->assertRedirect('/lapor/sukses/' . $tiket->nomor_tiket);
        $this->assertNull($tiket->pelapor_id);
        $this->assertEquals('Dewi Sartika (Siswa)', $tiket->nama_pelapor);
        $this->assertEquals('081234567899', $tiket->kontak_pelapor);
        $this->assertEquals('Pending', $tiket->status);
        $this->assertStringStartsWith('TKT-', $tiket->nomor_tiket);

        // Cek log penanganan awal
        $this->assertDatabaseHas('log_penanganans', [
            'tiket_id' => $tiket->id,
            'status_ke' => 'Pending',
        ]);

        // Cek foto tersimpan
        $this->assertCount(1, $tiket->fotos);
        Storage::disk('public')->assertExists($tiket->fotos->first()->path);
    }

    public function test_guest_validation_fails_when_fields_empty(): void
    {
        $response = $this->post('/lapor/' . $this->aset->kode_aset, []);

        $response->assertSessionHasErrors(['nama_pelapor', 'judul', 'deskripsi']);
        $this->assertDatabaseCount('tikets', 0);
    }

    public function test_guest_cannot_upload_more_than_three_photos(): void
    {
        Storage::fake('public');

        $payload = [
            'nama_pelapor' => 'Tamu Lab',
            'judul' => 'Kerusakan PC',
            'deskripsi' => 'Kerusakan fisik parah.',
            'fotos' => [
                UploadedFile::fake()->image('foto1.jpg'),
                UploadedFile::fake()->image('foto2.jpg'),
                UploadedFile::fake()->image('foto3.jpg'),
                UploadedFile::fake()->image('foto4.jpg'),
            ],
        ];

        $response = $this->post('/lapor/' . $this->aset->kode_aset, $payload);

        $response->assertSessionHasErrors(['fotos']);
        $this->assertDatabaseCount('tikets', 0);
    }

    public function test_guest_can_view_success_page(): void
    {
        $tiket = Tiket::factory()->create([
            'aset_id' => $this->aset->id,
            'pelapor_id' => null,
            'nomor_tiket' => 'TKT-20261111-9999',
            'nama_pelapor' => 'Tamu Lab',
        ]);

        $response = $this->get('/lapor/sukses/' . $tiket->nomor_tiket);

        $response->assertOk();
        $response->assertSee('Laporan Berhasil Terkirim!');
        $response->assertSee($tiket->nomor_tiket);
        $response->assertSee($this->aset->nama);
    }

    public function test_guest_cannot_access_member_tickets_page(): void
    {
        $response = $this->get('/member/tikets');

        $response->assertRedirect('/login');
    }

    public function test_member_can_view_their_tickets_index(): void
    {
        // Tiket milik member ini
        $myTiket = Tiket::factory()->member($this->member)->create([
            'aset_id' => $this->aset->id,
            'judul' => 'Tiket Saya Sendiri',
        ]);

        // Tiket milik member lain
        $otherTiket = Tiket::factory()->member($this->otherMember)->create([
            'aset_id' => $this->aset->id,
            'judul' => 'Tiket Milik Orang Lain',
        ]);

        $response = $this->actingAs($this->member)->get('/member/tikets');

        $response->assertOk();
        $response->assertSee('Tiket Saya Sendiri');
        $response->assertDontSee('Tiket Milik Orang Lain');
    }

    public function test_member_can_create_ticket_from_member_portal(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->member)->get('/member/tikets/create');
        $response->assertOk();
        $response->assertSee($this->aset->nama);

        $payload = [
            'aset_id' => $this->aset->id,
            'judul' => 'PC tidak menyala setelah petir',
            'deskripsi' => 'Lampu indikator power mati total saat tombol power ditekan.',
            'kontak_pelapor' => '085566778899',
        ];

        $postResponse = $this->actingAs($this->member)->post('/member/tikets', $payload);

        $tiket = Tiket::first();
        $this->assertNotNull($tiket);

        $postResponse->assertRedirect('/member/tikets/' . $tiket->nomor_tiket);
        $this->assertEquals($this->member->id, $tiket->pelapor_id);
        $this->assertEquals($this->member->nama, $tiket->nama_pelapor);
        $this->assertEquals('Pending', $tiket->status);

        $this->assertDatabaseHas('log_penanganans', [
            'tiket_id' => $tiket->id,
            'user_id' => $this->member->id,
            'status_ke' => 'Pending',
        ]);
    }

    public function test_member_can_view_own_ticket_detail(): void
    {
        $tiket = Tiket::factory()->member($this->member)->create([
            'aset_id' => $this->aset->id,
            'judul' => 'Detail Tiket Milik Saya',
        ]);

        $response = $this->actingAs($this->member)->get('/member/tikets/' . $tiket->nomor_tiket);

        $response->assertOk();
        $response->assertSee('Detail Tiket Milik Saya');
        $response->assertSee($this->aset->kode_aset);
    }

    public function test_member_cannot_view_other_members_ticket(): void
    {
        $tiketLain = Tiket::factory()->member($this->otherMember)->create([
            'aset_id' => $this->aset->id,
            'judul' => 'Rahasia Member Lain',
        ]);

        $response = $this->actingAs($this->member)->get('/member/tikets/' . $tiketLain->nomor_tiket);

        $response->assertForbidden();
    }

    public function test_member_dashboard_shows_recent_tickets_and_counters(): void
    {
        Tiket::factory()->member($this->member)->create([
            'aset_id' => $this->aset->id,
            'status' => 'Pending',
            'judul' => 'Tiket Pending Terbaru',
        ]);

        Tiket::factory()->member($this->member)->create([
            'aset_id' => $this->aset->id,
            'status' => 'Selesai',
            'judul' => 'Tiket Sudah Selesai',
        ]);

        $response = $this->actingAs($this->member)->get('/member/dashboard');

        $response->assertOk();
        $response->assertSee('Tiket Pending Terbaru');
        $response->assertSee('Tiket Sudah Selesai');
        $response->assertSee('Tiket Pending');
        $response->assertSee('Laporan Kerusakan Terbaru Saya');
    }
}
