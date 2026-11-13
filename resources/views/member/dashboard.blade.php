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
                <a href="{{ route('member.tikets.create') }}" class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold bg-white text-emerald-800 shadow-sm hover:bg-emerald-50 transition-colors">
                    + Buat Tiket Baru
                </a>
            </div>
        </div>

        <!-- Metric Grid for Member (FR-27) -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <!-- Tiket Menunggu -->
            <a href="{{ route('member.tikets.index', ['status' => 'Pending']) }}" class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm hover:border-amber-400 transition-colors block">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Tiket Pending</p>
                        <p class="text-2xl font-bold text-slate-900 mt-1">{{ $pendingCount }}</p>
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
            </a>

            <!-- Tiket Diproses -->
            <a href="{{ route('member.tikets.index', ['status' => 'Diproses']) }}" class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm hover:border-blue-400 transition-colors block">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Sedang Ditangani</p>
                        <p class="text-2xl font-bold text-blue-600 mt-1">{{ $diprosesCount }}</p>
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
            </a>

            <!-- Tiket Selesai -->
            <a href="{{ route('member.tikets.index', ['status' => 'Selesai']) }}" class="bg-white rounded-xl p-5 border border-slate-200 shadow-sm hover:border-emerald-400 transition-colors block">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Tiket Selesai</p>
                        <p class="text-2xl font-bold text-emerald-600 mt-1">{{ $selesaiCount }}</p>
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
            </a>
        </div>

        <!-- SECTION: CHART & RECENT TICKETS (FR-27) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Chart Proporsi Tiket Member (1 Col) -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-xs p-5">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Proporsi Status Tiket Saya</h3>
                        <p class="text-xs text-slate-500">Rasio status seluruh laporan Anda</p>
                    </div>
                    <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-600">{{ $totalTiketSaya }} Tiket</span>
                </div>
                <div class="mt-4 relative h-64 flex items-center justify-center">
                    @if($totalTiketSaya > 0)
                        <canvas id="memberStatusChart"></canvas>
                    @else
                        <div class="text-center text-slate-400 text-xs">
                            <p>Belum ada tiket pengaduan</p>
                            <p class="text-[11px] mt-1">Grafik akan tampil saat Anda membuat tiket.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Tickets Table for Member (2 Cols) -->
            <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Laporan Kerusakan Terbaru Saya</h3>
                        <p class="text-xs text-slate-500">Pantau progres penanganan tiket yang baru saja Anda ajukan</p>
                    </div>
                    <div class="space-x-2">
                        <a href="{{ route('member.tikets.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-800">
                            Semua Tiket Saya →
                        </a>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-700">
                        <thead class="bg-slate-50 border-b border-slate-100 text-xs uppercase font-semibold text-slate-500">
                            <tr>
                                <th class="px-5 py-3">Nomor Tiket</th>
                                <th class="px-5 py-3">Perangkat</th>
                                <th class="px-5 py-3">Keluhan</th>
                                <th class="px-5 py-3">Status</th>
                                <th class="px-5 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recentTikets as $item)
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <td class="px-5 py-3 font-mono font-bold text-emerald-700">
                                        <a href="{{ route('member.tikets.show', $item) }}" class="hover:underline">
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
                                        @php
                                            $stBadge = match($item->status) {
                                                'Pending' => 'bg-gray-100 text-gray-700',
                                                'Diproses' => 'bg-blue-100 text-blue-800',
                                                'Menunggu Komponen/Sparepart' => 'bg-orange-100 text-orange-800',
                                                'Selesai' => 'bg-emerald-100 text-emerald-800',
                                                'Rusak Total' => 'bg-red-100 text-red-800',
                                                'Ditolak' => 'bg-rose-100 text-rose-800',
                                                default => 'bg-slate-100 text-slate-700'
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold {{ $stBadge }}">
                                            {{ $item->status }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 text-right">
                                        <a href="{{ route('member.tikets.show', $item) }}" class="text-xs font-semibold text-emerald-600 hover:underline">
                                            Timeline →
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-8 text-center text-slate-400 text-xs">
                                        Belum ada tiket yang pernah Anda laporkan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
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

    <!-- Chart.js Initialization for Member -->
    @if($totalTiketSaya > 0)
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (typeof window.Chart === 'undefined') return;

                const memberCtx = document.getElementById('memberStatusChart');
                if (memberCtx) {
                    new window.Chart(memberCtx, {
                        type: 'doughnut',
                        data: {
                            labels: @json(array_keys($memberStatusCounts)),
                            datasets: [{
                                data: @json(array_values($memberStatusCounts)),
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
                                legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } }
                            },
                            cutout: '65%'
                        }
                    });
                }
            });
        </script>
    @endif
</x-app-layout>
