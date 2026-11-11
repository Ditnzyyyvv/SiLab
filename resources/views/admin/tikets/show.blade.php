<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.tikets.index') }}" class="p-1.5 rounded-lg text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <div class="flex items-center space-x-2">
                        <h1 class="text-xl font-bold font-mono text-slate-900">{{ $tiket->nomor_tiket }}</h1>
                        @php
                            $badgeClasses = match($tiket->status) {
                                'Pending' => 'bg-amber-100 text-amber-800 border-amber-200',
                                'Diproses' => 'bg-blue-100 text-blue-800 border-blue-200',
                                'Menunggu Komponen/Sparepart' => 'bg-orange-100 text-orange-800 border-orange-200',
                                'Selesai' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                'Rusak Total' => 'bg-red-100 text-red-800 border-red-200',
                                'Ditolak' => 'bg-rose-100 text-rose-800 border-rose-200',
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
                        Dibuat pada {{ $tiket->created_at->isoFormat('dddd, D MMMM Y • HH:mm') }} WIB
                    </p>
                </div>
            </div>

            <div class="flex items-center space-x-2">
                @if($tiket->isGuest())
                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold bg-purple-100 text-purple-800 border border-purple-200">
                        Pelapor Tamu (QR Scan Tanpa Login)
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                        Pelapor Member Terdaftar
                    </span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Kolom Kiri / Utama: Detail Kerusakan & Aksi Admin (2 Kolom) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informasi Aset -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-xs p-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Perangkat Aset Terkait</h3>
                    <a href="{{ route('admin.asets.show', $tiket->aset) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                        Detail Master Aset →
                    </a>
                </div>
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
                        <span class="text-slate-400">Kondisi Fisik Saat Ini:</span>
                        <div class="mt-0.5">
                            @php
                                $kondisiBadge = match($tiket->aset->kondisi) {
                                    'Baik' => 'bg-emerald-100 text-emerald-800',
                                    'Rusak Ringan' => 'bg-amber-100 text-amber-800',
                                    'Rusak Berat' => 'bg-orange-100 text-orange-800',
                                    'Dalam Perbaikan' => 'bg-blue-100 text-blue-800',
                                    'Tidak Layak Pakai' => 'bg-rose-100 text-rose-800',
                                    default => 'bg-slate-100 text-slate-700'
                                };
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold {{ $kondisiBadge }}">
                                {{ $tiket->aset->kondisi }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail Keluhan & Bukti Foto -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-xs p-6">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider pb-3 border-b border-slate-100">
                    Keluhan & Kerusakan Fisik
                </h3>
                <div class="mt-4 space-y-3">
                    <div>
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Judul Keluhan</span>
                        <h4 class="text-base font-bold text-slate-900 mt-0.5">{{ $tiket->judul }}</h4>
                    </div>
                    <div>
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Deskripsi Rinci</span>
                        <div class="mt-1 p-3.5 bg-slate-50 rounded-lg text-xs text-slate-700 leading-relaxed whitespace-pre-line border border-slate-100">
                            {{ $tiket->deskripsi }}
                        </div>
                    </div>

                    <!-- Lampiran Foto Bukti -->
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
                            <p class="text-xs text-slate-400 italic">Tidak ada foto bukti kerusakan yang dilampirkan.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- ACTION PANELS -->
            @if($tiket->status === 'Pending')
                <!-- Form Validasi & Disposisi -->
                <div class="bg-white rounded-xl border border-indigo-200 shadow-sm p-6">
                    <div class="flex items-center space-x-2 text-indigo-700 mb-4 pb-3 border-b border-indigo-100">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-900">Validasi & Disposisikan Tiket ke Teknisi</h3>
                    </div>

                    <form action="{{ route('admin.tikets.disposisi', $tiket) }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Pilih Teknisi -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Pilih Teknisi Penanggung Jawab *</label>
                                <select name="teknisi_id" required class="w-full text-xs rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">-- Pilih Teknisi --</option>
                                    @foreach($teknisis as $tek)
                                        <option value="{{ $tek->id }}" {{ old('teknisi_id') == $tek->id ? 'selected' : '' }}>
                                            {{ $tek->nama }} ({{ $tek->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('teknisi_id')
                                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Pilih Prioritas -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Tingkat Prioritas *</label>
                                <select name="prioritas" required class="w-full text-xs rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="Sedang" {{ old('prioritas', 'Sedang') === 'Sedang' ? 'selected' : '' }}>Sedang (Standar)</option>
                                    <option value="Tinggi" {{ old('prioritas') === 'Tinggi' ? 'selected' : '' }}>Tinggi (Krusial / Mendesak)</option>
                                    <option value="Rendah" {{ old('prioritas') === 'Rendah' ? 'selected' : '' }}>Rendah (Dapat Ditunda)</option>
                                </select>
                                @error('prioritas')
                                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Catatan Disposisi -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan Tambahan untuk Teknisi (Opsional)</label>
                            <textarea name="catatan" rows="2" placeholder="Instruksi khusus, batas waktu, atau lokasi spesifik..." class="w-full text-xs rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('catatan') }}</textarea>
                            @error('catatan')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-sm transition-colors">
                                Validasi & Tugaskan Teknisi Sekarang
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Form Penolakan Tiket -->
                <div x-data="{ openReject: false }" class="bg-white rounded-xl border border-rose-200 shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-bold text-rose-800">Tolak Tiket Pengaduan Ini</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Jika laporan palsu, bukan kerusakan lab, atau tidak relevan</p>
                        </div>
                        <button @click="openReject = !openReject" type="button" class="px-3 py-1.5 text-xs font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 rounded-lg transition-colors">
                            <span x-text="openReject ? 'Tutup Form Penolakan' : 'Buka Form Penolakan'"></span>
                        </button>
                    </div>

                    <div x-show="openReject" x-collapse class="mt-4 pt-4 border-t border-rose-100">
                        <form action="{{ route('admin.tikets.tolak', $tiket) }}" method="POST" class="space-y-3">
                            @csrf
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Alasan Penolakan (Wajib Diisi) *</label>
                                <textarea name="alasan_penolakan" rows="2" required placeholder="Contoh: Bukan kerusakan perangkat lab / Perangkat setelah dicek normal..." class="w-full text-xs rounded-lg border-slate-300 focus:border-rose-500 focus:ring-rose-500">{{ old('alasan_penolakan') }}</textarea>
                                @error('alasan_penolakan')
                                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menolak tiket ini?')" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold rounded-lg shadow-xs transition-colors">
                                    Konfirmasi Penolakan Tiket
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            @elseif(in_array($tiket->status, ['Diproses', 'Menunggu Komponen/Sparepart']))
                <!-- Re-disposisi (Pengalihan Teknisi) -->
                <div x-data="{ openReassign: false }" class="bg-white rounded-xl border border-slate-200 shadow-xs p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">Alihkan Penugasan Teknisi</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Pindahkan tanggung jawab tiket ini ke teknisi lain</p>
                        </div>
                        <button @click="openReassign = !openReassign" type="button" class="px-3 py-1.5 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                            <span x-text="openReassign ? 'Batal' : 'Ganti Teknisi'"></span>
                        </button>
                    </div>

                    <div x-show="openReassign" x-collapse class="mt-4 pt-4 border-t border-slate-100">
                        <form action="{{ route('admin.tikets.disposisi', $tiket) }}" method="POST" class="space-y-3">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Teknisi Pengganti *</label>
                                    <select name="teknisi_id" required class="w-full text-xs rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                                        @foreach($teknisis as $tek)
                                            <option value="{{ $tek->id }}" {{ $tiket->teknisi_id == $tek->id ? 'selected' : '' }}>
                                                {{ $tek->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Prioritas *</label>
                                    <select name="prioritas" required class="w-full text-xs rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="Rendah" {{ $tiket->prioritas === 'Rendah' ? 'selected' : '' }}>Rendah</option>
                                        <option value="Sedang" {{ $tiket->prioritas === 'Sedang' ? 'selected' : '' }}>Sedang</option>
                                        <option value="Tinggi" {{ $tiket->prioritas === 'Tinggi' ? 'selected' : '' }}>Tinggi</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan Pengalihan</label>
                                <input type="text" name="catatan" placeholder="Alasan pengalihan teknisi..." class="w-full text-xs rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-lg shadow-xs transition-colors">
                                    Simpan Perubahan Penugasan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            @elseif($tiket->status === 'Selesai')
                @if($tiket->isGuest() && ! $tiket->dikonfirmasi_at)
                    <!-- Tombol Konfirmasi Tiket Tamu oleh Admin (FR-19) -->
                    <div class="bg-emerald-50 border border-emerald-300 rounded-xl p-6">
                        <div class="flex items-start space-x-3">
                            <div class="w-10 h-10 rounded-lg bg-emerald-600 text-white flex items-center justify-center shrink-0">
                                ✓
                            </div>
                            <div class="flex-1">
                                <h3 class="text-sm font-bold text-emerald-950">Konfirmasi Akhir Tiket Tamu (PRD FR-19)</h3>
                                <p class="text-xs text-emerald-800 mt-1 leading-relaxed">
                                    Teknisi telah menyelesaikan perbaikan pada aset ini. Karena tiket ini dilaporkan oleh tamu (tanpa akun login), Admin Lab bertanggung jawab melakukan verifikasi akhir fisik dan mengonfirmasi penutupan tiket secara resmi.
                                </p>
                                <div class="mt-4">
                                    <form action="{{ route('admin.tikets.konfirmasi-tamu', $tiket) }}" method="POST">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Apakah Anda telah memeriksa fisik perangkat dan menyetujui penutupan tiket ini?')" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg shadow-sm transition-colors">
                                            ✓ Konfirmasi & Tutup Tiket Tamu Ini
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($tiket->dikonfirmasi_at)
                    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-center space-x-3 text-emerald-800 text-xs">
                        <span class="text-base">✓</span>
                        <div>
                            <strong>Tiket ini telah dikonfirmasi dan ditutup secara resmi</strong> pada 
                            {{ $tiket->dikonfirmasi_at->isoFormat('D MMMM Y, HH:mm') }} WIB.
                        </div>
                    </div>
                @else
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-center space-x-3 text-blue-800 text-xs">
                        <span class="text-base">ℹ</span>
                        <div>
                            <strong>Menunggu Konfirmasi Member:</strong> Perbaikan telah selesai. Menunggu konfirmasi persetujuan dari member pelapor ({{ $tiket->nama_pelapor }}).
                        </div>
                    </div>
                @endif

            @elseif($tiket->status === 'Ditolak')
                <div class="bg-rose-50 border border-rose-200 rounded-xl p-4 text-xs text-rose-800">
                    <p class="font-bold">Tiket Telah Ditolak</p>
                    <p class="mt-1">Alasan Penolakan: {{ $tiket->alasan_penolakan }}</p>
                </div>
            @endif
        </div>

        <!-- Kolom Kanan: Info Pelapor, Teknisi, & Timeline Riwayat (1 Kolom) -->
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
                        <span class="text-slate-400">Tipe Akun:</span>
                        <div class="mt-0.5">
                            @if($tiket->isGuest())
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-purple-100 text-purple-800">
                                    Tamu Lab (Scan QR Tanpa Login)
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-100 text-emerald-800">
                                    Member Terdaftar ({{ $tiket->pelapor?->email }})
                                </span>
                            @endif
                        </div>
                    </div>
                    @if($tiket->kontak_pelapor)
                        <div>
                            <span class="text-slate-400">Kontak / WhatsApp:</span>
                            <p class="font-mono font-medium text-slate-800 mt-0.5">{{ $tiket->kontak_pelapor }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Info Teknisi -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-xs p-5">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Teknisi Penanggung Jawab</h3>
                @if($tiket->teknisi)
                    <div class="space-y-2 text-xs">
                        <div>
                            <span class="text-slate-400">Nama Teknisi:</span>
                            <p class="font-bold text-slate-900 text-sm mt-0.5">{{ $tiket->teknisi->nama }}</p>
                        </div>
                        <div>
                            <span class="text-slate-400">Email:</span>
                            <p class="font-medium text-slate-800 mt-0.5">{{ $tiket->teknisi->email }}</p>
                        </div>
                        @if($tiket->teknisi->no_hp)
                            <div>
                                <span class="text-slate-400">No. HP:</span>
                                <p class="font-mono text-slate-800 mt-0.5">{{ $tiket->teknisi->no_hp }}</p>
                            </div>
                        @endif
                        @if($tiket->tanggal_disposisi)
                            <div>
                                <span class="text-slate-400">Didisposisikan Pada:</span>
                                <p class="text-slate-600 mt-0.5">{{ $tiket->tanggal_disposisi->isoFormat('D MMM Y, HH:mm') }}</p>
                            </div>
                        @endif
                    </div>
                @else
                    <p class="text-xs text-slate-400 italic">Belum ada teknisi yang ditugaskan untuk tiket ini.</p>
                @endif
            </div>

            <!-- Timeline Riwayat Log Penanganan -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-xs p-5">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">Kronologi & Log Penanganan</h3>
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
                                                    <strong>Komponen Dibutuhkan:</strong> {{ $log->komponen_dibutuhkan }}
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
