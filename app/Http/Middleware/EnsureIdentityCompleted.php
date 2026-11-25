<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIdentityCompleted
{
    /**
     * Pastikan pengguna yang baru masuk via Google OAuth sudah melengkapi identitas aslinya
     * (Nama Lengkap Asli & Kelas/Jabatan) sebelum mengakses fitur aplikasi.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->identitas_lengkap === false) {
            if (! $request->routeIs('auth.google.complete-profile*', 'logout')) {
                return redirect()->route('auth.google.complete-profile')
                    ->with('info', 'Mohon lengkapi identitas asli Anda terlebih dahulu agar Admin dan Teknisi mudah mengenali laporan Anda.');
            }
        }

        return $next($request);
    }
}
