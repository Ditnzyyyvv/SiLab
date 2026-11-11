<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.asets.index') }}" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h1 class="text-xl font-bold text-slate-900">Tambah Aset Laboratorium Baru</h1>
        </div>
    </x-slot>

    <div class="max-w-3xl bg-white p-6 sm:p-8 rounded-xl border border-slate-200 shadow-xs">
        <!-- Info Auto Code -->
        <div class="mb-6 p-4 rounded-xl bg-indigo-50/80 border border-indigo-100 flex items-start space-x-3 text-xs text-indigo-900">
            <svg class="w-5 h-5 text-indigo-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <p class="font-semibold text-sm">Penomoran Kode Aset & QR Code Otomatis</p>
                <p class="mt-0.5 text-slate-600">
                    Sistem akan otomatis menghasilkan kode aset unik (format <code class="font-mono font-bold text-indigo-700">AST-{KODE_LAB}-{URUT}</code>) dan stiker QR Code resmi berdasarkan laboratorium yang Anda pilih.
                </p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.asets.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

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
                        <option value="">-- Pilih Laboratorium --</option>
                        @foreach($labs as $lab)
                            <option value="{{ $lab->id }}" {{ old('lab_id') == $lab->id ? 'selected' : '' }}>
                                {{ $lab->nama }} (Kode: {{ $lab->kode }})
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
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
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
                        value="{{ old('nama') }}" 
                        placeholder="Contoh: PC Client 01, Switch 24 Port"
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
                        value="{{ old('merek') }}" 
                        placeholder="Contoh: Lenovo ThinkCentre, Cisco Catalyst"
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
                    placeholder="Contoh: Core i5 Gen 12, RAM 16GB DDR4, SSD 512GB NVMe, Monitor 24 inch"
                    class="w-full text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('spesifikasi') border-rose-500 @enderror"
                >{{ old('spesifikasi') }}</textarea>
                @error('spesifikasi')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Tanggal Perolehan -->
                <div>
                    <label for="tanggal_perolehan" class="block text-sm font-semibold text-slate-700 mb-1">
                        Tanggal Perolehan / Pengadaan
                    </label>
                    <input 
                        type="date" 
                        id="tanggal_perolehan" 
                        name="tanggal_perolehan" 
                        value="{{ old('tanggal_perolehan') }}" 
                        class="w-full text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('tanggal_perolehan') border-rose-500 @enderror"
                    />
                    @error('tanggal_perolehan')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kondisi Aset (FR-08) -->
                <div>
                    <label for="kondisi" class="block text-sm font-semibold text-slate-700 mb-1">
                        Kondisi Awal <span class="text-rose-500">*</span>
                    </label>
                    <select 
                        id="kondisi" 
                        name="kondisi" 
                        class="w-full text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('kondisi') border-rose-500 @enderror"
                        required
                    >
                        @foreach($kondisiList as $kondisi)
                            <option value="{{ $kondisi }}" {{ old('kondisi', 'Baik') == $kondisi ? 'selected' : '' }}>
                                {{ $kondisi }}
                            </option>
                        @endforeach
                    </select>
                    @error('kondisi')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Upload Foto Aset -->
            <div>
                <label for="foto" class="block text-sm font-semibold text-slate-700 mb-1">
                    Foto Perangkat / Aset (JPG/PNG, Maks. 2MB)
                </label>
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
                <a href="{{ route('admin.asets.index') }}" class="px-4 py-2 text-sm text-slate-600 hover:text-slate-800 font-medium">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm transition-colors">
                    Simpan & Buat QR Code Aset
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
