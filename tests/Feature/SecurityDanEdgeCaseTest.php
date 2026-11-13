<?php

namespace Tests\Feature;

use App\Models\Aset;
use App\Models\Kategori;
use App\Models\Lab;
use App\Models\Tiket;
use App\Models\User;
use App\Services\TiketService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class SecurityDanEdgeCaseTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $teknisiA;
    protected User $teknisiB;
    protected User $memberA;
    protected User $memberB;
    protected Lab $lab;
    protected Kategori $kategori;
    protected Aset $aset;
    protected TiketService $tiketService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin', 'email' => 'admin.sec@silab.sch.id', 'nama' => 'Admin Security']);
        $this->teknisiA = User::factory()->create(['role' => 'teknisi', 'email' => 'teknisi.a@silab.sch.id', 'nama' => 'Teknisi A']);
        $this->teknisiB = User::factory()->create(['role' => 'teknisi', 'email' => 'teknisi.b@silab.sch.id', 'nama' => 'Teknisi B']);
        $this->memberA = User::factory()->create(['role' => 'member', 'email' => 'member.a@silab.sch.id', 'nama' => 'Member A']);
        $this->memberB = User::factory()->create(['role' => 'member', 'email' => 'member.b@silab.sch.id', 'nama' => 'Member B']);

        $this->lab = Lab::factory()->create(['kode' => 'RPL', 'nama' => 'Lab RPL']);
        $this->kategori = Kategori::factory()->create(['nama' => 'PC']);

        $this->aset = Aset::factory()->create([
            'kode_aset' => 'AST-RPL-0099',
            'nama' => 'PC Server RPL 99',
            'lab_id' => $this->lab->id,
            'kategori_id' => $this->kategori->id,
            'kondisi' => 'Baik',
        ]);

        $this->tiketService = app(TiketService::class);
    }

    /**
     * IDOR: Member B tidak dapat melihat tiket milik Member A (403 Forbidden).
     */
    public function test_member_cannot_view_another_members_ticket(): void
    {
        $tiketA = Tiket::factory()->create([
            'nomor_tiket' => 'TKT-202611-1001',
            'aset_id' => $this->aset->id,
            'pelapor_id' => $this->memberA->id,
            'nama_pelapor' => $this->memberA->nama,
            'status' => 'Pending',
        ]);

        // Member B mengakses tiket Member A -> 403 Forbidden
        $response = $this->actingAs($this->memberB)->get(route('member.tikets.show', $tiketA));
        $response->assertForbidden();
    }

    /**
     * IDOR: Member B tidak dapat mengonfirmasi atau membuka kembali tiket milik Member A.
     */
    public function test_member_cannot_confirm_or_reopen_another_members_ticket(): void
    {
        $tiketA = Tiket::factory()->create([
            'nomor_tiket' => 'TKT-202611-1002',
            'aset_id' => $this->aset->id,
            'pelapor_id' => $this->memberA->id,
            'nama_pelapor' => $this->memberA->nama,
            'status' => 'Selesai',
        ]);

        // Member B coba konfirmasi tiket Member A
        $responseKonfirmasi = $this->actingAs($this->memberB)->post(route('member.tikets.konfirmasi', $tiketA));
        $responseKonfirmasi->assertForbidden();

        // Member B coba buka kembali tiket Member A
        $responseReopen = $this->actingAs($this->memberB)->post(route('member.tikets.reopen', $tiketA), [
            'alasan' => 'Mencoba buka tiket orang lain',
        ]);
        $responseReopen->assertForbidden();
    }

    /**
     * IDOR: Teknisi B tidak dapat melihat atau meng-update status tugas yang ditugaskan ke Teknisi A.
     */
    public function test_technician_cannot_view_or_update_another_technicians_assigned_task(): void
    {
        $tiketTugasA = Tiket::factory()->create([
            'nomor_tiket' => 'TKT-202611-1003',
            'aset_id' => $this->aset->id,
            'pelapor_id' => $this->memberA->id,
            'teknisi_id' => $this->teknisiA->id,
            'status' => 'Diproses',
        ]);

        // Teknisi B coba buka tugas Teknisi A -> 403
        $responseView = $this->actingAs($this->teknisiB)->get(route('teknisi.tugas.show', $tiketTugasA));
        $responseView->assertForbidden();

        // Teknisi B coba update status tugas Teknisi A -> 403
        $responseUpdate = $this->actingAs($this->teknisiB)->post(route('teknisi.tugas.update-status', $tiketTugasA), [
            'status' => 'Selesai',
            'catatan' => 'Mencoba selesaikan tugas teknisi lain',
        ]);
        $responseUpdate->assertForbidden();
    }

    /**
     * Deletion Guard: Admin dilarang menghapus aset jika masih memiliki tiket penanganan aktif.
     */
    public function test_admin_cannot_delete_asset_with_active_tickets(): void
    {
        Tiket::factory()->create([
            'nomor_tiket' => 'TKT-202611-1004',
            'aset_id' => $this->aset->id,
            'status' => 'Diproses',
            'teknisi_id' => $this->teknisiA->id,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.asets.destroy', $this->aset));

        $response->assertSessionHas('error');
        $this->assertNotSoftDeleted($this->aset);
    }

    /**
     * Multi-Ticket Concurrency: Kondisi aset tetap 'Dalam Perbaikan' jika masih ada tiket aktif lain pada aset yang sama.
     */
    public function test_asset_condition_remains_dalam_perbaikan_if_another_ticket_is_still_active(): void
    {
        // Tiket 1: Layar bergaris
        $tiket1 = Tiket::factory()->create([
            'nomor_tiket' => 'TKT-202611-1005',
            'aset_id' => $this->aset->id,
            'status' => 'Diproses',
            'teknisi_id' => $this->teknisiA->id,
        ]);

        // Tiket 2: Keyboard rusak
        $tiket2 = Tiket::factory()->create([
            'nomor_tiket' => 'TKT-202611-1006',
            'aset_id' => $this->aset->id,
            'status' => 'Diproses',
            'teknisi_id' => $this->teknisiB->id,
        ]);

        $this->aset->update(['kondisi' => 'Dalam Perbaikan']);

        // Teknisi A menyelesaikan Tiket 1
        $this->tiketService->updateStatusTeknisi(
            $tiket1,
            'Selesai',
            'Layar monitor sudah diganti baru.',
            null,
            null,
            $this->teknisiA
        );

        // Verifikasi: Kondisi aset TETAP 'Dalam Perbaikan' karena Tiket 2 masih Diproses
        $this->aset->refresh();
        $this->assertEquals('Dalam Perbaikan', $this->aset->kondisi);

        // Teknisi B menyelesaikan Tiket 2
        $this->tiketService->updateStatusTeknisi(
            $tiket2,
            'Selesai',
            'Keyboard sudah diganti.',
            null,
            null,
            $this->teknisiB
        );

        // Verifikasi: Setelah SEMUA tiket selesai, kondisi aset baru kembali 'Baik'
        $this->aset->refresh();
        $this->assertEquals('Baik', $this->aset->kondisi);
    }

    /**
     * State Lock: Tiket yang sudah dikonfirmasi tuntas tidak dapat dibuka kembali via direct request.
     */
    public function test_confirmed_ticket_cannot_be_reopened(): void
    {
        $tiket = Tiket::factory()->create([
            'nomor_tiket' => 'TKT-202611-1007',
            'aset_id' => $this->aset->id,
            'pelapor_id' => $this->memberA->id,
            'nama_pelapor' => $this->memberA->nama,
            'status' => 'Selesai',
            'dikonfirmasi_at' => now(), // Sudah dikonfirmasi
        ]);

        $response = $this->actingAs($this->memberA)->post(route('member.tikets.reopen', $tiket), [
            'alasan' => 'Mencoba buka kembali setelah konfirmasi',
        ]);

        $response->assertSessionHas('error');
        $tiket->refresh();
        $this->assertEquals('Selesai', $tiket->status);
    }

    /**
     * State Lock: Tiket yang sudah dikonfirmasi tidak dapat dikonfirmasi ganda.
     */
    public function test_already_confirmed_ticket_cannot_be_reconfirmed(): void
    {
        $tiket = Tiket::factory()->create([
            'nomor_tiket' => 'TKT-202611-1008',
            'aset_id' => $this->aset->id,
            'pelapor_id' => $this->memberA->id,
            'nama_pelapor' => $this->memberA->nama,
            'status' => 'Selesai',
            'dikonfirmasi_at' => now()->subDay(),
        ]);

        $response = $this->actingAs($this->memberA)->post(route('member.tikets.konfirmasi', $tiket));

        $response->assertSessionHas('error');
    }

    /**
     * File Upload Security: Berkas non-gambar (.php, .exe) ditolak pada formulir pengaduan.
     */
    public function test_non_image_file_upload_is_rejected_on_public_lapor(): void
    {
        $fakeScript = UploadedFile::fake()->create('malicious.php', 10, 'application/x-php');

        $response = $this->post(route('lapor.store', $this->aset->kode_aset), [
            'nama_pelapor' => 'Tester Keamanan',
            'judul' => 'Pengujian unggah berkas ilegal',
            'deskripsi' => 'Mencoba unggah berkas skrip PHP',
            'fotos' => [$fakeScript],
        ]);

        $response->assertSessionHasErrors('fotos.0');
    }
}
