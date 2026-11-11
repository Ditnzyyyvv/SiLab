<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2">
            <h1 class="text-xl font-bold text-slate-900">Dashboard Teknisi</h1>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                Teknisi
            </span>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Welcome Card -->
        <div class="bg-gradient-to-r from-amber-600 to-amber-800 rounded-2xl p-6 text-white shadow-lg shadow-amber-900/10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold tracking-tight">Halo, {{ Auth::user()->nama ?? Auth::user()->name }}! 🛠️</h2>
                <p class="text-amber-100 mt-1 text-sm max-w-xl">
                    Di panel ini Anda dapat memantau tugas perbaikan aset laboratorium yang ditugaskan, memperbarui log pengerjaan, dan mengunggah foto bukti.
                </p>
            </div>
            <div>
                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium bg-white/10 text-white backdrop-blur-sm border border-white/20">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 mr-2"></span> Siap Menerima Tugas
                </span>
            </div>
        </div>

        <!-- Metric Grid for Teknisi (FR-26) -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <!-- Tugas Aktif -->
            <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Tugas Aktif (Diproses)</p>
                        <p class="text-2xl font-bold text-slate-900 mt-1">0</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 text-xs text-slate-500">
                    Aset yang sedang dalam penanganan
                </div>
            </div>

            <!-- Menunggu Komponen / Sparepart -->
            <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Menunggu Sparepart</p>
                        <p class="text-2xl font-bold text-slate-900 mt-1">0</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 text-xs text-slate-500">
                    Menunggu pengadaan komponen
                </div>
            </div>

            <!-- Selesai Bulan Ini -->
            <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Selesai Bulan Ini</p>
                        <p class="text-2xl font-bold text-slate-900 mt-1">0</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 text-xs text-slate-500">
                    Perbaikan yang berhasil diselesaikan
                </div>
            </div>
        </div>

        <!-- Empty State Info -->
        <div class="bg-white rounded-xl p-8 border border-slate-200 text-center">
            <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 mx-auto flex items-center justify-center mb-3">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <h3 class="text-base font-medium text-slate-800">Belum Ada Tugas Perbaikan</h3>
            <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">
                Modul disposisi tiket dan pembaruan progres teknisi akan diintegrasikan pada Fase 4.
            </p>
        </div>
    </div>
</x-app-layout>

