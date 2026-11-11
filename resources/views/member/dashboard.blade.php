<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2">
            <h1 class="text-xl font-bold text-slate-900">Dashboard Member</h1>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                Member
            </span>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Welcome Card -->
        <div class="bg-gradient-to-r from-emerald-600 to-teal-800 rounded-2xl p-6 text-white shadow-lg shadow-emerald-900/10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold tracking-tight">Selamat Datang, {{ Auth::user()->nama ?? Auth::user()->name }}! 📋</h2>
                <p class="text-emerald-100 mt-1 text-sm max-w-xl">
                    Sebagai member terdaftar, Anda dapat melihat seluruh riwayat tiket yang pernah Anda laporkan, memantau perkembangan penanganan secara real-time, dan mengonfirmasi hasil perbaikan.
                </p>
            </div>
            <div class="flex items-center space-x-2">
                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium bg-white/10 text-white backdrop-blur-sm border border-white/20">
                    <span class="w-2 h-2 rounded-full bg-emerald-300 mr-2"></span> Akun Terverifikasi
                </span>
            </div>
        </div>

        <!-- Metric Grid for Member (FR-27) -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <!-- Tiket Menunggu -->
            <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
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
                    Menunggu validasi admin lab
                </div>
            </div>

            <!-- Tiket Diproses -->
            <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Sedang Ditangani</p>
                        <p class="text-2xl font-bold text-slate-900 mt-1">0</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 text-xs text-slate-500">
                    Teknisi sedang memperbaiki aset
                </div>
            </div>

            <!-- Tiket Selesai -->
            <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Tiket Selesai</p>
                        <p class="text-2xl font-bold text-slate-900 mt-1">0</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 text-xs text-slate-500">
                    Perbaikan telah selesai & dikonfirmasi
                </div>
            </div>
        </div>

        <!-- Info Card: Guest vs Member Comparison -->
        <div class="bg-emerald-50/60 border border-emerald-200/80 rounded-xl p-6">
            <h3 class="text-base font-semibold text-emerald-950 mb-2">Informasi Akses & Alur Lapor Cepat (PRD v1.1)</h3>
            <p class="text-sm text-emerald-800 leading-relaxed mb-4">
                Sebagai member terdaftar yang login, Anda menikmati fitur eksklusif pemantauan seluruh riwayat tiket ("Tiket Saya"). Pengguna di lab yang tidak memiliki akun tetap dapat melapor cepat dengan memindai stiker QR Code pada aset fisik, namun laporan tersebut tidak disimpan di dashboard pribadi.
            </p>
            <div class="flex flex-wrap gap-3">
                <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-medium bg-white text-emerald-800 shadow-sm border border-emerald-200">
                    ✓ Rekapitulasi Riwayat Laporan
                </span>
                <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-medium bg-white text-emerald-800 shadow-sm border border-emerald-200">
                    ✓ Notifikasi Status Real-time
                </span>
                <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-medium bg-white text-emerald-800 shadow-sm border border-emerald-200">
                    ✓ Konfirmasi Selesai / Buka Kembali
                </span>
            </div>
        </div>
    </div>
</x-app-layout>
