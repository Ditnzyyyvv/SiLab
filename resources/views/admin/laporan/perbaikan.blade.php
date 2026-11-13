<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between w-full gap-3">
            <div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('admin.laporan.index') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium inline-flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Pusat Laporan
                    </a>
                    <span class="text-slate-300">/</span>
                    <span class="text-xs text-slate-500 font-semibold uppercase tracking-wider">FR-30</span>
                </div>
                <h1 class="text-xl font-bold text-slate-900 mt-1">Laporan Pemeliharaan & Perbaikan Aset</h1>
                <p class="text-xs text-slate-500 mt-0.5">Riwayat penanganan tiket kerusakan, kinerja teknisi, serta rekapitulasi status</p>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.laporan.perbaikan.pdf', request()->query()) }}" class="inline-flex items-center px-3.5 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold rounded-lg shadow-sm transition-colors">
                    <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Unduh PDF
                </a>
                <a href="{{ route('admin.laporan.perbaikan.excel', request()->query()) }}" class="inline-flex items-center px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg shadow-sm transition-colors">
                    <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Unduh Excel (CSV)
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Rekapitulasi Ringkasan KPI Status -->
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <div class="text-[11px] font-semibold text-slate-500 uppercase">Total Tiket</div>
                <div class="text-2xl font-bold text-slate-900 mt-1">{{ $rekapStatus['total'] }}</div>
                <div class="text-[11px] text-slate-400 mt-0.5">Sesuai Filter</div>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <div class="text-[11px] font-semibold text-emerald-600 uppercase">Perbaikan Selesai</div>
                <div class="text-2xl font-bold text-emerald-700 mt-1">{{ $rekapStatus['selesai'] }}</div>
                <div class="text-[11px] text-slate-400 mt-0.5">Tuntas Diperbaiki</div>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <div class="text-[11px] font-semibold text-blue-600 uppercase">Sedang Diproses</div>
                <div class="text-2xl font-bold text-blue-700 mt-1">{{ $rekapStatus['proses'] }}</div>
                <div class="text-[11px] text-slate-400 mt-0.5">Pengerjaan / Sparepart</div>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <div class="text-[11px] font-semibold text-amber-600 uppercase">Menunggu Validasi</div>
                <div class="text-2xl font-bold text-amber-700 mt-1">{{ $rekapStatus['pending'] }}</div>
                <div class="text-[11px] text-slate-400 mt-0.5">Status Pending</div>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-4 col-span-2 sm:col-span-1">
                <div class="text-[11px] font-semibold text-rose-600 uppercase">Rusak Total / Afkir</div>
                <div class="text-2xl font-bold text-rose-700 mt-1">{{ $rekapStatus['rusak_total'] }}</div>
                <div class="text-[11px] text-slate-400 mt-0.5">Tidak Dapat Ditolong</div>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-xs p-4">
            <form method="GET" action="{{ route('admin.laporan.perbaikan') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
                <!-- Periode Mulai -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Mulai</label>
                    <input 
                        type="date" 
                        name="tanggal_mulai" 
                        value="{{ request('tanggal_mulai') }}" 
                        class="w-full text-xs rounded-lg border-slate-300 focus:ring-indigo-500 focus:border-indigo-500"
                    />
                </div>

                <!-- Periode Selesai -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Selesai</label>
                    <input 
                        type="date" 
                        name="tanggal_selesai" 
                        value="{{ request('tanggal_selesai') }}" 
                        class="w-full text-xs rounded-lg border-slate-300 focus:ring-indigo-500 focus:border-indigo-500"
                    />
                </div>

                <!-- Filter Lab -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Laboratorium</label>
                    <select name="lab_id" class="w-full text-xs rounded-lg border-slate-300 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Lab</option>
                        @foreach($labs as $lab)
                            <option value="{{ $lab->id }}" {{ request('lab_id') == $lab->id ? 'selected' : '' }}>
                                {{ $lab->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Teknisi -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Teknisi</label>
                    <select name="teknisi_id" class="w-full text-xs rounded-lg border-slate-300 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Teknisi</option>
                        @foreach($teknisis as $tek)
                            <option value="{{ $tek->id }}" {{ request('teknisi_id') == $tek->id ? 'selected' : '' }}>
                                {{ $tek->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Status -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Status Tiket</label>
                    <select name="status" class="w-full text-xs rounded-lg border-slate-300 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Status</option>
                        @foreach($statusList as $st)
                            <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>
                                {{ $st }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Prioritas & Tombol -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Prioritas</label>
                    <select name="prioritas" class="w-full text-xs rounded-lg border-slate-300 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Prioritas</option>
                        @foreach($prioritasList as $pr)
                            <option value="{{ $pr }}" {{ request('prioritas') == $pr ? 'selected' : '' }}>
                                {{ $pr }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2 lg:col-span-6 flex justify-end space-x-2 pt-2 border-t border-slate-100">
                    <a href="{{ route('admin.laporan.perbaikan') }}" class="py-2 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-medium rounded-lg transition-colors">
                        Reset Filter
                    </a>
                    <button type="submit" class="py-2 px-5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-xs transition-colors">
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel Pratinjau Laporan Perbaikan -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Daftar Tiket Perbaikan Terfilter ({{ $tikets->total() }} Data)</h2>
                    <p class="text-xs text-slate-500">Menampilkan hasil pencarian sesuai kriteria filter di atas</p>
                </div>
                <div class="text-xs text-slate-500">
                    Halaman {{ $tikets->currentPage() }} dari {{ $tikets->lastPage() }}
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-3 text-center w-12">No</th>
                            <th class="px-4 py-3">No Tiket</th>
                            <th class="px-4 py-3">Tanggal Lapor</th>
                            <th class="px-4 py-3">Pelapor</th>
                            <th class="px-4 py-3">Perangkat / Lab</th>
                            <th class="px-4 py-3">Keluhan</th>
                            <th class="px-4 py-3 text-center">Prioritas</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3">Teknisi</th>
                            <th class="px-4 py-3 text-center">Tgl Selesai</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($tikets as $index => $t)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="px-4 py-3 text-center font-medium text-slate-400">
                                    {{ $tikets->firstItem() + $index }}
                                </td>
                                <td class="px-4 py-3 font-mono font-bold text-indigo-700">
                                    <a href="{{ route('admin.tikets.show', $t) }}" class="hover:underline">
                                        {{ $t->nomor_tiket }}
                                    </a>
                                </td>
                                <td class="px-4 py-3 text-slate-600 whitespace-nowrap">
                                    {{ $t->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-semibold text-slate-900">{{ $t->nama_pelapor }}</div>
                                    <div class="text-[11px] text-slate-400">
                                        {{ $t->isGuest() ? 'Tamu (QR Scan)' : 'Member Login' }}
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-semibold text-slate-800">{{ $t->aset->nama ?? '-' }}</div>
                                    <div class="text-[11px] font-mono text-indigo-600">{{ $t->aset->kode_aset ?? '-' }} ({{ $t->aset->lab->nama ?? '-' }})</div>
                                </td>
                                <td class="px-4 py-3 max-w-xs">
                                    <div class="font-medium text-slate-900 truncate">{{ $t->judul }}</div>
                                    <div class="text-[11px] text-slate-400 truncate">{{ $t->deskripsi }}</div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @php
                                        $prioClass = match($t->prioritas) {
                                            'Tinggi' => 'bg-rose-50 text-rose-700 border-rose-200',
                                            'Sedang' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'Rendah' => 'bg-slate-100 text-slate-700 border-slate-200',
                                            default => 'bg-slate-50 text-slate-500 border-slate-200'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold border {{ $prioClass }}">
                                        {{ $t->prioritas ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @php
                                        $statusClass = match($t->status) {
                                            'Pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'Diproses' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'Menunggu Komponen/Sparepart' => 'bg-purple-50 text-purple-700 border-purple-200',
                                            'Selesai' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'Rusak Total' => 'bg-rose-50 text-rose-700 border-rose-200',
                                            'Ditolak' => 'bg-slate-100 text-slate-700 border-slate-300',
                                            default => 'bg-slate-50 text-slate-600 border-slate-200'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold border {{ $statusClass }}">
                                        {{ $t->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-slate-700 whitespace-nowrap">
                                    {{ $t->teknisi->nama ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-center text-slate-500 whitespace-nowrap">
                                    {{ $t->tanggal_selesai ? $t->tanggal_selesai->format('d/m/Y H:i') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-4 py-12 text-center text-slate-400">
                                    <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="font-medium text-slate-600">Tidak ada tiket perbaikan yang cocok dengan filter</p>
                                    <p class="text-xs text-slate-400 mt-1">Coba sesuaikan tanggal periode atau parameter filter lainnya</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($tikets->hasPages())
                <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $tikets->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
