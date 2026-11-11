<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.kategoris.index') }}" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h1 class="text-xl font-bold text-slate-900">Tambah Kategori Aset</h1>
        </div>
    </x-slot>

    <div class="max-w-xl bg-white p-6 rounded-xl border border-slate-200 shadow-xs">
        <form method="POST" action="{{ route('admin.kategoris.store') }}" class="space-y-5">
            @csrf

            <div>
                <label for="nama" class="block text-sm font-semibold text-slate-700 mb-1">
                    Nama Kategori <span class="text-rose-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="nama" 
                    name="nama" 
                    value="{{ old('nama') }}" 
                    placeholder="Contoh: Komputer & PC, Periferal, Jaringan, Proyektor"
                    class="w-full text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('nama') border-rose-500 @enderror"
                    required
                />
                @error('nama')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.kategoris.index') }}" class="px-4 py-2 text-sm text-slate-600 hover:text-slate-800 font-medium">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm transition-colors">
                    Simpan Kategori
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
