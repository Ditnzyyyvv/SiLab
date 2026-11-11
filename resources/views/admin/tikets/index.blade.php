<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-slate-900">Validasi & Disposisi Tiket Pengaduan</h1>
                <p class="text-xs text-slate-500 mt-0.5">Kelola tiket kerusakan aset baik dari pelapor tamu via QR Code maupun member lab</p>
            </div>
            <div class="flex items-center space-x-2">
                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                    <span class="w-2 h-2 rounded-full bg-indigo-500 mr-2 animate-pulse"></span>
                    {{ $statCounts['pending'] }} Tiket Menunggu Validasi
                </span>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Stat Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            <a href="{{ route('admin.tikets.index') }}" class="bg-white rounded-xl p-4 border border-slate-200 shadow-xs hover:border-indigo-400 transition-colors">
                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Total Tiket</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $statCounts['total'] }}</p>
            </a>
            <a href="{{ route('admin.tikets.index', ['status' => 'Pending']) }}" class="bg-white rounded-xl p-4 border border-amber-200 bg-amber-50/20 shadow-xs hover:border-amber-400 transition-colors">
                <p class="text-[11px] font-semibold uppercase tracking-wider text-amber-600">Pending</p>
                <p class="text-2xl font-bold text-amber-700 mt-1">{{ $statCounts['pending'] }}</p>
            </a>
            <a href="{{ route('admin.tikets.index', ['status' => 'Diproses']) }}" class="bg-white rounded-xl p-4 border border-blue-200 bg-blue-50/20 shadow-xs hover:border-blue-400 transition-colors">
                <p class="text-[11px] font-semibold uppercase tracking-wider text-blue-600">Ditangani</p>
                <p class="text-2xl font-bold text-blue-700 mt-1">{{ $statCounts['diproses'] }}</p>
            </a>
            <a href="{{ route('admin.tikets.index', ['status' => 'Selesai']) }}" class="bg-white rounded-xl p-4 border border-emerald-200 bg-emerald-50/20 shadow-xs hover:border-emerald-400 transition-colors">
                <p class="text-[11px] font-semibold uppercase tracking-wider text-emerald-600">Selesai</p>
                <p class="text-2xl font-bold text-emerald-700 mt-1">{{ $statCounts['selesai'] }}</p>
            </a>
            <a href="{{ route('admin.tikets.index', ['status' => 'Ditolak']) }}" class="bg-white rounded-xl p-4 border border-rose-200 bg-rose-50/20 shadow-xs hover:border-rose-400 transition-colors">
                <p class="text-[11px] font-semibold uppercase tracking-wider text-rose-600">Ditolak</p>
                <p class="text-2xl font-bold text-rose-700 mt-1">{{ $statCounts['ditolak'] }}</p>
            </a>
            <a href="{{ route('admin.tikets.index', ['tipe_pelapor' => 'guest']) }}" class="bg-white rounded-xl p-4 border border-purple-200 bg-purple-50/20 shadow-xs hover:border-purple-400 transition-colors">
                <p class="text-[11px] font-semibold uppercase tracking-wider text-purple-600">Scan QR (Tamu)</p>
                <p class="text-2xl font-bold text-purple-700 mt-1">{{ $statCounts['guest'] }}</p>
            </a>
        </div>

        <!-- Filter Bar -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-xs p-4">
            <form action="{{ route('admin.tikets.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
                <!-- Search Input -->
                <div class="lg:col-span-2">
                    <label class="block text-xs font-medium text-slate-600 mb-1">Cari Tiket</label>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="No tiket, keluhan, pelapor, aset..." 
                        class="w-full text-xs rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 placeholder-slate-400"
                    >
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Status</label>
                    <select name="status" class="w-full text-xs rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Semua Status</option>
                        <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Diproses" {{ request('status') === 'Diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="Menunggu Komponen/Sparepart" {{ request('status') === 'Menunggu Komponen/Sparepart' ? 'selected' : '' }}>Menunggu Komponen</option>
                        <option value="Selesai" {{ request('status') === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="Rusak Total" {{ request('status') === 'Rusak Total' ? 'selected' : '' }}>Rusak Total</option>
                        <option value="Ditolak" {{ request('status') === 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <!-- Prioritas Filter -->
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Prioritas</label>
                    <select name="prioritas" class="w-full text-xs rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Semua Prioritas</option>
                        <option value="Rendah" {{ request('prioritas') === 'Rendah' ? 'selected' : '' }}>Rendah</option>
                        <option value="Sedang" {{ request('prioritas') === 'Sedang' ? 'selected' : '' }}>Sedang</option>
                        <option value="Tinggi" {{ request('prioritas') === 'Tinggi' ? 'selected' : '' }}>Tinggi</option>
                    </select>
                </div>

                <!-- Lab Filter -->
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Laboratorium</label>
                    <select name="lab_id" class="w-full text-xs rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Semua Lab</option>
                        @foreach($labs as $l)
                            <option value="{{ $l->id }}" {{ request('lab_id') == $l->id ? 'selected' : '' }}>{{ $l->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tipe Pelapor Filter & Actions -->
                <div class="flex items-end space-x-2">
                    <div class="flex-1">
                        <label class="block text-xs font-medium text-slate-600 mb-1">Pelapor</label>
                        <select name="tipe_pelapor" class="w-full text-xs rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua Pelapor</option>
                            <option value="guest" {{ request('tipe_pelapor') === 'guest' ? 'selected' : '' }}>Tamu (QR)</option>
                            <option value="member" {{ request('tipe_pelapor') === 'member' ? 'selected' : '' }}>Member</option>
                        </select>
                    </div>
                    <button type="submit" class="px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-xs transition-colors">
                        Filter
                    </button>
                    @if(request()->anyFilled(['search', 'status', 'prioritas', 'lab_id', 'tipe_pelapor']))
                        <a href="{{ route('admin.tikets.index') }}" class="px-2.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-medium rounded-lg transition-colors" title="Reset Filter">
                            ↺
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700">
                    <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-semibold text-slate-500">
                        <tr>
                            <th class="px-5 py-3.5">Nomor Tiket & Tanggal</th>
                            <th class="px-5 py-3.5">Pelapor</th>
                            <th class="px-5 py-3.5">Perangkat & Lab</th>
                            <th class="px-5 py-3.5">Keluhan</th>
                            <th class="px-5 py-3.5">Prioritas</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5">Teknisi</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($tikets as $t)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <!-- Nomor & Tanggal -->
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <div class="font-mono font-bold text-indigo-600">
                                        <a href="{{ route('admin.tikets.show', $t) }}" class="hover:underline">
                                            {{ $t->nomor_tiket }}
                                        </a>
                                    </div>
                                    <div class="text-[11px] text-slate-400 mt-0.5">
                                        {{ $t->created_at->isoFormat('D MMM Y, HH:mm') }}
                                    </div>
                                </td>

                                <!-- Pelapor -->
                                <td class="px-5 py-3.5">
                                    <div class="font-medium text-slate-900 text-xs">{{ $t->nama_pelapor }}</div>
                                    <div class="mt-0.5">
                                        @if($t->isGuest())
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-purple-100 text-purple-800">
                                                Tamu (QR Scan)
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-emerald-100 text-emerald-800">
                                                Member Login
                                            </span>
                                        @endif
                                    </div>
                                    @if($t->kontak_pelapor)
                                        <div class="text-[11px] text-slate-400 mt-0.5 font-mono">{{ $t->kontak_pelapor }}</div>
                                    @endif
                                </td>

                                <!-- Aset & Lab -->
                                <td class="px-5 py-3.5">
                                    <div class="font-medium text-slate-900 text-xs">{{ $t->aset->nama }}</div>
                                    <div class="text-[11px] font-mono text-slate-400">{{ $t->aset->kode_aset }}</div>
                                    <div class="text-[11px] text-slate-500 mt-0.5">{{ $t->aset->lab->nama }}</div>
                                </td>

                                <!-- Keluhan -->
                                <td class="px-5 py-3.5 max-w-xs">
                                    <div class="font-medium text-slate-800 text-xs line-clamp-1">{{ $t->judul }}</div>
                                    <div class="text-[11px] text-slate-500 line-clamp-2 mt-0.5">{{ $t->deskripsi }}</div>
                                </td>

                                <!-- Prioritas -->
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    @if($t->prioritas === 'Tinggi')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-rose-100 text-rose-800">
                                            ● Tinggi
                                        </span>
                                    @elseif($t->prioritas === 'Sedang')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-amber-100 text-amber-800">
                                            ● Sedang
                                        </span>
                                    @elseif($t->prioritas === 'Rendah')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-slate-100 text-slate-700">
                                            ● Rendah
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400 italic">Belum Diatur</span>
                                    @endif
                                </td>

                                <!-- Status -->
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    @php
                                        $badgeClasses = match($t->status) {
                                            'Pending' => 'bg-amber-100 text-amber-800 border-amber-200',
                                            'Diproses' => 'bg-blue-100 text-blue-800 border-blue-200',
                                            'Menunggu Komponen/Sparepart' => 'bg-orange-100 text-orange-800 border-orange-200',
                                            'Selesai' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                            'Rusak Total' => 'bg-red-100 text-red-800 border-red-200',
                                            'Ditolak' => 'bg-rose-100 text-rose-800 border-rose-200',
                                            default => 'bg-slate-100 text-slate-700 border-slate-200'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold border {{ $badgeClasses }}">
                                        {{ $t->status }}
                                    </span>
                                </td>

                                <!-- Teknisi -->
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    @if($t->teknisi)
                                        <div class="text-xs font-medium text-slate-900">{{ $t->teknisi->nama }}</div>
                                        <div class="text-[10px] text-slate-400">Teknisi</div>
                                    @else
                                        <span class="text-xs text-slate-400 italic">Belum Ditugaskan</span>
                                    @endif
                                </td>

                                <!-- Aksi -->
                                <td class="px-5 py-3.5 whitespace-nowrap text-right text-xs">
                                    <a href="{{ route('admin.tikets.show', $t) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold rounded-lg transition-colors">
                                        Tindakan →
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                                    <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <p class="mt-2 text-sm font-medium text-slate-600">Tidak ada tiket pengaduan ditemukan</p>
                                    <p class="text-xs text-slate-400 mt-1">Coba sesuaikan kata kunci pencarian atau reset filter</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($tikets->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $tikets->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
