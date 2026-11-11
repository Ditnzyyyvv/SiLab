<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2">
            <a href="{{ route('member.tikets.index') }}" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h1 class="text-xl font-bold text-slate-900">Buat Tiket Pengaduan Kerusakan</h1>
        </div>
    </x-slot>

    <div class="max-w-3xl bg-white p-6 sm:p-8 rounded-xl border border-slate-200 shadow-xs">
        <form method="POST" action="{{ route('member.tikets.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Pilih Aset -->
            <div>
                <label for="aset_id" class="block text-sm font-semibold text-slate-700 mb-1">
                    Pilih Perangkat Aset Laboratorium <span class="text-rose-500">*</span>
                </label>
                <select 
                    id="aset_id" 
                    name="aset_id" 
                    class="w-full text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('aset_id') border-rose-500 @enderror"
                    required
                >
                    <option value="">-- Pilih Perangkat Aset yang Mengalami Kerusakan --</option>
                    @foreach($asets as $aset)
                        <option value="{{ $aset->id }}" {{ old('aset_id', $selectedAsetId) == $aset->id ? 'selected' : '' }}>
                            [{{ $aset->kode_aset }}] {{ $aset->nama }} — {{ $aset->lab->nama }} (Kondisi: {{ $aset->kondisi }})
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-slate-400 mt-1">Pilih perangkat sesuai label stiker yang tertera di meja atau casing perangkat.</p>
                @error('aset_id')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kontak Pelapor -->
            <div>
                <label for="kontak_pelapor" class="block text-sm font-semibold text-slate-700 mb-1">
                    Nomor WhatsApp / Kontak (Opsional)
                </label>
                <input 
                    type="text" 
                    id="kontak_pelapor" 
                    name="kontak_pelapor" 
                    value="{{ old('kontak_pelapor', Auth::user()->no_hp) }}" 
                    placeholder="Contoh: 081234567890 (agar teknisi dapat menghubungi)"
                    class="w-full text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('kontak_pelapor') border-rose-500 @enderror"
                />
                @error('kontak_pelapor')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Judul Keluhan -->
            <div>
                <label for="judul" class="block text-sm font-semibold text-slate-700 mb-1">
                    Judul Pengaduan <span class="text-rose-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="judul" 
                    name="judul" 
                    value="{{ old('judul') }}" 
                    placeholder="Contoh: PC sering mati sendiri saat menjalankan software berat"
                    class="w-full text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('judul') border-rose-500 @enderror"
                    required
                />
                @error('judul')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Deskripsi Detail -->
            <div>
                <label for="deskripsi" class="block text-sm font-semibold text-slate-700 mb-1">
                    Deskripsi & Kronologi Kerusakan <span class="text-rose-500">*</span>
                </label>
                <textarea 
                    id="deskripsi" 
                    name="deskripsi" 
                    rows="4" 
                    placeholder="Ceritakan detail kendala, kode error bila ada, dan kapan gejala mulai muncul..."
                    class="w-full text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('deskripsi') border-rose-500 @enderror"
                    required
                >{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Lampiran Foto -->
            <div>
                <label for="fotos" class="block text-sm font-semibold text-slate-700 mb-1">
                    Foto Bukti Kerusakan (Maksimal 3 Foto, JPG/PNG, Maks. 2MB/foto)
                </label>
                <input 
                    type="file" 
                    id="fotos" 
                    name="fotos[]" 
                    multiple 
                    accept="image/jpeg,image/png,image/jpg"
                    class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-slate-300 rounded-lg cursor-pointer"
                />
                @error('fotos')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
                @error('fotos.*')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                <a href="{{ route('member.tikets.index') }}" class="px-4 py-2 text-sm text-slate-600 hover:text-slate-800 font-medium">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg shadow-sm transition-colors">
                    Kirim Tiket Pengaduan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
