<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <div class="flex items-center space-x-2">
                <a href="{{ route('member.tikets.index') }}" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-slate-900">Tiket {{ $tiket->nomor_tiket }}</h1>
                    <p class="text-xs text-slate-500">Timeline progres dan penanganan tiket pengaduan</p>
                </div>
            </div>
            <div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold
                    @if($tiket->status === 'Pending') bg-gray-100 text-gray-700 border border-gray-300
                    @elseif($tiket->status === 'Diproses') bg-blue-100 text-blue-800 border border-blue-200
                    @elseif($tiket->status === 'Menunggu Komponen/Sparepart') bg-yellow-100 text-yellow-800 border border-yellow-200
                    @elseif($tiket->status === 'Selesai') bg-emerald-100 text-emerald-800 border border-emerald-200
                    @elseif($tiket->status === 'Rusak Total') bg-rose-100 text-rose-800 border border-rose-200
                    @else bg-pink-100 text-pink-800 border border-pink-200 @endif">
                    {{ $tiket->status }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-sm text-emerald-800 flex items-center">
                <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left 2 Cols: Ticket Information & Damage Evidence -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Ticket Content Card -->
                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-xs space-y-4">
                    <div>
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Keluhan Kerusakan</span>
                        <h2 class="text-xl font-bold text-slate-900 mt-1">{{ $tiket->judul }}</h2>
                    </div>

                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 text-sm text-slate-700 leading-relaxed whitespace-pre-wrap">
                        {{ $tiket->deskripsi }}
                    </div>

                    <!-- Lampiran Foto Kerusakan (FR-13) -->
                    @if($tiket->fotos->count() > 0)
                        <div class="pt-2">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Foto Bukti Kerusakan Fisik</h3>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                @foreach($tiket->fotos as $foto)
                                    <a href="{{ $foto->foto_url }}" target="_blank" class="block group rounded-xl overflow-hidden border border-slate-200 aspect-square bg-slate-100 relative shadow-2xs">
                                        <img src="{{ $foto->foto_url }}" alt="Bukti Kerusakan" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200" />
                                        <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-medium">
                                            🔍 Perbesar
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Perangkat Aset Terkait -->
                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-xs">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-4">Informasi Perangkat Laboratorium</h3>
                    <div class="flex flex-col sm:flex-row gap-4 items-center">
                        <div class="w-20 h-20 bg-indigo-50 border border-indigo-100 rounded-xl flex items-center justify-center text-indigo-600 shrink-0">
                            @if($tiket->aset->foto_url)
                                <img src="{{ $tiket->aset->foto_url }}" alt="{{ $tiket->aset->nama }}" class="w-full h-full object-cover rounded-xl" />
                            @else
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0 text-center sm:text-left">
                            <span class="font-mono text-xs font-extrabold text-indigo-600 uppercase bg-indigo-50 px-2 py-0.5 rounded">
                                {{ $tiket->aset->kode_aset }}
                            </span>
                            <h4 class="text-base font-bold text-slate-900 mt-0.5">{{ $tiket->aset->nama }}</h4>
                            <p class="text-xs text-slate-600 mt-0.5">
                                {{ $tiket->aset->lab->nama }} (Ruang {{ $tiket->aset->lab->lokasi }}) • Kategori: {{ $tiket->aset->kategori->nama }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Col: Timeline & Handling Progress (FR-15 / PRD Section 11) -->
            <div class="space-y-6">
                <!-- Status & Petugas Card -->
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs space-y-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Penanganan Tiket</h3>
                    
                    <div class="text-xs space-y-2">
                        <div class="flex justify-between py-1 border-b border-slate-100">
                            <span class="text-slate-500">Teknisi Penanggung Jawab</span>
                            <span class="font-semibold text-slate-800">
                                {{ $tiket->teknisi ? ($tiket->teknisi->nama ?? $tiket->teknisi->name) : 'Belum Ditugaskan' }}
                            </span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-100">
                            <span class="text-slate-500">Prioritas</span>
                            <span class="font-semibold text-slate-800">{{ $tiket->prioritas ?? 'Menunggu Verifikasi Admin' }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-100">
                            <span class="text-slate-500">Waktu Pengajuan</span>
                            <span class="font-semibold text-slate-800">{{ $tiket->created_at->format('d-m-Y H:i') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Vertical Timeline of Logs -->
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-4">Timeline Progres Tiket</h3>

                    <div class="relative pl-6 space-y-6 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200">
                        @forelse($tiket->logs as $log)
                            <div class="relative group">
                                <!-- Dot Indicator -->
                                <div class="absolute -left-6 top-1 w-4 h-4 rounded-full border-2 border-white ring-2 ring-indigo-500 bg-indigo-600"></div>

                                <div class="space-y-1">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-slate-900">
                                            Status: {{ $log->status_ke }}
                                        </span>
                                        <span class="text-[10px] text-slate-400 font-mono">
                                            {{ $log->created_at->format('d/m H:i') }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-600 leading-relaxed bg-slate-50 p-2.5 rounded-lg border border-slate-100">
                                        {{ $log->catatan }}
                                    </p>
                                    @if($log->komponen_dibutuhkan)
                                        <p class="text-[11px] text-amber-700 font-medium">
                                            ⚠️ Komponen: {{ $log->komponen_dibutuhkan }}
                                        </p>
                                    @endif
                                    <div class="text-[10px] text-slate-400">
                                        Oleh: {{ $log->user ? ($log->user->nama ?? $log->user->name) : 'Sistem / Pelapor' }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400">Belum ada catatan log penanganan.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
