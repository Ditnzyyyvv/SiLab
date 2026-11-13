<?php

namespace Tests\Feature;

use App\Models\Aset;
use App\Models\Kategori;
use App\Models\Lab;
use App\Models\Tiket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaporanEksporTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $teknisi;
    protected User $member;
    protected Lab $labA;
    protected Lab $labB;
    protected Kategori $kategoriPC;
    protected Kategori $kategoriJaringan;
    protected Aset $asetBaik;
    protected Aset $asetRusak;
    protected Tiket $tiketSelesai;
    protected Tiket $tiketDiproses;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'nama' => 'Admin Sarpras',
            'email' => 'admin.laporan@silab.sch.id',
        ]);

        $this->teknisi = User::factory()->create([
            'role' => 'teknisi',
            'nama' => 'Teknisi Jaringan',
            'email' => 'teknisi.laporan@silab.sch.id',
        ]);

        $this->member = User::factory()->create([
            'role' => 'member',
            'nama' => 'Siswa RPL',
            'email' => 'siswa.laporan@silab.sch.id',
        ]);

        $this->labA = Lab::factory()->create(['kode' => 'LAB-RPL', 'nama' => 'Laboratorium Rekayasa Perangkat Lunak']);
        $this->labB = Lab::factory()->create(['kode' => 'LAB-TKJ', 'nama' => 'Laboratorium Komputer Jaringan']);

        $this->kategoriPC = Kategori::factory()->create(['nama' => 'Komputer PC']);
        $this->kategoriJaringan = Kategori::factory()->create(['nama' => 'Perangkat Jaringan']);

        $this->asetBaik = Aset::factory()->create([
            'kode_aset' => 'AST-RPL-001',
            'nama' => 'PC Workstation 01',
            'lab_id' => $this->labA->id,
            'kategori_id' => $this->kategoriPC->id,
            'kondisi' => 'Baik',
        ]);

        $this->asetRusak = Aset::factory()->create([
            'kode_aset' => 'AST-TKJ-001',
            'nama' => 'Switch Gigabit 24 Port',
            'lab_id' => $this->labB->id,
            'kategori_id' => $this->kategoriJaringan->id,
            'kondisi' => 'Rusak Ringan',
        ]);

        $this->tiketSelesai = Tiket::factory()->create([
            'nomor_tiket' => 'TKT-202611-0001',
            'aset_id' => $this->asetBaik->id,
            'pelapor_id' => $this->member->id,
            'nama_pelapor' => 'Siswa RPL',
            'judul' => 'Mouse tidak terdeteksi port USB',
            'deskripsi' => 'Mouse optic mati mendadak saat praktikum',
            'prioritas' => 'Sedang',
            'status' => 'Selesai',
            'teknisi_id' => $this->teknisi->id,
            'created_at' => now()->subDays(3),
            'tanggal_selesai' => now()->subDay(),
        ]);

        $this->tiketDiproses = Tiket::factory()->create([
            'nomor_tiket' => 'TKT-202611-0002',
            'aset_id' => $this->asetRusak->id,
            'pelapor_id' => null, // Guest QR report
            'nama_pelapor' => 'Tamu Praktikan Lab',
            'judul' => 'Port 5 switch mati tidak ada link',
            'deskripsi' => 'Kabel LAN tidak memancarkan sinyal koneksi',
            'prioritas' => 'Tinggi',
            'status' => 'Diproses',
            'teknisi_id' => $this->teknisi->id,
            'created_at' => now(),
        ]);
    }

    /**
     * Hak akses: Non-admin tidak boleh mengakses modul laporan (FR-29, FR-30).
     */
    public function test_non_admin_cannot_access_laporan_routes(): void
    {
        // Tamu (guest tanpa login) diarahkan ke login
        $this->get(route('admin.laporan.index'))->assertRedirect(route('login'));
        $this->get(route('admin.laporan.aset'))->assertRedirect(route('login'));
        $this->get(route('admin.laporan.perbaikan'))->assertRedirect(route('login'));

        // Member login ditolak (403 Forbidden)
        $this->actingAs($this->member)
            ->get(route('admin.laporan.index'))
            ->assertForbidden();

        $this->actingAs($this->member)
            ->get(route('admin.laporan.aset'))
            ->assertForbidden();

        // Teknisi login ditolak (403 Forbidden)
        $this->actingAs($this->teknisi)
            ->get(route('admin.laporan.index'))
            ->assertForbidden();
    }

    /**
     * Admin dapat melihat halaman hub laporan (FR-29, FR-30).
     */
    public function test_admin_can_view_laporan_hub(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.laporan.index'));

        $response->assertOk()
            ->assertSee('Pusat Laporan')
            ->assertSee('Laporan Inventaris Aset Laboratorium')
            ->assertSee('Laporan Pemeliharaan')
            ->assertSee('FR-29')
            ->assertSee('FR-30');
    }

    /**
     * Admin dapat melihat pratinjau Laporan Aset dan memfilter data (FR-29).
     */
    public function test_admin_can_view_and_filter_laporan_aset(): void
    {
        // Akses tanpa filter (menampilkan semua aset)
        $response = $this->actingAs($this->admin)->get(route('admin.laporan.aset'));

        $response->assertOk()
            ->assertSee('AST-RPL-001')
            ->assertSee('PC Workstation 01')
            ->assertSee('AST-TKJ-001')
            ->assertSee('Switch Gigabit 24 Port');

        // Filter berdasarkan Lab A
        $responseFilteredLab = $this->actingAs($this->admin)->get(route('admin.laporan.aset', [
            'lab_id' => $this->labA->id,
        ]));

        $responseFilteredLab->assertOk()
            ->assertSee('AST-RPL-001')
            ->assertDontSee('AST-TKJ-001');

        // Filter berdasarkan Kondisi Baik
        $responseFilteredKondisi = $this->actingAs($this->admin)->get(route('admin.laporan.aset', [
            'kondisi' => 'Baik',
        ]));

        $responseFilteredKondisi->assertOk()
            ->assertSee('AST-RPL-001')
            ->assertDontSee('AST-TKJ-001');
    }

    /**
     * Admin dapat mengunduh Laporan Aset dalam format PDF (FR-29).
     */
    public function test_admin_can_download_laporan_aset_pdf(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.laporan.aset.pdf'));

        $response->assertOk();
        $this->assertEquals('application/pdf', $response->headers->get('content-type'));
        $this->assertStringContainsString('Laporan_Aset_SiLab_', $response->headers->get('content-disposition'));
    }

    /**
     * Admin dapat mengunduh Laporan Aset dalam format Excel/CSV (FR-29).
     */
    public function test_admin_can_download_laporan_aset_excel(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.laporan.aset.excel'));

        $response->assertOk();
        $this->assertStringContainsString('text/csv', $response->headers->get('content-type'));
        $this->assertStringContainsString('Laporan_Aset_SiLab_', $response->headers->get('content-disposition'));

        // Periksa isi stream (BOM UTF-8, Header, dan Konten)
        $content = $response->streamedContent();
        $this->assertStringContainsString('LAPORAN DATA INVENTARIS ASET LABORATORIUM', $content);
        $this->assertStringContainsString('AST-RPL-001', $content);
        $this->assertStringContainsString('PC Workstation 01', $content);
    }

    /**
     * Admin dapat melihat pratinjau Laporan Perbaikan dan memfilter data (FR-30).
     */
    public function test_admin_can_view_and_filter_laporan_perbaikan(): void
    {
        // Akses tanpa filter
        $response = $this->actingAs($this->admin)->get(route('admin.laporan.perbaikan'));

        $response->assertOk()
            ->assertSee('TKT-202611-0001')
            ->assertSee('TKT-202611-0002')
            ->assertSee('Mouse tidak terdeteksi port USB')
            ->assertSee('Port 5 switch mati tidak ada link');

        // Filter berdasarkan Status Selesai
        $responseFilteredStatus = $this->actingAs($this->admin)->get(route('admin.laporan.perbaikan', [
            'status' => 'Selesai',
        ]));

        $responseFilteredStatus->assertOk()
            ->assertSee('TKT-202611-0001')
            ->assertDontSee('TKT-202611-0002');

        // Filter berdasarkan Prioritas Tinggi
        $responseFilteredPrio = $this->actingAs($this->admin)->get(route('admin.laporan.perbaikan', [
            'prioritas' => 'Tinggi',
        ]));

        $responseFilteredPrio->assertOk()
            ->assertSee('TKT-202611-0002')
            ->assertDontSee('TKT-202611-0001');
    }

    /**
     * Admin dapat mengunduh Laporan Perbaikan dalam format PDF (FR-30).
     */
    public function test_admin_can_download_laporan_perbaikan_pdf(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.laporan.perbaikan.pdf'));

        $response->assertOk();
        $this->assertEquals('application/pdf', $response->headers->get('content-type'));
        $this->assertStringContainsString('Laporan_Perbaikan_SiLab_', $response->headers->get('content-disposition'));
    }

    /**
     * Admin dapat mengunduh Laporan Perbaikan dalam format Excel/CSV (FR-30).
     */
    public function test_admin_can_download_laporan_perbaikan_excel(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.laporan.perbaikan.excel'));

        $response->assertOk();
        $this->assertStringContainsString('text/csv', $response->headers->get('content-type'));
        $this->assertStringContainsString('Laporan_Perbaikan_SiLab_', $response->headers->get('content-disposition'));

        $content = $response->streamedContent();
        $this->assertStringContainsString('LAPORAN PEMELIHARAAN DAN PERBAIKAN ASET', $content);
        $this->assertStringContainsString('TKT-202611-0001', $content);
        $this->assertStringContainsString('TKT-202611-0002', $content);
        $this->assertStringContainsString('Teknisi Jaringan', $content);
    }
}

