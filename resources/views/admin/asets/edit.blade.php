<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.asets.show', $aset) }}" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h1 class="text-xl font-bold text-slate-900">Ubah Data Aset: {{ $aset->kode_aset }}</h1>
        </div>
    </x-slot>

    <div class="max-w-3xl bg-white p-6 sm:p-8 rounded-xl border border-slate-200 shadow-xs">
        <form method="POST" action="{{ route('admin.asets.update', $aset) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Kode Aset Readonly -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">
                    Kode Aset (Unik)
                </label>
                <input 
                    type="text" 
                    value="{{ $aset->kode_aset }}" 
                    class="w-full text-sm font-mono font-bold bg-slate-100 rounded-lg border border-slate-300 text-slate-600 cursor-not-allowed"
                    readonly
                />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Pilihan Lab -->
                <div>
                    <label for="lab_id" class="block text-sm font-semibold text-slate-700 mb-1">
                        Laboratorium Penempatan <span class="text-rose-500">*</span>
                    </label>
                    <select 
                        id="lab_id" 
                        name="lab_id" 
                        class="w-full text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('lab_id') border-rose-500 @enderror"
                        required
                    >
                        @foreach($labs as $lab)
                            <option value="{{ $lab->id }}" {{ old('lab_id', $aset->lab_id) == $lab->id ? 'selected' : '' }}>
                                {{ $lab->nama }} ({{ $lab->kode }})
                            </option>
                        @endforeach
                    </select>
                    @error('lab_id')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pilihan Kategori -->
                <div>
                    <label for="kategori_id" class="block text-sm font-semibold text-slate-700 mb-1">
                        Kategori Perangkat <span class="text-rose-500">*</span>
                    </label>
                    <select 
                        id="kategori_id" 
                        name="kategori_id" 
                        class="w-full text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('kategori_id') border-rose-500 @enderror"
                        required
                    >
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" {{ old('kategori_id', $aset->kategori_id) == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori_id')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Nama Aset -->
                <div>
                    <label for="nama" class="block text-sm font-semibold text-slate-700 mb-1">
                        Nama Aset / Perangkat <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="nama" 
                        name="nama" 
                        value="{{ old('nama', $aset->nama) }}" 
                        class="w-full text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('nama') border-rose-500 @enderror"
                        required
                    />
                    @error('nama')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Merek / Model -->
                <div>
                    <label for="merek" class="block text-sm font-semibold text-slate-700 mb-1">
                        Merek / Model
                    </label>
                    <input 
                        type="text" 
                        id="merek" 
                        name="merek" 
                        value="{{ old('merek', $aset->merek) }}" 
                        class="w-full text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('merek') border-rose-500 @enderror"
                    />
                    @error('merek')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Spesifikasi Teknis -->
            <div>
                <label for="spesifikasi" class="block text-sm font-semibold text-slate-700 mb-1">
                    Spesifikasi Teknis
                </label>
                <textarea 
                    id="spesifikasi" 
                    name="spesifikasi" 
                    rows="3" 
                    class="w-full text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('spesifikasi') border-rose-500 @enderror"
                >{{ old('spesifikasi', $aset->spesifikasi) }}</textarea>
                @error('spesifikasi')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Tanggal Perolehan -->
                <div>
                    <label for="tanggal_perolehan" class="block text-sm font-semibold text-slate-700 mb-1">
                        Tanggal Perolehan
                    </label>
                    <input 
                        type="date" 
                        id="tanggal_perolehan" 
                        name="tanggal_perolehan" 
                        value="{{ old('tanggal_perolehan', $aset->tanggal_perolehan ? $aset->tanggal_perolehan->format('Y-m-d') : '') }}" 
                        class="w-full text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('tanggal_perolehan') border-rose-500 @enderror"
                    />
                    @error('tanggal_perolehan')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kondisi Aset -->
                <div>
                    <label for="kondisi" class="block text-sm font-semibold text-slate-700 mb-1">
                        Kondisi Aset <span class="text-rose-500">*</span>
                    </label>
                    <select 
                        id="kondisi" 
                        name="kondisi" 
                        class="w-full text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('kondisi') border-rose-500 @enderror"
                        required
                    >
                        @foreach($kondisiList as $kondisi)
                            <option value="{{ $kondisi }}" {{ old('kondisi', $aset->kondisi) == $kondisi ? 'selected' : '' }}>
                                {{ $kondisi }}
                            </option>
                        @endforeach
                    </select>
                    @error('kondisi')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Foto Aset -->
            <div>
                <label for="foto" class="block text-sm font-semibold text-slate-700 mb-1">
                    Ganti Foto Aset (Biarkan kosong jika tidak diubah)
                </label>
                @if($aset->foto_url)
                    <div class="mb-2 flex items-center space-x-3">
                        <img src="{{ $aset->foto_url }}" alt="{{ $aset->nama }}" class="w-16 h-16 object-cover rounded-lg border border-slate-200" />
                        <span class="text-xs text-slate-500">Foto saat ini tersimpan</span>
                    </div>
                @endif
                <input 
                    type="file" 
                    id="foto" 
                    name="foto" 
                    accept="image/jpeg,image/png,image/jpg"
                    class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-slate-300 rounded-lg cursor-pointer"
                />
                @error('foto')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.asets.show', $aset) }}" class="px-4 py-2 text-sm text-slate-600 hover:text-slate-800 font-medium">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm transition-colors">
                    Perbarui Data Aset
                </button>
            </div>
        </form>
    </div>
</x-app-layout>

