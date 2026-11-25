<x-guest-layout>
    <div class="mb-5">
        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-50 text-[#3B49DF] border border-blue-200 mb-3">
            Langkah 2 dari 2 • Verifikasi Identitas Sekolah
        </span>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Lengkapi Identitas Asli Anda</h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1.5 leading-relaxed">
            Karena nama akun Google terkadang menggunakan nama samaran, mohon isi <strong>nama lengkap asli</strong> dan <strong>kelas/jabatan</strong> Anda agar Admin & Teknisi Lab mudah mengenali laporan Anda.
        </p>
    </div>

    <!-- Connected Google Account Badge -->
    <div class="mb-5 p-3.5 rounded-xl bg-slate-50 border border-slate-200 flex items-center gap-3">
        @if($user->avatar)
            <img src="{{ $user->avatar }}" alt="{{ $user->email }}" class="w-10 h-10 rounded-full object-cover border border-slate-200 shrink-0" referrerpolicy="no-referrer" />
        @else
            <div class="w-10 h-10 rounded-full bg-blue-100 text-[#3B49DF] font-bold text-sm flex items-center justify-center shrink-0">
                {{ strtoupper(substr($user->email, 0, 2)) }}
            </div>
        @endif
        <div class="min-w-0 flex-1">
            <div class="flex items-center gap-1.5">
                <span class="text-xs font-bold text-slate-900 truncate">{{ $user->email }}</span>
                <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-emerald-100 text-emerald-700 shrink-0">Terhubung Google</span>
            </div>
            <p class="text-[11px] text-slate-500 truncate mt-0.5">
                Nama akun Google: <span class="font-medium text-slate-700">{{ $user->nama }}</span>
            </p>
        </div>
    </div>

    @if(session('info'))
        <div class="mb-4 p-3 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-900 leading-relaxed">
            {{ session('info') }}
        </div>
    @endif

    <form method="POST" action="{{ route('auth.google.complete-profile.store') }}" class="space-y-4">
        @csrf

        <!-- Nama Lengkap Asli (Dikosongkan agar pengguna mengisi nama aslinya secara sadar) -->
        <div>
            <label for="nama" class="block text-xs font-semibold text-slate-700 mb-1.5">
                Nama Lengkap Asli <span class="text-rose-500">*</span>
            </label>
            <input
                id="nama"
                type="text"
                name="nama"
                value="{{ old('nama') }}"
                required
                autofocus
                placeholder="Contoh: Muhammad Aditya Pratama"
                class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-[#3B49DF] focus:ring-2 focus:ring-[#3B49DF]/20 transition-all @error('nama') border-rose-400 @enderror"
            />
            <p class="text-[11px] text-slate-400 mt-1">Gunakan nama asli sesuai absensi sekolah / daftar guru.</p>
            <x-input-error :messages="$errors->get('nama')" class="mt-1" />
        </div>

        <!-- Kelas / Jabatan -->
        <div>
            <label for="kelas_jabatan" class="block text-xs font-semibold text-slate-700 mb-1.5">
                Kelas / Jabatan <span class="text-rose-500">*</span>
            </label>
            <input
                id="kelas_jabatan"
                type="text"
                name="kelas_jabatan"
                value="{{ old('kelas_jabatan') }}"
                required
                placeholder="Contoh: XI RPL 2 / XII TKJ 1 / Guru Produktif"
                class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-[#3B49DF] focus:ring-2 focus:ring-[#3B49DF]/20 transition-all @error('kelas_jabatan') border-rose-400 @enderror"
            />
            <x-input-error :messages="$errors->get('kelas_jabatan')" class="mt-1" />
        </div>

        <!-- No. HP / WhatsApp -->
        <div>
            <label for="no_hp" class="block text-xs font-semibold text-slate-700 mb-1.5">
                Nomor HP / WhatsApp (Opsional)
            </label>
            <input
                id="no_hp"
                type="text"
                name="no_hp"
                value="{{ old('no_hp') }}"
                placeholder="Contoh: 081234567890"
                class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-[#3B49DF] focus:ring-2 focus:ring-[#3B49DF]/20 transition-all @error('no_hp') border-rose-400 @enderror"
            />
            <x-input-error :messages="$errors->get('no_hp')" class="mt-1" />
        </div>

        <div class="pt-2">
            <button
                type="submit"
                class="w-full py-2.5 px-4 bg-[#3B49DF] hover:bg-[#2F3CC7] text-white text-sm font-semibold rounded-xl shadow-md shadow-blue-600/20 transition-all"
            >
                Simpan Identitas & Masuk Dashboard
            </button>
        </div>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="mt-4 text-center">
        @csrf
        <button type="submit" class="text-xs font-medium text-slate-400 hover:text-rose-600 transition-colors">
            Batal & Keluar dari akun ini
        </button>
    </form>
</x-guest-layout>
