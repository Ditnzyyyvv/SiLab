<?php

namespace Tests\Feature;

use App\Models\Aset;
use App\Models\Kategori;
use App\Models\Lab;
use App\Models\User;
use App\Services\AsetService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MasterDataTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $teknisi;
    protected User $member;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin', 'email' => 'admin@silab.sch.id']);
        $this->teknisi = User::factory()->create(['role' => 'teknisi', 'email' => 'teknisi@silab.sch.id']);
        $this->member = User::factory()->create(['role' => 'member', 'email' => 'member@silab.sch.id']);
    }

    public function test_non_admin_cannot_access_master_data(): void
    {
        $this->actingAs($this->member)->get('/admin/labs')->assertForbidden();
        $this->actingAs($this->member)->get('/admin/kategoris')->assertForbidden();
        $this->actingAs($this->member)->get('/admin/asets')->assertForbidden();

        $this->actingAs($this->teknisi)->get('/admin/labs')->assertForbidden();
        $this->actingAs($this->teknisi)->get('/admin/kategoris')->assertForbidden();
        $this->actingAs($this->teknisi)->get('/admin/asets')->assertForbidden();
    }

    public function test_admin_can_manage_labs(): void
    {
        // 1. View index
        $this->actingAs($this->admin)->get('/admin/labs')->assertOk()->assertSee('Data Laboratorium');

        // 2. Create lab
        $response = $this->actingAs($this->admin)->post('/admin/labs', [
            'kode' => 'RPL',
            'nama' => 'Lab Rekayasa Perangkat Lunak',
            'lokasi' => 'Gedung B Lantai 2',
            'penanggung_jawab_id' => $this->admin->id,
        ]);

        $response->assertRedirect(route('admin.labs.index'));
        $this->assertDatabaseHas('labs', ['kode' => 'RPL', 'nama' => 'Lab Rekayasa Perangkat Lunak']);

        $lab = Lab::where('kode', 'RPL')->first();

        // 3. Update lab
        $this->actingAs($this->admin)->put(route('admin.labs.update', $lab), [
            'kode' => 'RPL1',
            'nama' => 'Lab RPL 1',
            'lokasi' => 'Gedung B Lantai 2 R201',
            'penanggung_jawab_id' => $this->admin->id,
        ])->assertRedirect(route('admin.labs.index'));

        $this->assertDatabaseHas('labs', ['kode' => 'RPL1', 'nama' => 'Lab RPL 1']);

        // 4. Delete lab
        $this->actingAs($this->admin)->delete(route('admin.labs.destroy', $lab))
            ->assertRedirect(route('admin.labs.index'));

        $this->assertSoftDeleted('labs', ['id' => $lab->id]);
    }

    public function test_admin_can_manage_kategori(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/kategoris', [
            'nama' => 'Komputer & PC',
        ]);

        $response->assertRedirect(route('admin.kategoris.index'));
        $this->assertDatabaseHas('kategoris', ['nama' => 'Komputer & PC']);

        $kategori = Kategori::where('nama', 'Komputer & PC')->first();

        $this->actingAs($this->admin)->put(route('admin.kategoris.update', $kategori), [
            'nama' => 'Komputer Workstation',
        ])->assertRedirect(route('admin.kategoris.index'));

        $this->assertDatabaseHas('kategoris', ['nama' => 'Komputer Workstation']);

        $this->actingAs($this->admin)->delete(route('admin.kategoris.destroy', $kategori))
            ->assertRedirect(route('admin.kategoris.index'));

        $this->assertDatabaseMissing('kategoris', ['id' => $kategori->id]);
    }

    public function test_aset_auto_code_generation_and_creation_with_photo(): void
    {
        Storage::fake('public');

        $lab = Lab::factory()->create(['kode' => 'TKJ']);
        $kategori = Kategori::factory()->create(['nama' => 'Jaringan']);

        $photo = UploadedFile::fake()->image('switch.jpg', 600, 600)->size(500);

        // Tambah aset pertama (kode otomatis)
        $response = $this->actingAs($this->admin)->post('/admin/asets', [
            'lab_id' => $lab->id,
            'kategori_id' => $kategori->id,
            'nama' => 'Switch Cisco 24 Port',
            'merek' => 'Cisco Catalyst',
            'kondisi' => 'Baik',
            'foto' => $photo,
        ]);

        $this->assertDatabaseHas('asets', [
            'kode_aset' => 'AST-TKJ-0001',
            'nama' => 'Switch Cisco 24 Port',
            'lab_id' => $lab->id,
        ]);

        $aset = Aset::where('kode_aset', 'AST-TKJ-0001')->first();
        $response->assertRedirect(route('admin.asets.show', $aset));

        $this->assertNotNull($aset->foto);
        Storage::disk('public')->assertExists($aset->foto);

        // Tambah aset kedua pada lab yang sama (otomatis increment jadi 0002)
        $this->actingAs($this->admin)->post('/admin/asets', [
            'lab_id' => $lab->id,
            'kategori_id' => $kategori->id,
            'nama' => 'Router MikroTik',
            'merek' => 'MikroTik',
            'kondisi' => 'Baik',
        ]);

        $this->assertDatabaseHas('asets', [
            'kode_aset' => 'AST-TKJ-0002',
            'nama' => 'Router MikroTik',
        ]);
    }

    public function test_aset_service_generates_valid_qr_svg(): void
    {
        $lab = Lab::factory()->create(['kode' => 'RPL']);
        $kategori = Kategori::factory()->create();
        $aset = Aset::factory()->create([
            'lab_id' => $lab->id,
            'kategori_id' => $kategori->id,
            'kode_aset' => 'AST-RPL-0001',
        ]);

        $service = app(AsetService::class);
        $svg = $service->generateQrSvg($aset, 180);

        $this->assertStringContainsString('<svg', $svg);
        $this->assertStringContainsString('</svg>', $svg);
    }

    public function test_print_qr_endpoints_and_public_scan_preview(): void
    {
        $lab = Lab::factory()->create(['kode' => 'MM']);
        $kategori = Kategori::factory()->create(['nama' => 'Proyektor']);
        $aset = Aset::factory()->create([
            'lab_id' => $lab->id,
            'kategori_id' => $kategori->id,
            'kode_aset' => 'AST-MM-0001',
            'nama' => 'Proyektor Epson',
        ]);

        // 1. Single print QR
        $this->actingAs($this->admin)->get(route('admin.asets.print-qr', $aset))
            ->assertOk()
            ->assertSee('AST-MM-0001')
            ->assertSee('Proyektor Epson');

        // 2. Batch print QR
        $this->actingAs($this->admin)->get(route('admin.asets.print-all-qr'))
            ->assertOk()
            ->assertSee('AST-MM-0001');

        // 3. Public QR scan URL (FR-07b / Alur 1)
        $this->get('/lapor/AST-MM-0001')
            ->assertOk()
            ->assertSee('Proyektor Epson')
            ->assertSee('AST-MM-0001');
    }
}
