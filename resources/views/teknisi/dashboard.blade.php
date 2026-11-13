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
                <a href="{{ route('teknisi.tugas.index') }}" class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold bg-white text-amber-800 shadow-sm hover:bg-amber-50 transition-colors">
                    Lihat Semua Tugas →
                </a>
            </div>
        </div>

        <!-- Metric Grid for Teknisi (FR-26) -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <!-- Tugas Aktif -->
            <a href="{{ route('teknisi.tugas.index', ['status' => 'Diproses']) }}" class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm hover:border-blue-400 transition-colors block">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Tugas Aktif (Diproses)</p>
                        <p class="text-2xl font-bold text-blue-600 mt-1">{{ $tugasAktifCount }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 text-xs text-slate-500">
                    Aset yang sedang dalam pengerjaan
                </div>
            </a>

            <!-- Menunggu Komponen / Sparepart -->
            <a href="{{ route('teknisi.tugas.index', ['status' => 'Menunggu Komponen/Sparepart']) }}" class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm hover:border-orange-400 transition-colors block">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Menunggu Sparepart</p>
                        <p class="text-2xl font-bold text-orange-600 mt-1">{{ $tugasSparepartCount }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 text-xs text-slate-500">
                    Menunggu ketersediaan suku cadang
                </div>
            </a>

            <!-- Selesai Bulan Ini -->
            <a href="{{ route('teknisi.tugas.index', ['status' => 'Selesai']) }}" class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm hover:border-emerald-400 transition-colors block">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Selesai Bulan Ini</p>
                        <p class="text-2xl font-bold text-emerald-600 mt-1">{{ $tugasSelesaiBulanIni }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 text-xs text-slate-500">
                    Perbaikan selesai pada bulan {{ now()->isoFormat('MMMM Y') }}
                </div>
            </a>
        </div>

        <!-- SECTION: CHART.JS UNTUK TEKNISI (FR-26) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Chart 1: Komposisi Status Tugas Saya -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-xs p-5">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Distribusi Status Tugas Saya</h3>
                        <p class="text-xs text-slate-500">Rasio penanganan tugas yang pernah dipercayakan</p>
                    </div>
                    <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-600">Semua Tugas</span>
                </div>
                <div class="mt-4 relative h-64 flex items-center justify-center">
                    <canvas id="teknisiStatusChart"></canvas>
                </div>
            </div>

            <!-- Chart 2: Tren Penyelesaian Tugas Bulanan -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-xs p-5">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Produktivitas Selesai Bulanan</h3>
                        <p class="text-xs text-slate-500">Jumlah aset yang berhasil diselesaikan per bulan</p>
                    </div>
                    <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-600">6 Bulan</span>
                </div>
                <div class="mt-4 relative h-64">
                    <canvas id="teknisiTrendChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Tabel Tugas Terbaru -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Tugas Perbaikan Terbaru</h3>
                    <p class="text-xs text-slate-500">Daftar penugasan perbaikan yang baru saja didisposisikan kepada Anda</p>
                </div>
                <a href="{{ route('teknisi.tugas.index') }}" class="text-xs font-semibold text-amber-700 hover:text-amber-900">
                    Lihat Semua Tugas →
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700">
                    <thead class="bg-slate-50 border-b border-slate-100 text-xs uppercase font-semibold text-slate-500">
                        <tr>
                            <th class="px-5 py-3">Nomor Tiket</th>
                            <th class="px-5 py-3">Perangkat</th>
                            <th class="px-5 py-3">Keluhan</th>
                            <th class="px-5 py-3">Prioritas</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentTugas as $item)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="px-5 py-3 font-mono font-bold text-amber-700">
                                    <a href="{{ route('teknisi.tugas.show', $item) }}" class="hover:underline">
                                        {{ $item->nomor_tiket }}
                                    </a>
                                </td>
                                <td class="px-5 py-3">
                                    <div class="font-medium text-slate-900 text-xs">{{ $item->aset->nama }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $item->aset->lab->nama }}</div>
                                </td>
                                <td class="px-5 py-3 font-medium text-slate-800 max-w-xs truncate text-xs">
                                    {{ $item->judul }}
                                </td>
                                <td class="px-5 py-3">
                                    @if($item->prioritas === 'Tinggi')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-rose-100 text-rose-800">
                                            ● Tinggi
                                        </span>
                                    @elseif($item->prioritas === 'Sedang')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-amber-100 text-amber-800">
                                            ● Sedang
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-slate-100 text-slate-700">
                                            ● Rendah
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3">
                                    @php
                                        $badgeClasses = match($item->status) {
                                            'Diproses' => 'bg-blue-100 text-blue-800',
                                            'Menunggu Komponen/Sparepart' => 'bg-orange-100 text-orange-800',
                                            'Selesai' => 'bg-emerald-100 text-emerald-800',
                                            'Rusak Total' => 'bg-red-100 text-red-800',
                                            default => 'bg-slate-100 text-slate-700'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold {{ $badgeClasses }}">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <a href="{{ route('teknisi.tugas.show', $item) }}" class="text-xs font-semibold text-blue-600 hover:underline">
                                        Update Progres →
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-8 text-center text-slate-400 text-xs">
                                    Belum ada tugas perbaikan yang ditugaskan kepada Anda.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Chart.js Initialization for Teknisi -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof window.Chart === 'undefined') return;

            // 1. Doughnut Chart Status Tugas
            const statusCtx = document.getElementById('teknisiStatusChart');
            if (statusCtx) {
                new window.Chart(statusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: @json(array_keys($tugasStatusCounts)),
                        datasets: [{
                            data: @json(array_values($tugasStatusCounts)),
                            backgroundColor: [
                                '#3b82f6', // Diproses
                                '#f97316', // Menunggu Sparepart
                                '#10b981', // Selesai
                                '#ef4444', // Rusak Total
                            ],
                            borderWidth: 2,
                            borderColor: '#ffffff',
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 11 } } }
                        },
                        cutout: '65%'
                    }
                });
            }

            // 2. Bar Chart Tren Selesai
            const trendCtx = document.getElementById('teknisiTrendChart');
            if (trendCtx) {
                new window.Chart(trendCtx, {
                    type: 'bar',
                    data: {
                        labels: @json($teknisiMonthlyChart['labels']),
                        datasets: [{
                            label: 'Tugas Selesai',
                            data: @json($teknisiMonthlyChart['selesai']),
                            backgroundColor: '#10b981',
                            borderRadius: 6,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { stepSize: 1, font: { size: 10 } },
                                grid: { color: '#f1f5f9' }
                            },
                            x: {
                                ticks: { font: { size: 10 } },
                                grid: { display: false }
                            }
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>
