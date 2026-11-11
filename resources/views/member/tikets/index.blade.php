<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between w-full gap-3">
            <div>
                <h1 class="text-xl font-bold text-slate-900">Tiket Saya (Riwayat Laporan)</h1>
                <p class="text-xs text-slate-500 mt-0.5">Pantau status dan tahapan pengerjaan perbaikan aset yang Anda laporkan</p>
            </div>
            <a href="{{ route('member.tikets.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg shadow-sm transition-colors">
                <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Buat Tiket Baru
            </a>
        </div>
    </x-slot>

    <div class="space-y-4">
        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-sm text-emerald-800 flex items-center">
                <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Filter & Search Bar -->
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <form method="GET" action="{{ route('member.tikets.index') }}" class="flex-1 flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1 max-w-md">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="Cari nomor tiket, judul, atau nama perangkat..." 
                        class="w-full pl-9 pr-4 py-2 text-sm rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                    />
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <div class="w-full sm:w-56">
                    <select name="status" onchange="this.form.submit()" class="w-full text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">Semua Status</option>
                        @foreach($statusList as $st)
                            <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>
                                {{ $st }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('member.tikets.index') }}" class="text-xs text-slate-500 hover:text-slate-800 underline">Reset Filter</a>
            @endif
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700">
                    <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-semibold text-slate-500">
                        <tr>
                            <th class="px-5 py-3.5">Nomor Tiket</th>
                            <th class="px-5 py-3.5">Perangkat & Lab</th>
                            <th class="px-5 py-3.5">Judul Kerusakan</th>
                            <th class="px-5 py-3.5">Status Tiket</th>
                            <th class="px-5 py-3.5">Waktu Lapor</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($tikets as $item)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="px-5 py-3.5 font-mono font-bold text-emerald-700">
                                    <a href="{{ route('member.tikets.show', $item) }}" class="hover:underline">
                                        {{ $item->nomor_tiket }}
                                    </a>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="font-medium text-slate-900">{{ $item->aset->nama }}</div>
                                    <div class="text-xs text-slate-500">{{ $item->aset->lab->nama }} ({{ $item->aset->kode_aset }})</div>
                                </td>
                                <td class="px-5 py-3.5 max-w-xs truncate font-medium text-slate-800">
                                    {{ $item->judul }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                        @if($item->status === 'Pending') bg-gray-100 text-gray-700 border border-gray-300
                                        @elseif($item->status === 'Diproses') bg-blue-100 text-blue-800 border border-blue-200
                                        @elseif($item->status === 'Menunggu Komponen/Sparepart') bg-yellow-100 text-yellow-800 border border-yellow-200
                                        @elseif($item->status === 'Selesai') bg-emerald-100 text-emerald-800 border border-emerald-200
                                        @elseif($item->status === 'Rusak Total') bg-rose-100 text-rose-800 border border-rose-200
                                        @else bg-pink-100 text-pink-800 border border-pink-200 @endif">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-xs text-slate-500">
                                    {{ $item->created_at->format('d-m-Y H:i') }}
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <a href="{{ route('member.tikets.show', $item) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors">
                                        Lihat Timeline →
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center text-slate-500">
                                    <p class="font-medium">Belum ada riwayat tiket pengaduan.</p>
                                    <p class="text-xs text-slate-400 mt-1">Jika ada perangkat laboratorium yang bermasalah, silakan buat tiket baru atau scan QR pada perangkat.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($tikets->hasPages())
                <div class="p-4 border-t border-slate-200">
                    {{ $tikets->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
