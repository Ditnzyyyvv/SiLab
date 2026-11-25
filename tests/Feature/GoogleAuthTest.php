<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Contracts\User as SocialiteUserContract;
use Laravel\Socialite\Facades\Socialite;
use Mockery;
use Tests\TestCase;

class GoogleAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function mockGoogleUser(string $id, string $name, string $email, ?string $avatar = null): void
    {
        $abstractUser = Mockery::mock(SocialiteUserContract::class);
        $abstractUser->shouldReceive('getId')->andReturn($id);
        $abstractUser->shouldReceive('getName')->andReturn($name);
        $abstractUser->shouldReceive('getEmail')->andReturn($email);
        $abstractUser->shouldReceive('getAvatar')->andReturn($avatar);

        $provider = Mockery::mock(\Laravel\Socialite\Contracts\Provider::class);
        $provider->shouldReceive('user')->andReturn($abstractUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);
    }

    public function test_google_redirect_returns_helpful_error_when_credentials_missing(): void
    {
        config()->set('services.google.client_id', null);
        config()->set('services.google.client_secret', null);

        $response = $this->get(route('auth.google.redirect'));

        $response->assertRedirect(route('login'))
            ->assertSessionHas('error');
    }

    public function test_new_google_user_is_redirected_to_complete_identity_screen(): void
    {
        $this->mockGoogleUser('google-12345', 'xX_ProGamer_99_Xx', 'gamer99@gmail.com', 'https://example.com/avatar.jpg');

        $response = $this->get(route('auth.google.callback'));

        $response->assertRedirect(route('auth.google.complete-profile'));

        $user = User::where('email', 'gamer99@gmail.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('google-12345', $user->google_id);
        $this->assertEquals('member', $user->role);
        $this->assertFalse($user->identitas_lengkap);
        $this->assertAuthenticatedAs($user);
    }

    public function test_google_user_cannot_bypass_identity_completion_to_access_dashboard(): void
    {
        $incompleteUser = User::factory()->create([
            'role' => 'member',
            'nama' => 'Akun Aneh 123',
            'google_id' => 'google-999',
            'identitas_lengkap' => false,
        ]);

        // Coba langsung akses /dashboard atau /member/dashboard -> dipaksa ke /lengkapi-identitas
        $this->actingAs($incompleteUser)->get(route('dashboard'))
            ->assertRedirect(route('auth.google.complete-profile'));

        $this->actingAs($incompleteUser)->get(route('member.dashboard'))
            ->assertRedirect(route('auth.google.complete-profile'));

        $this->actingAs($incompleteUser)->get(route('member.tikets.index'))
            ->assertRedirect(route('auth.google.complete-profile'));
    }

    public function test_google_user_can_complete_real_identity_and_access_dashboard(): void
    {
        $incompleteUser = User::factory()->create([
            'role' => 'member',
            'nama' => 'xX_DarkKnight_Xx',
            'google_id' => 'google-555',
            'identitas_lengkap' => false,
        ]);

        // Halaman form dapat dibuka
        $this->actingAs($incompleteUser)->get(route('auth.google.complete-profile'))
            ->assertOk()
            ->assertSee('Lengkapi Identitas Asli Anda')
            ->assertSee('xX_DarkKnight_Xx');

        // Submit identitas asli
        $response = $this->actingAs($incompleteUser)->post(route('auth.google.complete-profile.store'), [
            'nama' => 'Muhammad Aditya Pratama',
            'kelas_jabatan' => 'XI RPL 2',
            'no_hp' => '081234567890',
        ]);

        $response->assertRedirect(route('dashboard'))
            ->assertSessionHas('success');

        $incompleteUser->refresh();
        $this->assertEquals('Muhammad Aditya Pratama', $incompleteUser->nama);
        $this->assertEquals('XI RPL 2', $incompleteUser->kelas_jabatan);
        $this->assertEquals('081234567890', $incompleteUser->no_hp);
        $this->assertTrue($incompleteUser->identitas_lengkap);

        // Sekarang sudah bisa akses dashboard member
        $this->actingAs($incompleteUser)->get(route('member.dashboard'))->assertOk();
    }

    public function test_existing_verified_user_logging_in_with_google_goes_straight_to_dashboard(): void
    {
        $existingUser = User::factory()->create([
            'role' => 'member',
            'nama' => 'Siti Aisyah',
            'email' => 'siti@gmail.com',
            'identitas_lengkap' => true,
        ]);

        $this->mockGoogleUser('google-777', 'Siti Aisyah', 'siti@gmail.com');

        $response = $this->get(route('auth.google.callback'));

        $response->assertRedirect(route('dashboard'));
        $existingUser->refresh();
        $this->assertEquals('google-777', $existingUser->google_id);
        $this->assertTrue($existingUser->identitas_lengkap);
    }
}
