<?php

namespace Tests\Feature;

use App\Models\Aset;
use App\Models\Kategori;
use App\Models\Lab;
use App\Models\Tiket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $teknisi;
    protected User $member;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'nama' => 'Admin Utama',
            'email' => 'admin.utama@silab.sch.id',
        ]);

        $this->teknisi = User::factory()->create([
            'role' => 'teknisi',
            'nama' => 'Teknisi Satu',
            'email' => 'teknisi.satu@silab.sch.id',
        ]);

        $this->member = User::factory()->create([
            'role' => 'member',
            'nama' => 'Member Satu',
            'email' => 'member.satu@silab.sch.id',
        ]);
    }

    public function test_non_admin_cannot_access_user_management(): void
    {
        $this->get(route('admin.users.index'))->assertRedirect(route('login'));
        $this->actingAs($this->member)->get(route('admin.users.index'))->assertForbidden();
        $this->actingAs($this->teknisi)->get(route('admin.users.index'))->assertForbidden();
    }

    public function test_admin_can_view_and_filter_users(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.users.index'));
        $response->assertOk()
            ->assertSee('Admin Utama')
            ->assertSee('Teknisi Satu')
            ->assertSee('Member Satu');

        $filtered = $this->actingAs($this->admin)->get(route('admin.users.index', ['role' => 'teknisi']));
        $filtered->assertOk()
            ->assertSee('Teknisi Satu')
            ->assertDontSee('Member Satu');
    }

    public function test_admin_can_create_new_technician_account(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.users.store'), [
            'nama' => 'Teknisi Baru Lab',
            'email' => 'teknisi.baru@silab.sch.id',
            'role' => 'teknisi',
            'no_hp' => '081233445566',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('success');

        $created = User::where('email', 'teknisi.baru@silab.sch.id')->first();
        $this->assertNotNull($created);
        $this->assertEquals('teknisi', $created->role);
        $this->assertTrue(Hash::check('password123', $created->password));
    }

    public function test_admin_can_update_user_and_optional_password(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admin.users.update', $this->member), [
            'nama' => 'Member Diperbarui',
            'email' => 'member.satu@silab.sch.id',
            'role' => 'teknisi',
            'no_hp' => '089911223344',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('success');

        $this->member->refresh();
        $this->assertEquals('Member Diperbarui', $this->member->nama);
        $this->assertEquals('teknisi', $this->member->role);
    }

    public function test_admin_cannot_downgrade_last_admin_role(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admin.users.update', $this->admin), [
            'nama' => 'Admin Utama',
            'email' => 'admin.utama@silab.sch.id',
            'role' => 'member',
        ]);

        $response->assertSessionHas('error');
        $this->admin->refresh();
        $this->assertEquals('admin', $this->admin->role);
    }

    public function test_admin_cannot_delete_self_or_technician_with_active_tasks(): void
    {
        // Guard 1: tidak bisa hapus diri sendiri
        $resSelf = $this->actingAs($this->admin)->delete(route('admin.users.destroy', $this->admin));
        $resSelf->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);

        // Guard 2: tidak bisa hapus teknisi yang sedang menangani tiket aktif
        $lab = Lab::factory()->create();
        $kategori = Kategori::factory()->create();
        $aset = Aset::factory()->create(['lab_id' => $lab->id, 'kategori_id' => $kategori->id]);

        Tiket::factory()->create([
            'aset_id' => $aset->id,
            'teknisi_id' => $this->teknisi->id,
            'status' => 'Diproses',
        ]);

        $resTeknisi = $this->actingAs($this->admin)->delete(route('admin.users.destroy', $this->teknisi));
        $resTeknisi->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $this->teknisi->id]);

        // Dapat menghapus member tanpa kendala
        $resMember = $this->actingAs($this->admin)->delete(route('admin.users.destroy', $this->member));
        $resMember->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('success');
        $this->assertDatabaseMissing('users', ['id' => $this->member->id]);
    }
}
