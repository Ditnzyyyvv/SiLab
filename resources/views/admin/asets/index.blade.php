<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between w-full gap-3">
            <div>
                <h1 class="text-xl font-bold text-slate-900">Inventaris Aset Laboratorium</h1>
                <p class="text-xs text-slate-500 mt-0.5">Kelola seluruh perangkat lab, cetak label stiker QR, dan pantau kondisi aset</p>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.asets.print-all-qr', request()->query()) }}" target="_blank" class="inline-flex items-center px-3.5 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-lg shadow-sm transition-colors">
                    <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak Massal QR
                </a>
                <a href="{{ route('admin.asets.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-sm transition-colors">
                    <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Aset
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-4">
        <!-- Notification Alerts -->
        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-sm text-emerald-800 flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-sm text-rose-800 flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- Filter & Search Panel (FR-09) -->
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
            <form method="GET" action="{{ route('admin.asets.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                <!-- Search text -->
                <div class="lg:col-span-2 relative">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="Cari kode aset, nama perangkat, atau merek..." 
                        class="w-full pl-9 pr-4 py-2 text-sm rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    />
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <!-- Filter Lab -->
                <div>
                    <select name="lab_id" onchange="this.form.submit()" class="w-full text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Lab</option>
                        @foreach($labs as $lab)
                            <option value="{{ $lab->id }}" {{ request('lab_id') == $lab->id ? 'selected' : '' }}>
                                {{ $lab->nama }} ({{ $lab->kode }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Kategori -->
                <div>
                    <select name="kategori_id" onchange="this.form.submit()" class="w-full text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Kondisi -->
                <div>
                    <select name="kondisi" onchange="this.form.submit()" class="w-full text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Kondisi</option>
                        @foreach($kondisiList as $k)
                            <option value="{{ $k }}" {{ request('kondisi') == $k ? 'selected' : '' }}>
                                {{ $k }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
            @if(request()->hasAny(['search', 'lab_id', 'kategori_id', 'kondisi']))
                <div class="mt-2 text-right">
                    <a href="{{ route('admin.asets.index') }}" class="text-xs text-slate-500 hover:text-slate-800 underline">Reset Semua Filter</a>
                </div>
            @endif
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700">
                    <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-semibold text-slate-500">
                        <tr>
                            <th class="px-5 py-3.5 w-16">Foto</th>
                            <th class="px-5 py-3.5">Kode Aset</th>
                            <th class="px-5 py-3.5">Nama & Merek</th>
                            <th class="px-5 py-3.5">Lab / Ruang</th>
                            <th class="px-5 py-3.5">Kategori</th>
                            <th class="px-5 py-3.5">Kondisi</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($asets as $aset)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="px-5 py-3">
                                    @if($aset->foto_url)
                                        <img src="{{ $aset->foto_url }}" alt="{{ $aset->nama }}" class="w-10 h-10 object-cover rounded-lg border border-slate-200" />
                                    @else
                                        <div class="w-10 h-10 bg-slate-100 text-slate-400 rounded-lg flex items-center justify-center border border-slate-200">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-5 py-3">
                                    <a href="{{ route('admin.asets.show', $aset) }}" class="font-mono font-bold text-indigo-600 hover:text-indigo-800 underline">
                                        {{ $aset->kode_aset }}
                                    </a>
                                </td>
                                <td class="px-5 py-3">
                                    <div class="font-medium text-slate-900">{{ $aset->nama }}</div>
                                    <div class="text-xs text-slate-500">{{ $aset->merek ?? 'Merek tidak dicatat' }}</div>
                                </td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-slate-100 text-slate-700">
                                        {{ $aset->lab->nama }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-slate-600 text-xs">
                                    {{ $aset->kategori->nama }}
                                </td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                        @if($aset->kondisi === 'Baik') bg-emerald-100 text-emerald-800
                                        @elseif($aset->kondisi === 'Rusak Ringan') bg-yellow-100 text-yellow-800
                                        @elseif($aset->kondisi === 'Rusak Berat') bg-rose-100 text-rose-800
                                        @elseif($aset->kondisi === 'Dalam Perbaikan') bg-blue-100 text-blue-800
                                        @else bg-gray-200 text-gray-800 @endif">
                                        {{ $aset->kondisi }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-right space-x-1">
                                    <!-- Print QR -->
                                    <a href="{{ route('admin.asets.print-qr', $aset) }}" target="_blank" class="inline-block p-1 text-slate-500 hover:text-slate-900 transition-colors" title="Cetak Stiker QR">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                        </svg>
                                    </a>
                                    <!-- Detail -->
                                    <a href="{{ route('admin.asets.show', $aset) }}" class="inline-block p-1 text-slate-500 hover:text-indigo-600 transition-colors" title="Detail Aset">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <!-- Edit -->
                                    <a href="{{ route('admin.asets.edit', $aset) }}" class="inline-block p-1 text-slate-500 hover:text-amber-600 transition-colors" title="Edit Aset">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <!-- Delete -->
                                    <form method="POST" action="{{ route('admin.asets.destroy', $aset) }}" class="inline-block" onsubmit="return confirm('Hapus aset {{ $aset->nama }} ({{ $aset->kode_aset }})?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 text-slate-500 hover:text-rose-600 transition-colors" title="Hapus Aset">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-12 text-center text-slate-500">
                                    Tidak ada data aset yang sesuai dengan kriteria pencarian.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($asets->hasPages())
                <div class="p-4 border-t border-slate-200">
                    {{ $asets->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

