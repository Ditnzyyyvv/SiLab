<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lapor Kerusakan Aset: {{ $aset->kode_aset }} — SiLab</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-slate-800 bg-slate-100 min-h-screen py-8 px-4 flex flex-col justify-center items-center">
    <div class="max-w-xl w-full space-y-6">
        <!-- Brand Header -->
        <div class="text-center">
            <div class="inline-flex items-center space-x-2 justify-center mb-2">
                <div class="w-9 h-9 rounded-xl bg-indigo-600 text-white font-bold text-lg flex items-center justify-center shadow-md shadow-indigo-600/30">
                    SL
                </div>
                <span class="text-xl font-extrabold text-slate-900 tracking-tight">SiLab</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900">Formulir Lapor Cepat Kerusakan</h1>
            <p class="text-xs text-slate-500 mt-1">Pindai QR Aset Laboratorium Sekolah</p>
        </div>

        <!-- Asset Target Card -->
        <div class="bg-white p-5 rounded-2xl shadow-xs border border-slate-200/80 flex items-center gap-4">
            <div class="w-14 h-14 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 shrink-0">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <span class="inline-block font-mono text-xs font-extrabold text-indigo-700 uppercase bg-indigo-50 px-2 py-0.5 rounded">
                    {{ $aset->kode_aset }}
                </span>
                <h2 class="text-base font-bold text-slate-900 truncate mt-0.5">{{ $aset->nama }}</h2>
                <p class="text-xs text-slate-500 truncate">
                    {{ $aset->lab->nama }} • Ruang {{ $aset->lab->lokasi }}
                </p>
            </div>
        </div>

        <!-- Role Option Banner (Guest vs Login) -->
        <div class="bg-amber-50/70 border border-amber-200/80 rounded-xl p-4 text-xs text-amber-900 flex items-start space-x-3">
            <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="leading-relaxed">
                <p class="font-bold mb-0.5">Pelaporan Tanpa Wajib Login</p>
                <p>
                    Anda dapat langsung mengisi form di bawah ini. Jika Anda ingin riwayat laporan ini tercatat dan dapat Anda pantau progresnya di dashboard pribadi, silakan 
                    <a href="{{ route('login') }}" class="font-bold underline text-amber-950">Masuk sebagai Member</a> terlebih dahulu.
                </p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-slate-200/80">
            <form method="POST" action="{{ route('lapor.store', $aset->kode_aset) }}" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <!-- Nama Pelapor -->
                <div>
                    <label for="nama_pelapor" class="block text-sm font-semibold text-slate-700 mb-1">
                        Nama Lengkap Anda <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="nama_pelapor" 
                        name="nama_pelapor" 
                        value="{{ old('nama_pelapor', Auth::user()?->nama ?? Auth::user()?->name) }}" 
                        placeholder="Contoh: Muhammad Reza / Siswa RPL 2"
                        class="w-full text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('nama_pelapor') border-rose-500 @enderror"
                        required
                    />
                    @error('nama_pelapor')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kontak Pelapor -->
                <div>
                    <label for="kontak_pelapor" class="block text-sm font-semibold text-slate-700 mb-1">
                        No. WhatsApp / Kontak (Opsional)
                    </label>
                    <input 
                        type="text" 
                        id="kontak_pelapor" 
                        name="kontak_pelapor" 
                        value="{{ old('kontak_pelapor', Auth::user()?->no_hp) }}" 
                        placeholder="Contoh: 081234567890 (agar teknisi dapat menghubungi jika butuh klarifikasi)"
                        class="w-full text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('kontak_pelapor') border-rose-500 @enderror"
                    />
                    @error('kontak_pelapor')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Judul Kerusakan -->
                <div>
                    <label for="judul" class="block text-sm font-semibold text-slate-700 mb-1">
                        Judul / Ringkasan Kerusakan <span class="text-rose-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="judul" 
                        name="judul" 
                        value="{{ old('judul') }}" 
                        placeholder="Contoh: PC tidak mau menyala saat tombol power ditekan"
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
                        Kronologi / Gejala Kerusakan <span class="text-rose-500">*</span>
                    </label>
                    <textarea 
                        id="deskripsi" 
                        name="deskripsi" 
                        rows="4" 
                        placeholder="Jelaskan apa yang terjadi, tanda-tanda kerusakan (lampu indikator, bunyi bip, layar bergaris, bau gosong, dll)..."
                        class="w-full text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('deskripsi') border-rose-500 @enderror"
                        required
                    >{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Lampiran Foto Bukti (FR-13) -->
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
                    <p class="text-xs text-slate-400 mt-1">Ambil foto dari kamera HP yang memperlihatkan kondisi fisik perangkat/pesan error.</p>
                    @error('fotos')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                    @error('fotos.*')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button 
                        type="submit" 
                        class="w-full py-3.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-xl shadow-md shadow-indigo-600/30 transition-all flex items-center justify-center space-x-2"
                    >
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        <span>Kirim Laporan Pengaduan</span>
                    </button>
                </div>
            </form>
        </div>

        <div class="text-center text-xs text-slate-400">
            &copy; {{ date('Y') }} SiLab — Sistem Pelaporan Kerusakan Aset Sekolah
        </div>
    </div>
</body>
</html>

