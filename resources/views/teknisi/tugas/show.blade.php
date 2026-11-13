<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center space-x-3">
                <a href="{{ route('teknisi.tugas.index') }}" class="p-1.5 rounded-lg text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <div class="flex items-center space-x-2">
                        <h1 class="text-xl font-bold font-mono text-slate-900">{{ $tiket->nomor_tiket }}</h1>
                        @php
                            $badgeClasses = match($tiket->status) {
                                'Diproses' => 'bg-blue-100 text-blue-800 border-blue-200',
                                'Menunggu Komponen/Sparepart' => 'bg-orange-100 text-orange-800 border-orange-200',
                                'Selesai' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                'Rusak Total' => 'bg-red-100 text-red-800 border-red-200',
                                default => 'bg-slate-100 text-slate-700 border-slate-200'
                            };
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $badgeClasses }}">
                            {{ $tiket->status }}
                        </span>
                        @if($tiket->prioritas)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold {{ $tiket->prioritas === 'Tinggi' ? 'bg-rose-100 text-rose-800' : ($tiket->prioritas === 'Sedang' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-700') }}">
                                Prioritas: {{ $tiket->prioritas }}
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Disposisi: {{ $tiket->tanggal_disposisi ? $tiket->tanggal_disposisi->isoFormat('dddd, D MMMM Y • HH:mm') : '-' }} WIB
                    </p>
                </div>
            </div>

            <div class="flex items-center space-x-2">
                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium bg-blue-50 text-blue-800 border border-blue-200">
                    <span class="w-2 h-2 rounded-full bg-blue-500 mr-2"></span> Tugas Anda
                </span>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Kolom Utama: Detail Perangkat, Kerusakan, & Form Update Status (2 Kolom) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informasi Perangkat Aset -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-xs p-6">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider pb-3 border-b border-slate-100">
                    Identitas Perangkat Laboratorium
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4 text-xs">
                    <div>
                        <span class="text-slate-400">Nama Perangkat:</span>
                        <p class="text-slate-900 font-semibold text-sm mt-0.5">{{ $tiket->aset->nama }}</p>
                    </div>
                    <div>
                        <span class="text-slate-400">Kode Aset:</span>
                        <p class="font-mono font-bold text-slate-800 text-sm mt-0.5">{{ $tiket->aset->kode_aset }}</p>
                    </div>
                    <div>
                        <span class="text-slate-400">Lokasi / Laboratorium:</span>
                        <p class="text-slate-900 font-medium mt-0.5">{{ $tiket->aset->lab->nama }} ({{ $tiket->aset->lab->lokasi }})</p>
                    </div>
                    <div>
                        <span class="text-slate-400">Kategori & Merek:</span>
                        <p class="text-slate-700 mt-0.5">{{ $tiket->aset->kategori->nama }} • {{ $tiket->aset->merek ?? '-' }}</p>
                    </div>
                    @if($tiket->aset->spesifikasi)
                        <div class="sm:col-span-2">
                            <span class="text-slate-400">Spesifikasi Teknis:</span>
                            <p class="text-slate-700 mt-0.5 font-mono text-[11px] bg-slate-50 p-2 rounded border border-slate-100">{{ $tiket->aset->spesifikasi }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Detail Keluhan & Bukti Foto Pelapor -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-xs p-6">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider pb-3 border-b border-slate-100">
                    Deskripsi Masalah Kerusakan
                </h3>
                <div class="mt-4 space-y-3">
                    <div>
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Judul Keluhan</span>
                        <h4 class="text-base font-bold text-slate-900 mt-0.5">{{ $tiket->judul }}</h4>
                    </div>
                    <div>
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Deskripsi Pelapor</span>
                        <div class="mt-1 p-3.5 bg-slate-50 rounded-lg text-xs text-slate-700 leading-relaxed whitespace-pre-line border border-slate-100">
                            {{ $tiket->deskripsi }}
                        </div>
                    </div>

                    <!-- Lampiran Foto Pelapor -->
                    <div class="pt-2">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-2">
                            Foto Bukti Kerusakan Fisik ({{ $tiket->fotos->count() }})
                        </span>
                        @if($tiket->fotos->isNotEmpty())
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                @foreach($tiket->fotos as $f)
                                    <a href="{{ $f->foto_url }}" target="_blank" class="group relative rounded-lg overflow-hidden border border-slate-200 aspect-video bg-slate-100 block">
                                        <img src="{{ $f->foto_url }}" alt="Bukti Kerusakan" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                                        <div class="absolute inset-0 bg-slate-900/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-medium">
                                            Perbesar ↗
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-slate-400 italic">Pelapor tidak melampirkan foto bukti.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- ACTION PANEL: FORM UPDATE STATUS PENGERJAAN TEKNISI -->
            @if(in_array($tiket->status, ['Diproses', 'Menunggu Komponen/Sparepart']))
                <div x-data="{ 
                    statusPilihan: '{{ old('status', $tiket->status === 'Menunggu Komponen/Sparepart' ? 'Diproses' : 'Selesai') }}' 
                }" class="bg-white rounded-xl border border-blue-200 shadow-sm p-6">
                    <div class="flex items-center space-x-2 text-blue-700 mb-4 pb-3 border-b border-blue-100">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-900">Perbarui Status & Catatan Penanganan (FR-21)</h3>
                    </div>

                    <form action="{{ route('teknisi.tugas.update-status', $tiket) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf

                        <!-- Pilihan Status Sesuai Alur PRD Bagian 9 -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-2">Pilih Aksi / Status Berikutnya *</label>
                            
                            @if($tiket->status === 'Diproses')
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    <!-- Opsi 1: Selesai -->
                                    <label :class="statusPilihan === 'Selesai' ? 'border-emerald-500 bg-emerald-50/50 ring-2 ring-emerald-500/20' : 'border-slate-200 bg-white hover:bg-slate-50'" class="border rounded-xl p-3.5 flex flex-col justify-between cursor-pointer transition-all">
                                        <div class="flex items-center space-x-2">
                                            <input type="radio" name="status" value="Selesai" x-model="statusPilihan" class="text-emerald-600 focus:ring-emerald-500">
                                            <span class="text-xs font-bold text-slate-900">Selesai Diperbaiki</span>
                                        </div>
                                        <p class="text-[11px] text-slate-500 mt-2">Aset berfungsi normal kembali (kondisi otomatis menjadi Baik).</p>
                                    </label>

                                    <!-- Opsi 2: Menunggu Komponen/Sparepart -->
                                    <label :class="statusPilihan === 'Menunggu Komponen/Sparepart' ? 'border-orange-500 bg-orange-50/50 ring-2 ring-orange-500/20' : 'border-slate-200 bg-white hover:bg-slate-50'" class="border rounded-xl p-3.5 flex flex-col justify-between cursor-pointer transition-all">
                                        <div class="flex items-center space-x-2">
                                            <input type="radio" name="status" value="Menunggu Komponen/Sparepart" x-model="statusPilihan" class="text-orange-600 focus:ring-orange-500">
                                            <span class="text-xs font-bold text-slate-900">Butuh Sparepart</span>
                                        </div>
                                        <p class="text-[11px] text-slate-500 mt-2">Perbaikan tertahan karena memerlukan penggantian suku cadang.</p>
                                    </label>

                                    <!-- Opsi 3: Rusak Total -->
                                    <label :class="statusPilihan === 'Rusak Total' ? 'border-red-500 bg-red-50/50 ring-2 ring-red-500/20' : 'border-slate-200 bg-white hover:bg-slate-50'" class="border rounded-xl p-3.5 flex flex-col justify-between cursor-pointer transition-all">
                                        <div class="flex items-center space-x-2">
                                            <input type="radio" name="status" value="Rusak Total" x-model="statusPilihan" class="text-red-600 focus:ring-red-500">
                                            <span class="text-xs font-bold text-slate-900">Rusak Total</span>
                                        </div>
                                        <p class="text-[11px] text-slate-500 mt-2">Tidak dapat diperbaiki (kondisi aset menjadi Tidak Layak Pakai).</p>
                                    </label>
                                </div>
                            @elseif($tiket->status === 'Menunggu Komponen/Sparepart')
                                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                                    <label class="flex items-center space-x-3 cursor-pointer">
                                        <input type="radio" name="status" value="Diproses" checked class="text-blue-600 focus:ring-blue-500">
                                        <div>
                                            <span class="text-xs font-bold text-blue-900">Lanjutkan Pengerjaan (Kembali ke Diproses)</span>
                                            <p class="text-[11px] text-blue-700 mt-0.5">Komponen / sparepart telah tersedia dan siap dipasang ke perangkat.</p>
                                        </div>
                                    </label>
                                </div>
                            @endif

                            @error('status')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Form Input Komponen Dibutuhkan (FR-23: Wajib saat Menunggu Komponen/Sparepart) -->
                        <div x-show="statusPilihan === 'Menunggu Komponen/Sparepart'" x-collapse class="bg-orange-50 border border-orange-200 rounded-xl p-4">
                            <label class="block text-xs font-bold text-orange-950 mb-1">
                                Nama Komponen / Suku Cadang yang Dibutuhkan * (FR-23)
                            </label>
                            <input 
                                type="text" 
                                name="komponen_dibutuhkan" 
                                value="{{ old('komponen_dibutuhkan') }}"
                                placeholder="Contoh: RAM DDR4 8GB PC2666 / Power Supply 500W / Kabel HDMI 3M" 
                                class="w-full text-xs rounded-lg border-orange-300 focus:border-orange-500 focus:ring-orange-500"
                            >
                            <p class="text-[11px] text-orange-700 mt-1">Admin Lab akan meninjau ketersediaan atau pengadaan komponen ini.</p>
                            @error('komponen_dibutuhkan')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Catatan Progres Penanganan (FR-22: Wajib) -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                Catatan Progres / Hasil Tindakan * (FR-22)
                            </label>
                            <textarea 
                                name="catatan" 
                                rows="3" 
                                required
                                placeholder="Jelaskan tindakan yang sudah dilakukan, temuan teknis kerusakan, atau komponen yang diperbaiki..." 
                                class="w-full text-xs rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                            >{{ old('catatan') }}</textarea>
                            @error('catatan')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Unggah Foto Bukti (FR-22: Opsional) -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                Foto Bukti Hasil Pengerjaan (Opsional)
                            </label>
                            <input 
                                type="file" 
                                name="foto_bukti" 
                                accept="image/jpeg,image/png,image/jpg" 
                                class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                            >
                            <p class="text-[11px] text-slate-400 mt-1">Format JPG atau PNG, maksimal 2 MB.</p>
                            @error('foto_bukti')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end pt-2">
                            <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg shadow-sm transition-colors">
                                Simpan & Perbarui Progres Tiket
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <!-- Read-only status info for completed tasks -->
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-6 text-center text-slate-600 text-xs">
                    <p class="font-bold text-sm text-slate-800">Tugas Telah Berstatus {{ $tiket->status }}</p>
                    <p class="mt-1">Pengerjaan teknisi untuk tiket ini telah selesai dan dicatat di log sistem.</p>
                    @if($tiket->dikonfirmasi_at)
                        <p class="text-emerald-700 font-semibold mt-2">✓ Telah Dikonfirmasi pada {{ $tiket->dikonfirmasi_at->isoFormat('D MMMM Y, HH:mm') }} WIB</p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Kolom Kanan: Pelapor & Timeline Log Penanganan (1 Kolom) -->
        <div class="space-y-6">
            <!-- Info Pelapor -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-xs p-5">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Informasi Pelapor</h3>
                <div class="space-y-2 text-xs">
                    <div>
                        <span class="text-slate-400">Nama Pelapor:</span>
                        <p class="font-bold text-slate-900 text-sm mt-0.5">{{ $tiket->nama_pelapor }}</p>
                    </div>
                    <div>
                        <span class="text-slate-400">Tipe Pelapor:</span>
                        <div class="mt-0.5">
                            @if($tiket->isGuest())
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-purple-100 text-purple-800">
                                    Tamu Lab (Scan QR)
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-100 text-emerald-800">
                                    Member Lab
                                </span>
                            @endif
                        </div>
                    </div>
                    @if($tiket->kontak_pelapor)
                        <div>
                            <span class="text-slate-400">Kontak / No. HP:</span>
                            <p class="font-mono font-medium text-slate-800 mt-0.5">{{ $tiket->kontak_pelapor }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Timeline Log Penanganan -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-xs p-5">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">Kronologi Penanganan</h3>
                <div class="flow-root">
                    <ul role="list" class="-mb-8">
                        @foreach($tiket->logs as $logIndex => $log)
                            <li>
                                <div class="relative pb-8">
                                    @if(! $loop->last)
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-slate-200" aria-hidden="true"></span>
                                    @endif
                                    <div class="relative flex space-x-3 items-start">
                                        <div>
                                            @php
                                                $dotBg = match($log->status_ke) {
                                                    'Pending' => 'bg-amber-500',
                                                    'Diproses' => 'bg-blue-600',
                                                    'Menunggu Komponen/Sparepart' => 'bg-orange-500',
                                                    'Selesai' => 'bg-emerald-600',
                                                    'Rusak Total' => 'bg-red-600',
                                                    'Ditolak' => 'bg-rose-600',
                                                    default => 'bg-slate-400'
                                                };
                                            @endphp
                                            <span class="h-8 w-8 rounded-full {{ $dotBg }} text-white flex items-center justify-center ring-4 ring-white shadow-xs text-xs font-bold">
                                                {{ $tiket->logs->count() - $loop->index }}
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-0.5">
                                            <div class="flex items-center justify-between">
                                                <p class="text-xs font-bold text-slate-900">
                                                    {{ $log->status_ke }}
                                                </p>
                                                <span class="text-[10px] text-slate-400">
                                                    {{ $log->created_at->isoFormat('D MMM, HH:mm') }}
                                                </span>
                                            </div>
                                            <p class="text-[11px] text-slate-500 mt-0.5">
                                                Oleh: <span class="font-medium text-slate-700">{{ $log->user?->nama ?? $tiket->nama_pelapor }}</span>
                                            </p>
                                            <div class="mt-1.5 p-2.5 rounded-lg bg-slate-50 border border-slate-100 text-xs text-slate-700 leading-relaxed">
                                                {{ $log->catatan }}
                                            </div>
                                            @if($log->komponen_dibutuhkan)
                                                <div class="mt-1.5 text-xs text-orange-800 bg-orange-50 border border-orange-200 rounded p-2">
                                                    <strong>Komponen:</strong> {{ $log->komponen_dibutuhkan }}
                                                </div>
                                            @endif
                                            @if($log->foto_bukti)
                                                <div class="mt-2">
                                                    <a href="{{ $log->foto_bukti_url }}" target="_blank" class="inline-block rounded-md overflow-hidden border border-slate-200">
                                                        <img src="{{ $log->foto_bukti_url }}" alt="Bukti Penanganan" class="h-20 w-auto object-cover">
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

