<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-slate-900">Daftar Tugas Perbaikan Saya</h1>
                <p class="text-xs text-slate-500 mt-0.5">Pantau dan perbarui status penanganan perbaikan aset laboratorium yang ditugaskan</p>
            </div>
            <div class="flex items-center space-x-2">
                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                    <span class="w-2 h-2 rounded-full bg-blue-500 mr-2 animate-pulse"></span>
                    {{ $statCounts['aktif'] }} Tugas Aktif Sedang Berjalan
                </span>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Stat Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <a href="{{ route('teknisi.tugas.index', ['status' => 'Diproses']) }}" class="bg-white rounded-xl p-4 border border-blue-200 bg-blue-50/20 shadow-xs hover:border-blue-400 transition-colors">
                <p class="text-xs font-semibold uppercase tracking-wider text-blue-600">Sedang Ditangani</p>
                <p class="text-2xl font-bold text-blue-700 mt-1">{{ $statCounts['aktif'] }}</p>
            </a>
            <a href="{{ route('teknisi.tugas.index', ['status' => 'Menunggu Komponen/Sparepart']) }}" class="bg-white rounded-xl p-4 border border-orange-200 bg-orange-50/20 shadow-xs hover:border-orange-400 transition-colors">
                <p class="text-xs font-semibold uppercase tracking-wider text-orange-600">Menunggu Sparepart</p>
                <p class="text-2xl font-bold text-orange-700 mt-1">{{ $statCounts['sparepart'] }}</p>
            </a>
            <a href="{{ route('teknisi.tugas.index', ['status' => 'Selesai']) }}" class="bg-white rounded-xl p-4 border border-emerald-200 bg-emerald-50/20 shadow-xs hover:border-emerald-400 transition-colors">
                <p class="text-xs font-semibold uppercase tracking-wider text-emerald-600">Selesai</p>
                <p class="text-2xl font-bold text-emerald-700 mt-1">{{ $statCounts['selesai'] }}</p>
            </a>
            <a href="{{ route('teknisi.tugas.index') }}" class="bg-white rounded-xl p-4 border border-slate-200 shadow-xs hover:border-indigo-400 transition-colors">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Tugas</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $statCounts['total'] }}</p>
            </a>
        </div>

        <!-- Filter Bar -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-xs p-4">
            <form action="{{ route('teknisi.tugas.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-medium text-slate-600 mb-1">Cari Tugas</label>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="Nomor tiket, nama perangkat, keluhan..." 
                        class="w-full text-xs rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                    >
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Status</label>
                    <select name="status" class="w-full text-xs rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Semua Status</option>
                        <option value="Diproses" {{ request('status') === 'Diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="Menunggu Komponen/Sparepart" {{ request('status') === 'Menunggu Komponen/Sparepart' ? 'selected' : '' }}>Menunggu Sparepart</option>
                        <option value="Selesai" {{ request('status') === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="Rusak Total" {{ request('status') === 'Rusak Total' ? 'selected' : '' }}>Rusak Total</option>
                    </select>
                </div>
                <div class="flex items-end space-x-2">
                    <div class="flex-1">
                        <label class="block text-xs font-medium text-slate-600 mb-1">Prioritas</label>
                        <select name="prioritas" class="w-full text-xs rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Semua Prioritas</option>
                            <option value="Tinggi" {{ request('prioritas') === 'Tinggi' ? 'selected' : '' }}>Tinggi</option>
                            <option value="Sedang" {{ request('prioritas') === 'Sedang' ? 'selected' : '' }}>Sedang</option>
                            <option value="Rendah" {{ request('prioritas') === 'Rendah' ? 'selected' : '' }}>Rendah</option>
                        </select>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg shadow-xs transition-colors">
                        Filter
                    </button>
                    @if(request()->anyFilled(['search', 'status', 'prioritas']))
                        <a href="{{ route('teknisi.tugas.index') }}" class="px-2.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-medium rounded-lg transition-colors" title="Reset Filter">
                            ↺
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Tabel Tugas -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700">
                    <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-semibold text-slate-500">
                        <tr>
                            <th class="px-5 py-3.5">Nomor Tiket</th>
                            <th class="px-5 py-3.5">Perangkat & Lokasi</th>
                            <th class="px-5 py-3.5">Keluhan</th>
                            <th class="px-5 py-3.5">Prioritas</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5">Disposisi</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($tugas as $item)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <a href="{{ route('teknisi.tugas.show', $item) }}" class="font-mono font-bold text-blue-600 hover:underline">
                                        {{ $item->nomor_tiket }}
                                    </a>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="font-medium text-slate-900 text-xs">{{ $item->aset->nama }}</div>
                                    <div class="text-[11px] font-mono text-slate-400">{{ $item->aset->kode_aset }}</div>
                                    <div class="text-[11px] text-slate-500 mt-0.5">{{ $item->aset->lab->nama }}</div>
                                </td>
                                <td class="px-5 py-3.5 max-w-xs">
                                    <div class="font-medium text-slate-800 text-xs line-clamp-1">{{ $item->judul }}</div>
                                    <div class="text-[11px] text-slate-500 line-clamp-2 mt-0.5">{{ $item->deskripsi }}</div>
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
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
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    @php
                                        $badgeClasses = match($item->status) {
                                            'Diproses' => 'bg-blue-100 text-blue-800 border-blue-200',
                                            'Menunggu Komponen/Sparepart' => 'bg-orange-100 text-orange-800 border-orange-200',
                                            'Selesai' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                            'Rusak Total' => 'bg-red-100 text-red-800 border-red-200',
                                            default => 'bg-slate-100 text-slate-700 border-slate-200'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold border {{ $badgeClasses }}">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap text-xs text-slate-500">
                                    {{ $item->tanggal_disposisi ? $item->tanggal_disposisi->isoFormat('D MMM Y') : '-' }}
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap text-right text-xs">
                                    <a href="{{ route('teknisi.tugas.show', $item) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 font-semibold rounded-lg transition-colors">
                                        Update Progres →
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                    <p class="text-sm font-medium text-slate-600">Tidak ada tugas perbaikan aktif yang ditugaskan</p>
                                    <p class="text-xs text-slate-400 mt-1">Tugas baru dari disposisi Admin akan muncul di sini</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($tugas->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $tugas->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
