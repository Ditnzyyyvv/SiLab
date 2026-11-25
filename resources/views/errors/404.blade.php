<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 — Halaman Tidak Ditemukan | SiLab</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-slate-800 bg-slate-50 min-h-screen py-10 px-4 flex flex-col justify-center items-center">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl border border-slate-200/80 p-8 text-center space-y-6">
        <div class="w-16 h-16 rounded-2xl bg-amber-50 border border-amber-200 text-amber-600 mx-auto flex items-center justify-center">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        <div>
            <span class="inline-block px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-amber-100 text-amber-800">
                Galat 404 Not Found
            </span>
            <h1 class="text-2xl font-bold text-slate-900 mt-2">Halaman Tidak Ditemukan</h1>
            <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                {{ $exception->getMessage() ?: 'Halaman, tautan QR, atau data yang Anda cari tidak tersedia atau telah dipindahkan.' }}
            </p>
        </div>

        <div class="pt-2 border-t border-slate-100 space-y-2">
            @auth
                <a href="{{ route('dashboard') }}" class="block w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl shadow-xs transition-colors">
                    Kembali ke Dashboard Saya
                </a>
            @else
                <a href="{{ url('/') }}" class="block w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl shadow-xs transition-colors">
                    Kembali ke Halaman Utama
                </a>
            @endauth
        </div>
    </div>
</body>
</html>

