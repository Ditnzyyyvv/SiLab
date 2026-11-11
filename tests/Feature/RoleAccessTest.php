<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_creates_three_roles(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseHas('users', ['email' => 'admin@silab.sch.id', 'role' => 'admin']);
        $this->assertDatabaseHas('users', ['email' => 'teknisi@silab.sch.id', 'role' => 'teknisi']);
        $this->assertDatabaseHas('users', ['email' => 'member@silab.sch.id', 'role' => 'member']);
    }

    public function test_unauthenticated_user_redirected_to_login(): void
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/login');

        $response = $this->get('/teknisi/dashboard');
        $response->assertRedirect('/login');

        $response = $this->get('/member/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_admin_accesses_own_dashboard_and_redirects_from_root_dashboard(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($admin)->get('/dashboard')->assertRedirect(route('admin.dashboard'));
        $this->actingAs($admin)->get('/admin/dashboard')->assertOk()->assertSee('Dashboard Administrator');

        // Admin restricted from other roles
        $this->actingAs($admin)->get('/teknisi/dashboard')->assertForbidden();
        $this->actingAs($admin)->get('/member/dashboard')->assertForbidden();
    }

    public function test_teknisi_accesses_own_dashboard_and_restricted_from_admin(): void
    {
        $teknisi = User::factory()->create([
            'role' => 'teknisi',
        ]);

        $this->actingAs($teknisi)->get('/dashboard')->assertRedirect(route('teknisi.dashboard'));
        $this->actingAs($teknisi)->get('/teknisi/dashboard')->assertOk()->assertSee('Dashboard Teknisi');

        // Teknisi restricted from admin and member dashboards
        $this->actingAs($teknisi)->get('/admin/dashboard')->assertForbidden();
        $this->actingAs($teknisi)->get('/member/dashboard')->assertForbidden();
    }

    public function test_member_accesses_own_dashboard_and_restricted_from_admin(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
        ]);

        $this->actingAs($member)->get('/dashboard')->assertRedirect(route('member.dashboard'));
        $this->actingAs($member)->get('/member/dashboard')->assertOk()->assertSee('Dashboard Member');

        // Member restricted from admin and teknisi dashboards
        $this->actingAs($member)->get('/admin/dashboard')->assertForbidden();
        $this->actingAs($member)->get('/teknisi/dashboard')->assertForbidden();
    }
}
