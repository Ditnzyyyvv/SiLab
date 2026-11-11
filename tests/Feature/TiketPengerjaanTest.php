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

class TiketPengerjaanTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $teknisi1;
    protected User $teknisi2;
    protected User $member;
    protected User $otherMember;
    protected Lab $lab;
    protected Kategori $kategori;
    protected Aset $aset;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin', 'email' => 'admin@silab.sch.id', 'nama' => 'Admin Lab']);
        $this->teknisi1 = User::factory()->create(['role' => 'teknisi', 'email' => 'teknisi1@silab.sch.id', 'nama' => 'Teknisi Satu']);
        $this->teknisi2 = User::factory()->create(['role' => 'teknisi', 'email' => 'teknisi2@silab.sch.id', 'nama' => 'Teknisi Dua']);
        $this->member = User::factory()->create(['role' => 'member', 'email' => 'member@silab.sch.id', 'nama' => 'Budi Siswa']);
        $this->otherMember = User::factory()->create(['role' => 'member', 'email' => 'other@silab.sch.id', 'nama' => 'Siti Siswi']);

        $this->lab = Lab::factory()->create([
            'kode' => 'TKJ',
            'nama' => 'Laboratorium TKJ',
            'penanggung_jawab_id' => $this->admin->id,
        ]);

        $this->kategori = Kategori::factory()->create(['nama' => 'Switch & Router']);

        $this->aset = Aset::factory()->create([
            'kode_aset' => 'AST-TKJ-0001',
            'nama' => 'Switch Cisco Catalyst 2960',
            'kategori_id' => $this->kategori->id,
            'lab_id' => $this->lab->id,
            'kondisi' => 'Baik',
        ]);
    }

    public function test_non_admin_cannot_access_admin_tickets_list(): void
    {
        $this->actingAs($this->teknisi1)->get('/admin/tikets')->assertForbidden();
        $this->actingAs($this->member)->get('/admin/tikets')->assertForbidden();
    }

    public function test_admin_can_view_tickets_list_and_detail(): void
    {
        $tiket = Tiket::factory()->guest()->create([
            'aset_id' => $this->aset->id,
            'judul' => 'Port 1-8 Mati Total',
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/tikets');
        $response->assertOk();
        $response->assertSee('Port 1-8 Mati Total');
        $response->assertSee($tiket->nomor_tiket);

        $detailResponse = $this->actingAs($this->admin)->get('/admin/tikets/' . $tiket->nomor_tiket);
        $detailResponse->assertOk();
        $detailResponse->assertSee($tiket->nomor_tiket);
        $detailResponse->assertSee($this->aset->nama);
    }

    public function test_admin_can_validate_and_disposisi_ticket_to_technician(): void
    {
        $tiket = Tiket::factory()->guest()->create([
            'aset_id' => $this->aset->id,
            'status' => 'Pending',
        ]);

        $payload = [
            'teknisi_id' => $this->teknisi1->id,
            'prioritas' => 'Tinggi',
            'catatan' => 'Segera cek port catuan daya sebelum jam praktikum.',
        ];

        $response = $this->actingAs($this->admin)->post("/admin/tikets/{$tiket->nomor_tiket}/disposisi", $payload);

        $response->assertRedirect("/admin/tikets/{$tiket->nomor_tiket}");

        $tiket->refresh();
        $this->assertEquals('Diproses', $tiket->status);
        $this->assertEquals('Tinggi', $tiket->prioritas);
        $this->assertEquals($this->teknisi1->id, $tiket->teknisi_id);
        $this->assertNotNull($tiket->tanggal_disposisi);

        // Aset otomatis masuk kondisi 'Dalam Perbaikan'
        $this->assertEquals('Dalam Perbaikan', $tiket->aset->fresh()->kondisi);

        // Log penanganan tercatat
        $this->assertDatabaseHas('log_penanganans', [
            'tiket_id' => $tiket->id,
            'status_dari' => 'Pending',
            'status_ke' => 'Diproses',
            'user_id' => $this->admin->id,
        ]);
    }

    public function test_admin_can_reject_ticket_with_mandatory_reason(): void
    {
        $tiket = Tiket::factory()->guest()->create([
            'aset_id' => $this->aset->id,
            'status' => 'Pending',
        ]);

        $payload = [
            'alasan_penolakan' => 'Perangkat setelah diperiksa berfungsi dengan baik dan normal.',
        ];

        $response = $this->actingAs($this->admin)->post("/admin/tikets/{$tiket->nomor_tiket}/tolak", $payload);

        $response->assertRedirect("/admin/tikets/{$tiket->nomor_tiket}");

        $tiket->refresh();
        $this->assertEquals('Ditolak', $tiket->status);
        $this->assertEquals('Perangkat setelah diperiksa berfungsi dengan baik dan normal.', $tiket->alasan_penolakan);

        $this->assertDatabaseHas('log_penanganans', [
            'tiket_id' => $tiket->id,
            'status_dari' => 'Pending',
            'status_ke' => 'Ditolak',
            'user_id' => $this->admin->id,
        ]);
    }

    public function test_rejection_requires_minimum_reason_length(): void
    {
        $tiket = Tiket::factory()->guest()->create(['aset_id' => $this->aset->id, 'status' => 'Pending']);

        $response = $this->actingAs($this->admin)->post("/admin/tikets/{$tiket->nomor_tiket}/tolak", [
            'alasan_penolakan' => 'No',
        ]);

        $response->assertSessionHasErrors(['alasan_penolakan']);
        $this->assertEquals('Pending', $tiket->fresh()->status);
    }

    public function test_technician_only_sees_their_assigned_tasks(): void
    {
        $tugasTeknisi1 = Tiket::factory()->diproses($this->teknisi1)->create([
            'aset_id' => $this->aset->id,
            'judul' => 'Tugas Khusus Teknisi Satu',
        ]);

        $tugasTeknisi2 = Tiket::factory()->diproses($this->teknisi2)->create([
            'aset_id' => $this->aset->id,
            'judul' => 'Tugas Milik Teknisi Dua',
        ]);

        $response = $this->actingAs($this->teknisi1)->get('/teknisi/tugas');

        $response->assertOk();
        $response->assertSee('Tugas Khusus Teknisi Satu');
        $response->assertDontSee('Tugas Milik Teknisi Dua');

        // Detail task access check
        $this->actingAs($this->teknisi1)->get('/teknisi/tugas/' . $tugasTeknisi1->nomor_tiket)->assertOk();
        $this->actingAs($this->teknisi1)->get('/teknisi/tugas/' . $tugasTeknisi2->nomor_tiket)->assertForbidden();
    }

    public function test_technician_can_set_status_to_menunggu_sparepart_with_component_name(): void
    {
        $tiket = Tiket::factory()->diproses($this->teknisi1)->create(['aset_id' => $this->aset->id]);

        $payload = [
            'status' => 'Menunggu Komponen/Sparepart',
            'catatan' => 'Modul power supply rusak terkena lonjakan arus listrik.',
            'komponen_dibutuhkan' => 'Power Supply Unit 48V 2.5A Cisco Internal',
        ];

        $response = $this->actingAs($this->teknisi1)->post("/teknisi/tugas/{$tiket->nomor_tiket}/status", $payload);

        $response->assertRedirect("/teknisi/tugas/{$tiket->nomor_tiket}");

        $tiket->refresh();
        $this->assertEquals('Menunggu Komponen/Sparepart', $tiket->status);

        $this->assertDatabaseHas('log_penanganans', [
            'tiket_id' => $tiket->id,
            'status_dari' => 'Diproses',
            'status_ke' => 'Menunggu Komponen/Sparepart',
            'komponen_dibutuhkan' => 'Power Supply Unit 48V 2.5A Cisco Internal',
            'user_id' => $this->teknisi1->id,
        ]);
    }

    public function test_menunggu_sparepart_requires_component_name(): void
    {
        $tiket = Tiket::factory()->diproses($this->teknisi1)->create(['aset_id' => $this->aset->id]);

        $payload = [
            'status' => 'Menunggu Komponen/Sparepart',
            'catatan' => 'Butuh suku cadang baru.',
            'komponen_dibutuhkan' => '',
        ];

        $response = $this->actingAs($this->teknisi1)->post("/teknisi/tugas/{$tiket->nomor_tiket}/status", $payload);

        $response->assertSessionHasErrors(['komponen_dibutuhkan']);
        $this->assertEquals('Diproses', $tiket->fresh()->status);
    }

    public function test_technician_completing_repair_automatically_sets_asset_to_baik(): void
    {
        Storage::fake('public');

        $this->aset->update(['kondisi' => 'Dalam Perbaikan']);

        $tiket = Tiket::factory()->diproses($this->teknisi1)->create(['aset_id' => $this->aset->id]);

        $payload = [
            'status' => 'Selesai',
            'catatan' => 'Pembersihan heatsink dan penggantian port ethernet berhasil diuji lancar.',
            'foto_bukti' => UploadedFile::fake()->image('hasil_perbaikan.jpg'),
        ];

        $response = $this->actingAs($this->teknisi1)->post("/teknisi/tugas/{$tiket->nomor_tiket}/status", $payload);

        $response->assertRedirect("/teknisi/tugas/{$tiket->nomor_tiket}");

        $tiket->refresh();
        $this->assertEquals('Selesai', $tiket->status);
        $this->assertNotNull($tiket->tanggal_selesai);

        // FR-24: Kondisi aset otomatis menjadi 'Baik'
        $this->assertEquals('Baik', $this->aset->fresh()->kondisi);

        // Foto bukti tersimpan di log
        $lastLog = $tiket->logs()->first();
        $this->assertNotNull($lastLog->foto_bukti);
        Storage::disk('public')->assertExists($lastLog->foto_bukti);
    }

    public function test_technician_marking_rusak_total_automatically_sets_asset_to_tidak_layak_pakai(): void
    {
        $this->aset->update(['kondisi' => 'Dalam Perbaikan']);

        $tiket = Tiket::factory()->diproses($this->teknisi1)->create(['aset_id' => $this->aset->id]);

        $payload = [
            'status' => 'Rusak Total',
            'catatan' => 'Mainboard terbakar parah dan komponen pengganti tidak lagi diproduksi.',
        ];

        $response = $this->actingAs($this->teknisi1)->post("/teknisi/tugas/{$tiket->nomor_tiket}/status", $payload);

        $response->assertRedirect("/teknisi/tugas/{$tiket->nomor_tiket}");

        $tiket->refresh();
        $this->assertEquals('Rusak Total', $tiket->status);

        // FR-24: Kondisi aset otomatis menjadi 'Tidak Layak Pakai'
        $this->assertEquals('Tidak Layak Pakai', $this->aset->fresh()->kondisi);
    }

    public function test_member_can_confirm_completed_ticket(): void
    {
        $tiket = Tiket::factory()->member($this->member)->selesai($this->teknisi1)->create([
            'aset_id' => $this->aset->id,
            'dikonfirmasi_at' => null,
        ]);

        $response = $this->actingAs($this->member)->post("/member/tikets/{$tiket->nomor_tiket}/konfirmasi");

        $response->assertRedirect("/member/tikets/{$tiket->nomor_tiket}");

        $tiket->refresh();
        $this->assertNotNull($tiket->dikonfirmasi_at);

        $this->assertDatabaseHas('log_penanganans', [
            'tiket_id' => $tiket->id,
            'user_id' => $this->member->id,
            'status_ke' => 'Selesai',
        ]);
    }

    public function test_member_can_reopen_completed_ticket_with_reason(): void
    {
        $this->aset->update(['kondisi' => 'Baik']);

        $tiket = Tiket::factory()->member($this->member)->selesai($this->teknisi1)->create([
            'aset_id' => $this->aset->id,
        ]);

        $payload = [
            'alasan' => 'Setelah dicek kembali, perangkat mati mendadak setelah 10 menit dinyalakan.',
        ];

        $response = $this->actingAs($this->member)->post("/member/tikets/{$tiket->nomor_tiket}/reopen", $payload);

        $response->assertRedirect("/member/tikets/{$tiket->nomor_tiket}");

        $tiket->refresh();
        $this->assertEquals('Diproses', $tiket->status);
        $this->assertNull($tiket->tanggal_selesai);
        $this->assertNull($tiket->dikonfirmasi_at);

        // Kondisi aset kembali ke 'Dalam Perbaikan'
        $this->assertEquals('Dalam Perbaikan', $this->aset->fresh()->kondisi);

        $this->assertDatabaseHas('log_penanganans', [
            'tiket_id' => $tiket->id,
            'status_dari' => 'Selesai',
            'status_ke' => 'Diproses',
            'user_id' => $this->member->id,
        ]);
    }

    public function test_other_member_cannot_confirm_or_reopen_ticket(): void
    {
        $tiket = Tiket::factory()->member($this->member)->selesai($this->teknisi1)->create([
            'aset_id' => $this->aset->id,
        ]);

        $this->actingAs($this->otherMember)->post("/member/tikets/{$tiket->nomor_tiket}/konfirmasi")->assertForbidden();
        $this->actingAs($this->otherMember)->post("/member/tikets/{$tiket->nomor_tiket}/reopen", ['alasan' => 'Coba reopen orang lain'])->assertForbidden();
    }

    public function test_admin_can_confirm_guest_completed_ticket(): void
    {
        $tiket = Tiket::factory()->guest()->selesai($this->teknisi1)->create([
            'aset_id' => $this->aset->id,
            'dikonfirmasi_at' => null,
        ]);

        $response = $this->actingAs($this->admin)->post("/admin/tikets/{$tiket->nomor_tiket}/konfirmasi-tamu");

        $response->assertRedirect("/admin/tikets/{$tiket->nomor_tiket}");

        $tiket->refresh();
        $this->assertNotNull($tiket->dikonfirmasi_at);

        $this->assertDatabaseHas('log_penanganans', [
            'tiket_id' => $tiket->id,
            'user_id' => $this->admin->id,
            'status_ke' => 'Selesai',
        ]);
    }
}
