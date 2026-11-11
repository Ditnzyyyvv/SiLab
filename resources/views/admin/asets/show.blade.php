<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.asets.index') }}" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-slate-900">{{ $aset->nama }}</h1>
                    <span class="font-mono text-xs font-bold text-indigo-600">{{ $aset->kode_aset }}</span>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.asets.print-qr', $aset) }}" target="_blank" class="inline-flex items-center px-3.5 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-lg shadow-sm transition-colors">
                    <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak Label QR
                </a>
                <a href="{{ route('admin.asets.edit', $aset) }}" class="inline-flex items-center px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-sm transition-colors">
                    <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Ubah Aset
                </a>
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
            <!-- Left 2 Cols: Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Overview Card -->
                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-xs">
                    <div class="flex flex-col sm:flex-row gap-6">
                        <!-- Foto -->
                        <div class="w-full sm:w-48 h-48 bg-slate-100 rounded-xl overflow-hidden border border-slate-200 shrink-0 flex items-center justify-center">
                            @if($aset->foto_url)
                                <img src="{{ $aset->foto_url }}" alt="{{ $aset->nama }}" class="w-full h-full object-cover" />
                            @else
                                <div class="text-center text-slate-400 p-4">
                                    <svg class="w-12 h-12 mx-auto mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-xs">Foto tidak tersedia</span>
                                </div>
                            @endif
                        </div>

                        <!-- Data Fields -->
                        <div class="flex-1 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                    @if($aset->kondisi === 'Baik') bg-emerald-100 text-emerald-800
                                    @elseif($aset->kondisi === 'Rusak Ringan') bg-yellow-100 text-yellow-800
                                    @elseif($aset->kondisi === 'Rusak Berat') bg-rose-100 text-rose-800
                                    @elseif($aset->kondisi === 'Dalam Perbaikan') bg-blue-100 text-blue-800
                                    @else bg-gray-200 text-gray-800 @endif">
                                    Kondisi: {{ $aset->kondisi }}
                                </span>
                                <span class="text-xs text-slate-400">
                                    Dicatat: {{ $aset->created_at->format('d-m-Y H:i') }}
                                </span>
                            </div>

                            <h2 class="text-2xl font-bold text-slate-900">{{ $aset->nama }}</h2>
                            <p class="text-sm text-slate-600 font-medium">Merek: <span class="text-slate-800 font-semibold">{{ $aset->merek ?? '-' }}</span></p>

                            <div class="grid grid-cols-2 gap-3 pt-2 text-xs border-t border-slate-100">
                                <div>
                                    <span class="text-slate-400 block">Kategori</span>
                                    <span class="font-semibold text-slate-700 text-sm">{{ $aset->kategori->nama }}</span>
                                </div>
                                <div>
                                    <span class="text-slate-400 block">Tanggal Perolehan</span>
                                    <span class="font-semibold text-slate-700 text-sm">
                                        {{ $aset->tanggal_perolehan ? $aset->tanggal_perolehan->format('d-m-Y') : '-' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Spesifikasi -->
                    <div class="mt-6 pt-5 border-t border-slate-100">
                        <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Spesifikasi Teknis</h3>
                        <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-200/80 text-sm text-slate-700 leading-relaxed font-mono whitespace-pre-wrap">
                            {{ $aset->spesifikasi ?: 'Tidak ada catatan spesifikasi tambahan.' }}
                        </div>
                    </div>
                </div>

                <!-- Laboratorium Penempatan Card -->
                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-xs">
                    <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider mb-4">Lokasi & Penempatan Aset</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                        <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-100">
                            <span class="text-xs text-slate-400 block">Laboratorium</span>
                            <span class="font-bold text-slate-800">{{ $aset->lab->nama }}</span>
                            <span class="block text-xs font-mono text-indigo-600 font-semibold mt-0.5">Kode: {{ $aset->lab->kode }}</span>
                        </div>
                        <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-100">
                            <span class="text-xs text-slate-400 block">Ruangan / Gedung</span>
                            <span class="font-semibold text-slate-800">{{ $aset->lab->lokasi }}</span>
                        </div>
                        <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-100">
                            <span class="text-xs text-slate-400 block">Penanggung Jawab</span>
                            <span class="font-semibold text-slate-800">{{ $aset->lab->penanggungJawab->nama ?? $aset->lab->penanggungJawab->name }}</span>
                            <span class="block text-xs text-slate-500">{{ $aset->lab->penanggungJawab->email ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Riwayat Tiket Perbaikan (FR-10) -->
                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-xs">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider">Riwayat Pengaduan & Perbaikan</h3>
                        <span class="text-xs px-2 py-0.5 rounded bg-slate-100 text-slate-600 font-medium">0 Tiket</span>
                    </div>
                    <div class="text-center py-8 text-slate-400 bg-slate-50/50 rounded-xl border border-dashed border-slate-200">
                        <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-sm font-medium text-slate-600">Belum ada riwayat perbaikan untuk aset ini</p>
                        <p class="text-xs text-slate-400 mt-0.5">Seluruh tiket pengaduan kerusakan yang dibuat pada Fase 3 & 4 akan otomatis terhubung ke sini.</p>
                    </div>
                </div>
            </div>

            <!-- Right Col: QR Code Card (FR-07b) -->
            <div class="space-y-6">
                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-xs text-center">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 block mb-1">Label Stiker QR Resmi</span>
                    <h4 class="text-sm font-bold text-slate-900 mb-4">Pindai Lapor Cepat (Tanpa Login)</h4>

                    <!-- SVG Container -->
                    <div class="p-4 bg-white border-2 border-indigo-100 rounded-2xl shadow-inner inline-block mx-auto mb-4">
                        {!! $qrSvg !!}
                    </div>

                    <div class="space-y-1 text-xs">
                        <p class="font-mono font-bold text-sm text-slate-900">{{ $aset->kode_aset }}</p>
                        <p class="text-slate-500 truncate max-w-xs mx-auto">
                            Target URL: <a href="{{ $aset->lapor_qr_url }}" target="_blank" class="text-indigo-600 hover:underline">{{ $aset->lapor_qr_url }}</a>
                        </p>
                    </div>

                    <div class="mt-5 pt-4 border-t border-slate-100 space-y-2">
                        <a href="{{ route('admin.asets.print-qr', $aset) }}" target="_blank" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-lg shadow-sm transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            Buka Tampilan Siap Cetak
                        </a>
                        <p class="text-[11px] text-slate-400">
                            Stiker dapat dicetak dan ditempel langsung pada fisik perangkat lab.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

