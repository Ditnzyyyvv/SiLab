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
                <a href="{{ route('admin.tikets.index', ['status' => 'Pending']) }}" class="inline-flex items-center px-3.5 py-2 rounded-xl text-xs font-bold bg-white text-indigo-900 shadow-sm hover:bg-indigo-50 transition-colors">
                    Validasi Tiket ({{ $pendingTiketCount }}) →
                </a>
            </div>
        </div>

        <!-- Metric Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Aset -->
            <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-xs">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Aset</p>
                        <p class="text-2xl font-bold text-slate-900 mt-1">{{ $totalAset }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 text-xs text-slate-500">
                    Tersebar di <span class="font-semibold text-indigo-600">{{ $totalLab }} Lab</span> & <span class="font-semibold text-indigo-600">{{ $totalKategori }} Kategori</span>
                </div>
            </div>

            <!-- Tiket Menunggu Validasi -->
            <a href="{{ route('admin.tikets.index', ['status' => 'Pending']) }}" class="bg-white rounded-xl p-5 border border-amber-200 bg-amber-50/10 shadow-xs hover:border-amber-400 transition-colors block">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-amber-600">Tiket Pending</p>
                        <p class="text-2xl font-bold text-amber-700 mt-1">{{ $pendingTiketCount }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 text-xs text-amber-700 font-medium">
                    Perlu divalidasi & didisposisikan
                </div>
            </a>

            <!-- Sedang Ditangani -->
            <a href="{{ route('admin.tikets.index', ['status' => 'Diproses']) }}" class="bg-white rounded-xl p-5 border border-blue-200 bg-blue-50/10 shadow-xs hover:border-blue-400 transition-colors block">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-blue-600">Sedang Ditangani</p>
                        <p class="text-2xl font-bold text-blue-700 mt-1">{{ $diprosesTiketCount }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 text-xs text-blue-600">
                    Dalam pengerjaan oleh teknisi
                </div>
            </a>

            <!-- Selesai Diperbaiki -->
            <a href="{{ route('admin.tikets.index', ['status' => 'Selesai']) }}" class="bg-white rounded-xl p-5 border border-emerald-200 bg-emerald-50/10 shadow-xs hover:border-emerald-400 transition-colors block">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-emerald-600">Tiket Selesai</p>
                        <p class="text-2xl font-bold text-emerald-700 mt-1">{{ $selesaiTiketCount }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 text-xs text-emerald-600">
                    Aset kembali berkondisi baik
                </div>
            </a>
        </div>

        <!-- SECTION: CHART.JS ANALYTICS (FR-25) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Chart 1: Tren Bulanan Tiket Masuk vs Selesai -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-xs p-5">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Grafik Tiket Masuk dan Selesai</h3>
                        <p class="text-xs text-slate-500">Tren volume laporan kerusakan dalam 6 bulan terakhir</p>
                    </div>
                    <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-600">Bulanan</span>
                </div>
                <div class="mt-4 relative h-64">
                    <canvas id="monthlyTrendChart"></canvas>
                </div>
            </div>

            <!-- Chart 2: Kondisi Fisik Aset -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-xs p-5">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Distribusi Kondisi Aset</h3>
                        <p class="text-xs text-slate-500">Kondisi seluruh perangkat laboratorium saat ini</p>
                    </div>
                    <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-600">{{ $totalAset }} Aset</span>
                </div>
                <div class="mt-4 relative h-64 flex items-center justify-center">
                    <canvas id="kondisiAsetChart"></canvas>
                </div>
            </div>

            <!-- Chart 3: Proporsi Tiket Berdasarkan Status -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-xs p-5">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Komposisi Status Tiket</h3>
                        <p class="text-xs text-slate-500">Rasio seluruh tiket pengaduan kerusakan yang tercatat</p>
                    </div>
                    <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-600">Semua Waktu</span>
                </div>
                <div class="mt-4 relative h-64 flex items-center justify-center">
                    <canvas id="statusTiketChart"></canvas>
                </div>
            </div>

            <!-- Widget: Beban Kerja per Teknisi (FR-25) -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-xs p-5">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Beban Kerja Teknisi (FR-25)</h3>
                        <p class="text-xs text-slate-500">Jumlah tugas aktif dan tugas yang telah diselesaikan</p>
                    </div>
                    <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-600">{{ $teknisiWorkload->count() }} Teknisi</span>
                </div>
                <div class="mt-4 space-y-4">
                    @forelse($teknisiWorkload as $tek)
                        @php
                            $totalTugasTek = $tek->aktif_count + $tek->selesai_count;
                            $pctAktif = $totalTugasTek > 0 ? round(($tek->aktif_count / $totalTugasTek) * 100) : 0;
                        @endphp
                        <div class="p-3 rounded-lg bg-slate-50 border border-slate-100">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <div class="w-7 h-7 rounded-full bg-slate-800 text-white flex items-center justify-center text-xs font-bold">
                                        {{ strtoupper(substr($tek->nama, 0, 1)) }}
                                    </div>
                                    <span class="text-xs font-bold text-slate-900">{{ $tek->nama }}</span>
                                </div>
                                <div class="flex items-center space-x-3 text-xs">
                                    <span class="text-blue-700 font-semibold">{{ $tek->aktif_count }} Aktif</span>
                                    <span class="text-slate-300">•</span>
                                    <span class="text-emerald-700 font-semibold">{{ $tek->selesai_count }} Selesai</span>
                                </div>
                            </div>
                            <!-- Bar Progres Beban Kerja -->
                            <div class="w-full bg-slate-200 rounded-full h-1.5 mt-2.5 overflow-hidden flex">
                                <div class="bg-blue-600 h-1.5" style="width: {{ $pctAktif }}%" title="{{ $tek->aktif_count }} Aktif"></div>
                                <div class="bg-emerald-500 h-1.5" style="width: {{ 100 - $pctAktif }}%" title="{{ $tek->selesai_count }} Selesai"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 italic">Belum ada akun teknisi yang terdaftar.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- SECTION: TIKET PRIORITAS TINGGI YANG BELUM SELESAI (FR-25) -->
        @if($tiketPrioritasTinggi->isNotEmpty())
            <div class="bg-white rounded-xl border border-rose-200 shadow-xs overflow-hidden">
                <div class="p-4 bg-rose-50/70 border-b border-rose-100 flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500 animate-ping"></span>
                        <h3 class="text-sm font-bold text-rose-900">Perhatian: Tiket Prioritas Tinggi Belum Selesai (FR-25)</h3>
                    </div>
                    <span class="text-xs font-semibold px-2 py-0.5 rounded bg-rose-100 text-rose-800">
                        {{ $tiketPrioritasTinggi->count() }} Kasus Mendesak
                    </span>
                </div>
                <div class="divide-y divide-rose-100">
                    @foreach($tiketPrioritasTinggi as $hiTiket)
                        <div class="p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 hover:bg-rose-50/20 transition-colors">
                            <div class="space-y-1">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.tikets.show', $hiTiket) }}" class="font-mono font-bold text-xs text-rose-700 hover:underline">
                                        {{ $hiTiket->nomor_tiket }}
                                    </a>
                                    <span class="text-xs text-slate-400">•</span>
                                    <span class="text-xs font-semibold text-slate-800">{{ $hiTiket->aset->nama }}</span>
                                    <span class="text-xs text-slate-500">({{ $hiTiket->aset->lab->nama }})</span>
                                </div>
                                <p class="text-xs text-slate-600 line-clamp-1">{{ $hiTiket->judul }}</p>
                                <div class="text-[11px] text-slate-400">
                                    Status: <strong class="text-slate-700">{{ $hiTiket->status }}</strong> • 
                                    Teknisi: <strong class="text-slate-700">{{ $hiTiket->teknisi ? $hiTiket->teknisi->nama : 'Belum Ditugaskan' }}</strong>
                                </div>
                            </div>
                            <div class="shrink-0">
                                <a href="{{ route('admin.tikets.show', $hiTiket) }}" class="inline-flex items-center px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold rounded-lg shadow-2xs transition-colors">
                                    Tangani Sekarang →
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- SECTION: AKTIVITAS TERBARU (Tiket & Aset) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Tickets Table -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Tiket Pengaduan Terbaru</h3>
                        <p class="text-xs text-slate-500">Antrean laporan masuk dari tamu maupun member</p>
                    </div>
                    <a href="{{ route('admin.tikets.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                        Semua Tiket →
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-700">
                        <thead class="bg-slate-50 border-b border-slate-100 text-xs uppercase font-semibold text-slate-500">
                            <tr>
                                <th class="px-5 py-3">Tiket</th>
                                <th class="px-5 py-3">Pelapor</th>
                                <th class="px-5 py-3">Status</th>
                                <th class="px-5 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recentTikets as $t)
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <td class="px-5 py-3">
                                        <a href="{{ route('admin.tikets.show', $t) }}" class="font-mono font-bold text-indigo-600 hover:underline text-xs">
                                            {{ $t->nomor_tiket }}
                                        </a>
                                        <div class="text-[11px] text-slate-500 truncate max-w-[160px]">{{ $t->aset->nama }}</div>
                                    </td>
                                    <td class="px-5 py-3 text-xs">
                                        <div class="font-medium text-slate-900 truncate max-w-[120px]">{{ $t->nama_pelapor }}</div>
                                        <div class="text-[10px] text-slate-400">{{ $t->isGuest() ? 'Tamu' : 'Member' }}</div>
                                    </td>
                                    <td class="px-5 py-3">
                                        @php
                                            $stClasses = match($t->status) {
                                                'Pending' => 'bg-amber-100 text-amber-800',
                                                'Diproses' => 'bg-blue-100 text-blue-800',
                                                'Menunggu Komponen/Sparepart' => 'bg-orange-100 text-orange-800',
                                                'Selesai' => 'bg-emerald-100 text-emerald-800',
                                                'Rusak Total' => 'bg-red-100 text-red-800',
                                                'Ditolak' => 'bg-rose-100 text-rose-800',
                                                default => 'bg-slate-100 text-slate-700'
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold {{ $stClasses }}">
                                            {{ $t->status }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 text-right">
                                        <a href="{{ route('admin.tikets.show', $t) }}" class="text-xs font-semibold text-indigo-600 hover:underline">
                                            Tindakan →
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-6 text-center text-slate-400 text-xs">
                                        Belum ada tiket pengaduan masuk.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Assets Table -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Perangkat Aset Terbaru</h3>
                        <p class="text-xs text-slate-500">Master data aset laboratorium sekolah</p>
                    </div>
                    <a href="{{ route('admin.asets.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                        Semua Aset →
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-700">
                        <thead class="bg-slate-50 border-b border-slate-100 text-xs uppercase font-semibold text-slate-500">
                            <tr>
                                <th class="px-5 py-3">Kode Aset</th>
                                <th class="px-5 py-3">Nama Perangkat</th>
                                <th class="px-5 py-3">Kondisi</th>
                                <th class="px-5 py-3 text-right">Stiker</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recentAsets as $item)
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <td class="px-5 py-3 font-mono font-bold text-indigo-600 text-xs">
                                        <a href="{{ route('admin.asets.show', $item) }}" class="hover:underline">
                                            {{ $item->kode_aset }}
                                        </a>
                                    </td>
                                    <td class="px-5 py-3">
                                        <div class="font-medium text-slate-900 text-xs">{{ $item->nama }}</div>
                                        <div class="text-[10px] text-slate-400">{{ $item->lab->nama }}</div>
                                    </td>
                                    <td class="px-5 py-3">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold
                                            @if($item->kondisi === 'Baik') bg-emerald-100 text-emerald-800
                                            @elseif($item->kondisi === 'Dalam Perbaikan') bg-blue-100 text-blue-800
                                            @else bg-amber-100 text-amber-800 @endif">
                                            {{ $item->kondisi }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 text-right">
                                        <a href="{{ route('admin.asets.print-qr', $item) }}" target="_blank" class="inline-flex items-center px-2 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-medium rounded">
                                            🖨️ QR
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-6 text-center text-slate-400 text-xs">
                                        Belum ada aset terdaftar.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Scripts Initialization -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof window.Chart === 'undefined') return;

            // 1. Chart Tren Bulanan
            const monthlyCtx = document.getElementById('monthlyTrendChart');
            if (monthlyCtx) {
                new window.Chart(monthlyCtx, {
                    type: 'line',
                    data: {
                        labels: @json($monthlyChart['labels']),
                        datasets: [
                            {
                                label: 'Tiket Masuk',
                                data: @json($monthlyChart['masuk']),
                                borderColor: '#4f46e5',
                                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                                tension: 0.35,
                                fill: true,
                                borderWidth: 2,
                                pointRadius: 4,
                            },
                            {
                                label: 'Tiket Selesai',
                                data: @json($monthlyChart['selesai']),
                                borderColor: '#10b981',
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                tension: 0.35,
                                fill: true,
                                borderWidth: 2,
                                pointRadius: 4,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'top', labels: { boxWidth: 12, font: { size: 11 } } },
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

            // 2. Chart Kondisi Aset
            const kondisiCtx = document.getElementById('kondisiAsetChart');
            if (kondisiCtx) {
                new window.Chart(kondisiCtx, {
                    type: 'doughnut',
                    data: {
                        labels: @json(array_keys($kondisiCounts)),
                        datasets: [{
                            data: @json(array_values($kondisiCounts)),
                            backgroundColor: [
                                '#10b981', // Baik
                                '#f59e0b', // Rusak Ringan
                                '#f97316', // Rusak Berat
                                '#3b82f6', // Dalam Perbaikan
                                '#ef4444', // Tidak Layak Pakai
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

            // 3. Chart Status Tiket
            const statusCtx = document.getElementById('statusTiketChart');
            if (statusCtx) {
                new window.Chart(statusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: @json(array_keys($statusCounts)),
                        datasets: [{
                            data: @json(array_values($statusCounts)),
                            backgroundColor: [
                                '#fbbf24', // Pending
                                '#3b82f6', // Diproses
                                '#f97316', // Menunggu Sparepart
                                '#10b981', // Selesai
                                '#ef4444', // Rusak Total
                                '#f43f5e', // Ditolak
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
        });
    </script>
</x-app-layout>
