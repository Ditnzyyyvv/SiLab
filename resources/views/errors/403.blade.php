<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 — Akses Ditolak | SiLab</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-slate-800 bg-slate-50 min-h-screen py-10 px-4 flex flex-col justify-center items-center">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl border border-slate-200/80 p-8 text-center space-y-6">
        <div class="w-16 h-16 rounded-2xl bg-rose-50 border border-rose-200 text-rose-600 mx-auto flex items-center justify-center">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>

        <div>
            <span class="inline-block px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-rose-100 text-rose-800">
                Galat 403 Forbidden
            </span>
            <h1 class="text-2xl font-bold text-slate-900 mt-2">Akses Ditolak</h1>
            <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                {{ $exception->getMessage() ?: 'Anda tidak memiliki hak akses atau wewenang untuk membuka halaman ini.' }}
            </p>
        </div>

        <div class="pt-2 border-t border-slate-100 space-y-2">
            @auth
                <a href="{{ route('dashboard') }}" class="block w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl shadow-xs transition-colors">
                    Kembali ke Dashboard Saya
                </a>
            @else
                <a href="{{ route('login') }}" class="block w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl shadow-xs transition-colors">
                    Masuk ke Akun Anda
                </a>
            @endauth
            <a href="{{ url('/') }}" class="block w-full py-2 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-medium rounded-xl transition-colors">
                Halaman Utama SiLab
            </a>
        </div>
    </div>
</body>
</html>
