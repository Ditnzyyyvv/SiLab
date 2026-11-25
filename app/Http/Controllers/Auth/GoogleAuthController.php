<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompleteGoogleProfileRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Arahkan pengguna ke halaman autentikasi Google OAuth 2.0.
     */
    public function redirect(): RedirectResponse
    {
        if (empty(config('services.google.client_id')) || empty(config('services.google.client_secret'))) {
            return redirect()->route('login')
                ->with('error', 'Login dengan Google belum dikonfigurasi. Mohon isi GOOGLE_CLIENT_ID dan GOOGLE_CLIENT_SECRET pada berkas .env.');
        }

        return Socialite::driver('google')->redirect();
    }

    /**
     * Tangani callback dari Google OAuth 2.0.
     * Pengguna baru wajib melengkapi identitas asli (nama & kelas/jabatan) sebelum masuk dashboard.
     */
    public function callback(Request $request): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Throwable $e) {
            return redirect()->route('login')
                ->with('error', 'Autentikasi Google dibatalkan atau gagal. Silakan coba kembali.');
        }

        $email = strtolower((string) $googleUser->getEmail());
        if (empty($email)) {
            return redirect()->route('login')
                ->with('error', 'Akun Google tidak menyertakan alamat email yang valid.');
        }

        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $email)
            ->first();

        if ($user) {
            $updates = [];
            if (empty($user->google_id)) {
                $updates['google_id'] = $googleUser->getId();
            }
            if ($googleUser->getAvatar()) {
                $updates['avatar'] = $googleUser->getAvatar();
            }
            if (! empty($updates)) {
                $user->update($updates);
            }
        } else {
            // Pengguna baru via Google: tandai identitas_lengkap = false agar wajib isi nama asli & kelas/jabatan
            $user = User::create([
                'nama' => $googleUser->getName() ?: 'Pengguna Google',
                'email' => $email,
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'role' => 'member',
                'password' => Str::random(32),
                'email_verified_at' => now(),
                'identitas_lengkap' => false,
            ]);
        }

        Auth::login($user, true);
        $request->session()->regenerate();

        if (! $user->identitas_lengkap) {
            return redirect()->route('auth.google.complete-profile')
                ->with('info', 'Selamat datang! Karena akun Google terkadang menggunakan nama samaran, mohon masukkan identitas asli Anda terlebih dahulu.');
        }

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Tampilkan formulir pelengkapan identitas asli pasca-login Google.
     */
    public function showCompleteProfile(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if ($user->identitas_lengkap) {
            return redirect()->route('dashboard');
        }

        return view('auth.complete-google-profile', compact('user'));
    }

    /**
     * Simpan identitas asli pengguna Google dan aktifkan akses dashboard penuh.
     */
    public function storeCompleteProfile(CompleteGoogleProfileRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $user = $request->user();

        $user->update([
            'nama' => trim($validated['nama']),
            'kelas_jabatan' => trim($validated['kelas_jabatan']),
            'no_hp' => $validated['no_hp'] ? trim($validated['no_hp']) : $user->no_hp,
            'identitas_lengkap' => true,
        ]);

        return redirect()->route('dashboard')
            ->with('success', "Identitas berhasil disimpan. Selamat datang di SiLab, {$user->nama}!");
    }
}
