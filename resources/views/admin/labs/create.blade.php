<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.labs.index') }}" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h1 class="text-xl font-bold text-slate-900">Tambah Laboratorium Baru</h1>
        </div>
    </x-slot>

    <div class="max-w-2xl bg-white p-6 rounded-xl border border-slate-200 shadow-xs">
        <form method="POST" action="{{ route('admin.labs.store') }}" class="space-y-5">
            @csrf

            <!-- Kode Lab -->
            <div>
                <label for="kode" class="block text-sm font-semibold text-slate-700 mb-1">
                    Kode Lab <span class="text-rose-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="kode" 
                    name="kode" 
                    value="{{ old('kode') }}" 
                    placeholder="Contoh: RPL, TKJ, MM, MULTIMEDIA"
                    class="w-full text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 uppercase @error('kode') border-rose-500 @enderror"
                    required
                />
                <p class="text-xs text-slate-400 mt-1">Kode unik yang digunakan sebagai awalan kode aset (misal: AST-RPL-0001).</p>
                @error('kode')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nama Lab -->
            <div>
                <label for="nama" class="block text-sm font-semibold text-slate-700 mb-1">
                    Nama Laboratorium <span class="text-rose-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="nama" 
                    name="nama" 
                    value="{{ old('nama') }}" 
                    placeholder="Contoh: Laboratorium Rekayasa Perangkat Lunak 1"
                    class="w-full text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('nama') border-rose-500 @enderror"
                    required
                />
                @error('nama')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Lokasi Lab -->
            <div>
                <label for="lokasi" class="block text-sm font-semibold text-slate-700 mb-1">
                    Lokasi / Ruangan <span class="text-rose-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="lokasi" 
                    name="lokasi" 
                    value="{{ old('lokasi') }}" 
                    placeholder="Contoh: Gedung B Lantai 2 Ruang 204"
                    class="w-full text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('lokasi') border-rose-500 @enderror"
                    required
                />
                @error('lokasi')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Penanggung Jawab -->
            <div>
                <label for="penanggung_jawab_id" class="block text-sm font-semibold text-slate-700 mb-1">
                    Penanggung Jawab (Kepala Lab / Guru) <span class="text-rose-500">*</span>
                </label>
                <select 
                    id="penanggung_jawab_id" 
                    name="penanggung_jawab_id" 
                    class="w-full text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('penanggung_jawab_id') border-rose-500 @enderror"
                    required
                >
                    <option value="">-- Pilih Penanggung Jawab --</option>
                    @foreach($penanggungJawabs as $user)
                        <option value="{{ $user->id }}" {{ old('penanggung_jawab_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->nama ?? $user->name }} ({{ ucfirst($user->role) }})
                        </option>
                    @endforeach
                </select>
                @error('penanggung_jawab_id')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.labs.index') }}" class="px-4 py-2 text-sm text-slate-600 hover:text-slate-800 font-medium">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm transition-colors">
                    Simpan Laboratorium
                </button>
            </div>
        </form>
    </div>
</x-app-layout>

