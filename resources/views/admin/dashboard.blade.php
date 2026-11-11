<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2">
            <h1 class="text-xl font-bold text-slate-900">Dashboard Administrator</h1>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800">
                Admin
            </span>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Welcome Card -->
        <div class="bg-gradient-to-r from-indigo-700 to-indigo-900 rounded-2xl p-6 text-white shadow-lg shadow-indigo-900/10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold tracking-tight">Selamat Datang, {{ Auth::user()->nama ?? Auth::user()->name }}! 👋</h2>
                <p class="text-indigo-200 mt-1 text-sm max-w-xl">
                    Sistem SiLab siap digunakan untuk pemantauan inventaris lab sekolah, validasi tiket pengaduan, dan disposisi tugas teknisi.
                </p>
            </div>
            <div class="flex items-center space-x-2">
                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium bg-white/10 text-white backdrop-blur-sm border border-white/20">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 mr-2"></span> Sistem Aktif
                </span>
            </div>
        </div>

        <!-- Metric Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Total Aset -->
            <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Aset</p>
                        <p class="text-2xl font-bold text-slate-900 mt-1">0</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 text-xs text-slate-500">
                    Data master aset disiapkan pada <span class="font-semibold text-indigo-600">Fase 2</span>
                </div>
            </div>

            <!-- Tiket Pending -->
            <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Tiket Pending</p>
                        <p class="text-2xl font-bold text-slate-900 mt-1">0</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 text-xs text-slate-500">
                    Menunggu validasi admin (<span class="font-semibold text-slate-600">Fase 3 & 4</span>)
                </div>
            </div>

            <!-- Tiket Diproses -->
            <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Dalam Perbaikan</p>
                        <p class="text-2xl font-bold text-slate-900 mt-1">0</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 text-xs text-slate-500">
                    Sedang dikerjakan teknisi
                </div>
            </div>

            <!-- Perbaikan Selesai -->
            <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Selesai</p>
                        <p class="text-2xl font-bold text-slate-900 mt-1">0</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 text-xs text-slate-500">
                    Aset kembali berkondisi baik
                </div>
            </div>
        </div>

        <!-- System Architecture Card for Fase 1 -->
        <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-sm">
            <h3 class="text-base font-semibold text-slate-900 mb-2">Status Fondasi Sistem (Fase 1 Selesai)</h3>
            <p class="text-sm text-slate-600 mb-4">
                Autentikasi role-based access control (RBAC) telah aktif. Setiap role diarahkan ke ruang kerja masing-masing secara terproteksi.
            </p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
                <div class="p-4 rounded-lg bg-indigo-50/50 border border-indigo-100">
                    <span class="text-xs font-bold text-indigo-700 uppercase tracking-wider">Role Admin</span>
                    <p class="text-sm text-slate-700 mt-1 font-medium">admin@silab.sch.id</p>
                    <span class="inline-block mt-2 text-xs px-2 py-0.5 rounded bg-indigo-200/60 text-indigo-800">Akses Penuh Master Data</span>
                </div>
                <div class="p-4 rounded-lg bg-amber-50/50 border border-amber-100">
                    <span class="text-xs font-bold text-amber-700 uppercase tracking-wider">Role Teknisi</span>
                    <p class="text-sm text-slate-700 mt-1 font-medium">teknisi@silab.sch.id</p>
                    <span class="inline-block mt-2 text-xs px-2 py-0.5 rounded bg-amber-200/60 text-amber-800">Akses Pengerjaan Tugas</span>
                </div>
                <div class="p-4 rounded-lg bg-emerald-50/50 border border-emerald-100">
                    <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Role Member</span>
                    <p class="text-sm text-slate-700 mt-1 font-medium">member@silab.sch.id</p>
                    <span class="inline-block mt-2 text-xs px-2 py-0.5 rounded bg-emerald-200/60 text-emerald-800">Pelaporan & Konfirmasi</span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
