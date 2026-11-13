<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2">
            <h1 class="text-xl font-bold text-slate-900">Pusat Laporan & Rekapitulasi (FR-29, FR-30)</h1>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800">
                Admin Ekspor
            </span>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Banner Info -->
        <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-2xl p-6 text-white shadow-lg flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold">Modul Rekapitulasi & Ekspor Dokumen Resmi</h2>
                <p class="text-slate-300 mt-1 text-xs max-w-2xl leading-relaxed">
                    Saring data inventaris dan riwayat perbaikan laboratorium berdasarkan kebutuhan audit, lalu unduh dalam format PDF siap cetak (lengkap dengan kop sekolah resmi) atau format Excel/Spreadsheet untuk pengolahan lebih lanjut.
                </p>
            </div>
            <div class="flex items-center space-x-2 shrink-0">
                <span class="px-3 py-1.5 rounded-lg text-xs font-medium bg-white/10 text-white border border-white/20">
                    Format: PDF & Excel (CSV UTF-8)
                </span>
            </div>
        </div>

        <!-- 2 Main Report Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Card 1: Laporan Aset (FR-29) -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 flex flex-col justify-between hover:border-indigo-400 transition-colors">
                <div>
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700">
                            FR-29
                        </span>
                    </div>

                    <div class="mt-4">
                        <h3 class="text-base font-bold text-slate-900">Laporan Inventaris Aset Laboratorium</h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                            Rekapitulasi seluruh perangkat laboratorium sekolah. Dilengkapi filter berdasarkan laboratorium ruangan, kategori aset, dan kondisi fisik (Baik, Rusak Ringan, Rusak Berat, Dalam Perbaikan, Tidak Layak Pakai).
                        </p>
                    </div>

                    <div class="mt-5 p-3 rounded-xl bg-slate-50 border border-slate-100 text-xs text-slate-600 space-y-1.5">
                        <div class="flex justify-between">
                            <span>Total Aset Terdaftar:</span>
                            <span class="font-bold text-slate-900">{{ $totalAset }} Unit</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Kelengkapan Data:</span>
                            <span class="font-semibold text-emerald-700">Kode Aset, Spesifikasi, Merek, Kondisi</span>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-100 space-y-2">
                    <a href="{{ route('admin.laporan.aset') }}" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl text-center block shadow-xs transition-colors">
                        Buka Pratinjau & Filter Data →
                    </a>
                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('admin.laporan.aset.pdf') }}" class="py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-medium rounded-lg text-center transition-colors">
                            📄 Unduh PDF
                        </a>
                        <a href="{{ route('admin.laporan.aset.excel') }}" class="py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-medium rounded-lg text-center transition-colors">
                            📊 Unduh Excel
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card 2: Laporan Perbaikan (FR-30) -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 flex flex-col justify-between hover:border-blue-400 transition-colors">
                <div>
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-blue-50 text-blue-700">
                            FR-30
                        </span>
                    </div>

                    <div class="mt-4">
                        <h3 class="text-base font-bold text-slate-900">Laporan Pemeliharaan & Perbaikan</h3>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                            Rekapitulasi riwayat penanganan tiket pengaduan kerusakan. Dilengkapi filter rentang tanggal (periode), laboratorium, teknisi penanggung jawab, prioritas, dan status penanganan akhir.
                        </p>
                    </div>

                    <div class="mt-5 p-3 rounded-xl bg-slate-50 border border-slate-100 text-xs text-slate-600 space-y-1.5">
                        <div class="flex justify-between">
                            <span>Total Pengaduan Masuk:</span>
                            <span class="font-bold text-slate-900">{{ $totalPerbaikan }} Tiket</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Berhasil Diselesaikan:</span>
                            <span class="font-semibold text-emerald-700">{{ $perbaikanSelesai }} Selesai</span>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-100 space-y-2">
                    <a href="{{ route('admin.laporan.perbaikan') }}" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl text-center block shadow-xs transition-colors">
                        Buka Pratinjau & Filter Data →
                    </a>
                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('admin.laporan.perbaikan.pdf') }}" class="py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-medium rounded-lg text-center transition-colors">
                            📄 Unduh PDF
                        </a>
                        <a href="{{ route('admin.laporan.perbaikan.excel') }}" class="py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-medium rounded-lg text-center transition-colors">
                            📊 Unduh Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
