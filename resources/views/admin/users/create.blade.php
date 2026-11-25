<x-app-layout>
    <x-slot name="header">
        <div>
            <a href="{{ route('admin.users.index') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium inline-flex items-center">
                ← Kembali ke Manajemen Pengguna
            </a>
            <h1 class="text-xl font-bold text-slate-900 mt-1">Tambah Pengguna Baru</h1>
        </div>
    </x-slot>

    <div class="max-w-2xl">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6">
            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="nama" class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required placeholder="Contoh: Budi Santoso, S.Kom." class="w-full text-sm rounded-lg border-slate-300 focus:ring-indigo-500 focus:border-indigo-500" />
                    @error('nama')<p class="text-xs text-rose-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-700 mb-1">Alamat Email <span class="text-rose-500">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="nama@silab.sch.id" class="w-full text-sm rounded-lg border-slate-300 focus:ring-indigo-500 focus:border-indigo-500" />
                        @error('email')<p class="text-xs text-rose-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="role" class="block text-xs font-semibold text-slate-700 mb-1">Peran (Role) <span class="text-rose-500">*</span></label>
                        <select id="role" name="role" required class="w-full text-sm rounded-lg border-slate-300 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="teknisi" {{ old('role') === 'teknisi' ? 'selected' : '' }}>Teknisi Laboratorium</option>
                            <option value="member" {{ old('role') === 'member' ? 'selected' : '' }}>Member (Siswa / Guru)</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrator</option>
                        </select>
                        @error('role')<p class="text-xs text-rose-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label for="no_hp" class="block text-xs font-semibold text-slate-700 mb-1">Nomor HP / WhatsApp (Opsional)</label>
                    <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp') }}" placeholder="081234567890" class="w-full text-sm rounded-lg border-slate-300 focus:ring-indigo-500 focus:border-indigo-500" />
                    @error('no_hp')<p class="text-xs text-rose-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-slate-100">
                    <div>
                        <label for="password" class="block text-xs font-semibold text-slate-700 mb-1">Kata Sandi <span class="text-rose-500">*</span></label>
                        <input type="password" id="password" name="password" required placeholder="Minimal 8 karakter" class="w-full text-sm rounded-lg border-slate-300 focus:ring-indigo-500 focus:border-indigo-500" />
                        @error('password')<p class="text-xs text-rose-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 mb-1">Konfirmasi Kata Sandi <span class="text-rose-500">*</span></label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Ulangi kata sandi" class="w-full text-sm rounded-lg border-slate-300 focus:ring-indigo-500 focus:border-indigo-500" />
                    </div>
                </div>

                <div class="pt-4 flex justify-end space-x-2">
                    <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-xs transition-colors">
                        Simpan Pengguna
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
