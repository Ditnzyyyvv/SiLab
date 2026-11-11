<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SiLab — Sistem Informasi Pemeliharaan & Pencatatan Aset Lab Sekolah</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-slate-800 bg-slate-50 min-h-screen flex flex-col justify-between">
        <!-- Top Navigation -->
        <header class="w-full bg-white border-b border-slate-200/80 sticky top-0 z-30 shadow-xs">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-bold text-xl shadow-md shadow-indigo-600/30">
                        SL
                    </div>
                    <div>
                        <span class="text-lg font-bold text-slate-900 tracking-tight">SiLab</span>
                        <span class="text-xs text-slate-500 block -mt-1 font-medium">Aset Laboratorium Sekolah</span>
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm transition-colors">
                            Buka Dashboard →
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-slate-700 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition-colors">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm transition-colors">
                            Daftar Member
                        </a>
                    @endauth
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <main class="flex-1 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 flex flex-col justify-center">
            <div class="text-center max-w-3xl mx-auto space-y-6">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                    🚀 Versi 1.1 SiLab Siap Digunakan
                </span>
                <h1 class="text-4xl sm:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight">
                    Pencatatan Aset & Pelaporan Kerusakan Lab Jadi <span class="text-indigo-600">Mudah & Terlacak</span>
                </h1>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">
                    Kelola seluruh inventaris laboratorium sekolah, terima aduan kerusakan secara cepat lewat scan stiker QR Code pada aset fisik tanpa wajib login, dan pantau proses perbaikan teknisi hingga selesai.
                </p>

                <!-- Action CTAs -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
                    @auth
                        <a href="{{ route('dashboard') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3.5 text-base font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-md shadow-indigo-600/20 transition-all">
                            Masuk ke Dashboard Saya
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3.5 text-base font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-md shadow-indigo-600/20 transition-all">
                            Masuk ke Akun
                        </a>
                        <a href="{{ route('register') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3.5 text-base font-semibold text-slate-700 bg-white hover:bg-slate-100 border border-slate-300 rounded-xl shadow-xs transition-all">
                            Registrasi Akun Baru (Opsional)
                        </a>
                    @endauth
                </div>
            </div>

            <!-- 3 Highlights Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-14">
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                        </svg>
                    </div>
                    <h2 class="text-base font-bold text-slate-900 mb-1">Scan QR Lapor Cepat</h2>
                    <p class="text-sm text-slate-600">
                        Siswa dan guru di lab dapat langsung scan stiker QR pada aset fisik dan mengirimkan keluhan kerusakan tanpa harus registrasi atau login.
                    </p>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <h2 class="text-base font-bold text-slate-900 mb-1">Disposisi & Log Teknisi</h2>
                    <p class="text-sm text-slate-600">
                        Admin memvalidasi aduan dan menugaskan teknisi terkait. Teknisi memperbarui log penanganan lengkap dengan foto bukti pengerjaan.
                    </p>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h2 class="text-base font-bold text-slate-900 mb-1">Riwayat & Pantau Laporan</h2>
                    <p class="text-sm text-slate-600">
                        Member terdaftar dapat membuka menu "Tiket Saya" untuk memantau timeline status penanganan tiket secara transparan hingga perbaikan tuntas.
                    </p>
                </div>
            </div>

            <!-- Demo Credentials Banner -->
            <div class="mt-10 p-5 rounded-xl bg-slate-900 text-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <span class="text-xs font-bold text-indigo-400 uppercase tracking-wider">Akun Uji Coba Tersedia (Fase 1)</span>
                    <p class="text-xs text-slate-300 mt-1">
                        Gunakan email demo berikut (password seluruh akun: <code class="px-1.5 py-0.5 rounded bg-slate-800 text-amber-300 font-mono">password</code>):
                    </p>
                </div>
                <div class="flex flex-wrap gap-2 text-xs font-mono">
                    <span class="px-2.5 py-1 rounded bg-slate-800 border border-slate-700 text-indigo-300">Admin: admin@silab.sch.id</span>
                    <span class="px-2.5 py-1 rounded bg-slate-800 border border-slate-700 text-amber-300">Teknisi: teknisi@silab.sch.id</span>
                    <span class="px-2.5 py-1 rounded bg-slate-800 border border-slate-700 text-emerald-300">Member: member@silab.sch.id</span>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="border-t border-slate-200 bg-white py-6">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-xs text-slate-500">
                &copy; {{ date('Y') }} SiLab — Sistem Informasi Pemeliharaan dan Pencatatan Aset Laboratorium Sekolah.
            </div>
        </footer>
    </body>
</html>
