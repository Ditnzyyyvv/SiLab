<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>429 — Terlalu Banyak Permintaan | SiLab</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-slate-800 bg-slate-50 min-h-screen py-10 px-4 flex flex-col justify-center items-center">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl border border-slate-200/80 p-8 text-center space-y-6">
        <div class="w-16 h-16 rounded-2xl bg-indigo-50 border border-indigo-200 text-indigo-600 mx-auto flex items-center justify-center">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        <div>
            <span class="inline-block px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-indigo-100 text-indigo-800">
                Galat 429 Terlalu Cepat
            </span>
            <h1 class="text-2xl font-bold text-slate-900 mt-2">Permintaan Dibatasi</h1>
            <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                Demi keamanan sistem dan pencegahan spam pada formulir lapor QR publik, permintaan Anda sementara dibatasi. Silakan tunggu sekitar 1 menit sebelum mencoba kembali.
            </p>
        </div>

        <div class="pt-2 border-t border-slate-100 space-y-2">
            <a href="javascript:location.reload()" class="block w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl shadow-xs transition-colors">
                Muat Ulang Halaman
            </a>
            <a href="{{ url('/') }}" class="block w-full py-2 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-medium rounded-xl transition-colors">
                Kembali ke Halaman Utama
            </a>
        </div>
    </div>
</body>
</html>

