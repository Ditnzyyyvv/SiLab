<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SiLab — Lapor Cepat Kerusakan & Pencatatan Aset Lab Sekolah</title>
        <link rel="icon" type="image/png" href="{{ asset('images/silab-logo.png') }}">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-slate-800 bg-slate-50 min-h-screen flex flex-col justify-between">
        @php
            $asetsPayload = $asets->map(fn ($aset) => [
                'id' => $aset->id,
                'kode_aset' => $aset->kode_aset,
                'nama' => $aset->nama,
                'lab_id' => $aset->lab_id,
                'lab_nama' => $aset->lab?->nama ?? '-',
                'kondisi' => $aset->kondisi,
            ])->values();
            $sampleQrKode = $asets->first()?->kode_aset ?? 'AST-RPL-0001';
        @endphp

        <script>
            window.__SILAB_ASETS__ = @json($asetsPayload);
        </script>

        <!-- Top Navigation -->
        <header class="w-full bg-white border-b border-slate-200/80 sticky top-0 z-30 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center space-x-3">
                    <div class="h-11 w-11 rounded-xl bg-white border border-slate-200 p-1 flex items-center justify-center shadow-sm">
                        <img src="{{ asset('images/silab-logo.png') }}" alt="Logo SiLab" class="h-full w-full object-contain" />
                    </div>
                    <div>
                        <span class="text-lg font-bold text-slate-900 tracking-tight">SiLab</span>
                        <span class="text-xs text-slate-500 block -mt-1 font-medium">Aset Laboratorium Sekolah</span>
                    </div>
                </a>

                <div class="flex items-center space-x-2 sm:space-x-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-3.5 sm:px-4 py-2 text-xs sm:text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm transition-colors">
                            Buka Dashboard →
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center px-3 sm:px-4 py-2 text-xs sm:text-sm font-semibold text-slate-700 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition-colors">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center px-3 sm:px-4 py-2 text-xs sm:text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm transition-colors">
                            Daftar Member
                        </a>
                    @endauth
                </div>
            </div>
        </header>

        <!-- Main Content: Split Hero + Direct Public Complaint Form -->
        <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">
                <!-- Left Column: Info, Steps & Quick Links -->
                <div class="lg:col-span-5 space-y-6">
                    <div class="space-y-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                            ⚡ Lapor Kerusakan Tanpa Wajib Login
                        </span>
                        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">
                            Ada Alat Lab Rusak? <span class="text-indigo-600">Lapor Langsung di Sini</span>
                        </h1>
                        <p class="text-sm sm:text-base text-slate-600 leading-relaxed">
                            Siswa dan guru dapat langsung memilih perangkat di formulir samping atau memindai stiker QR Code pada aset fisik <strong>tanpa perlu registrasi atau login</strong>.
                        </p>
                    </div>

                    <!-- Quick Jump Button for Mobile (360px) -->
                    <div class="lg:hidden">
                        <a href="#form-pengaduan" class="w-full inline-flex items-center justify-center px-5 py-3 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-md shadow-indigo-600/25 transition-all">
                            Isi Formulir Pengaduan Sekarang ↓
                        </a>
                    </div>

                    <!-- 3 Compact Steps -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
                        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Alur Penanganan Cepat</h2>
                        <div class="space-y-3">
                            <div class="flex items-start space-x-3">
                                <div class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-700 font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">1</div>
                                <div class="text-xs sm:text-sm text-slate-600">
                                    <span class="font-semibold text-slate-900 block">Pilih Aset / Scan Stiker QR</span>
                                    Tentukan lab & perangkat yang rusak, lalu isi gejala kerusakan beserta foto bukti.
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-700 font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">2</div>
                                <div class="text-xs sm:text-sm text-slate-600">
                                    <span class="font-semibold text-slate-900 block">Validasi Admin & Disposisi Teknisi</span>
                                    Admin memverifikasi laporan dan menugaskan teknisi lab sesuai prioritas.
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-700 font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">3</div>
                                <div class="text-xs sm:text-sm text-slate-600">
                                    <span class="font-semibold text-slate-900 block">Perbaikan Tuntas & Terdokumentasi</span>
                                    Member yang login dapat memantau timeline status di menu <em>Tiket Saya</em>.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- QR Scan Simulation Link -->
                    <div class="bg-indigo-50/70 border border-indigo-200/80 rounded-2xl p-4 flex items-center justify-between gap-3">
                        <div class="text-xs text-indigo-950">
                            <p class="font-bold">Punya Stiker QR di Meja Lab?</p>
                            <p class="text-indigo-800/90 mt-0.5">Scan lewat kamera HP atau coba simulasi langsung:</p>
                        </div>
                        <a href="{{ route('lapor.create', $sampleQrKode) }}" class="shrink-0 inline-flex items-center px-3 py-2 text-xs font-semibold text-indigo-700 bg-white hover:bg-indigo-50 border border-indigo-200 rounded-lg shadow-sm transition-colors">
                            Simulasi QR →
                        </a>
                    </div>

                    <!-- Demo Credentials Banner -->
                    <div class="p-4 rounded-2xl bg-slate-900 text-white space-y-2.5">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-bold text-indigo-400 uppercase tracking-wider">Akun Demo (Opsional Login)</span>
                            <span class="text-[11px] text-slate-300">Password: <code class="px-1.5 py-0.5 rounded bg-slate-800 text-amber-300 font-mono font-bold">password</code></span>
                        </div>
                        <div class="flex flex-wrap gap-1.5 text-[11px] font-mono">
                            <span class="px-2.5 py-1 rounded-md bg-slate-800 border border-slate-700 text-indigo-300">admin@silab.sch.id</span>
                            <span class="px-2.5 py-1 rounded-md bg-slate-800 border border-slate-700 text-amber-300">teknisi@silab.sch.id</span>
                            <span class="px-2.5 py-1 rounded-md bg-slate-800 border border-slate-700 text-emerald-300">member@silab.sch.id</span>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Direct Asset Complaint Form -->
                <div id="form-pengaduan" class="lg:col-span-7">
                    <div
                        class="bg-white p-5 sm:p-8 rounded-2xl shadow-md border border-slate-200/90"
                        x-data="{
                            asets: window.__SILAB_ASETS__ || [],
                            selectedLabId: @js((string) old('lab_id', '')),
                            selectedAsetId: @js((string) old('aset_id', '')),
                            searchAset: '',
                            get filteredAsets() {
                                return this.asets.filter(a => {
                                    const matchLab = !this.selectedLabId || String(a.lab_id) === String(this.selectedLabId);
                                    const q = this.searchAset.trim().toLowerCase();
                                    const matchQuery = !q
                                        || a.nama.toLowerCase().includes(q)
                                        || a.kode_aset.toLowerCase().includes(q)
                                        || a.lab_nama.toLowerCase().includes(q);
                                    return matchLab && matchQuery;
                                });
                            },
                            onLabChange() {
                                if (this.selectedAsetId && !this.filteredAsets.some(a => String(a.id) === String(this.selectedAsetId))) {
                                    this.selectedAsetId = '';
                                }
                            }
                        }"
                    >
                        <div class="flex items-start justify-between gap-3 pb-5 mb-5 border-b border-slate-100">
                            <div>
                                <h2 class="text-lg sm:text-xl font-bold text-slate-900">Formulir Pengaduan Kerusakan Aset</h2>
                                <p class="text-xs text-slate-500 mt-1">
                                    Isi formulir di bawah ini untuk langsung membuat tiket laporan kerusakan ke Admin Lab.
                                </p>
                            </div>
                            <span class="shrink-0 inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold {{ Auth::check() ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                {{ Auth::check() ? 'Mode Login' : 'Tanpa Login' }}
                            </span>
                        </div>

                        @if($errors->any())
                            <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-800">
                                <p class="font-bold mb-1">Mohon periksa kembali isian formulir Anda:</p>
                                <ul class="list-disc list-inside space-y-0.5">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('lapor.store-public') }}" enctype="multipart/form-data" class="space-y-4">
                            @csrf

                            <!-- Filter Lab & Cari Aset -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 p-3.5 rounded-xl bg-slate-50 border border-slate-200/80">
                                <div>
                                    <label for="lab_id" class="block text-xs font-semibold text-slate-700 mb-1">
                                        1. Filter Laboratorium
                                    </label>
                                    <select
                                        id="lab_id"
                                        name="lab_id"
                                        x-model="selectedLabId"
                                        @change="onLabChange()"
                                        class="w-full text-sm rounded-lg border border-slate-300 bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    >
                                        <option value="">Semua Laboratorium</option>
                                        @foreach($labs as $lab)
                                            <option value="{{ $lab->id }}" {{ old('lab_id') == $lab->id ? 'selected' : '' }}>
                                                {{ $lab->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="search_aset" class="block text-xs font-semibold text-slate-700 mb-1">
                                        Cari Cepat Kode / Nama Aset
                                    </label>
                                    <input
                                        type="text"
                                        id="search_aset"
                                        x-model="searchAset"
                                        placeholder="Ketik misal: PC 01, Proyektor, AST-RPL..."
                                        class="w-full text-sm rounded-lg border border-slate-300 bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    />
                                </div>

                                <!-- Pilih Perangkat Aset -->
                                <div class="sm:col-span-2">
                                    <label for="aset_id" class="block text-xs font-semibold text-slate-700 mb-1">
                                        2. Pilih Perangkat Aset Laboratorium <span class="text-rose-500">*</span>
                                    </label>
                                    <select
                                        id="aset_id"
                                        name="aset_id"
                                        x-model="selectedAsetId"
                                        class="w-full text-sm rounded-lg border border-slate-300 bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('aset_id') border-rose-500 @enderror"
                                        required
                                    >
                                        <option value="">-- Pilih Perangkat Aset yang Rusak --</option>
                                        <template x-for="aset in filteredAsets" :key="aset.id">
                                            <option
                                                :value="aset.id"
                                                :selected="String(aset.id) === String(selectedAsetId)"
                                                x-text="`[${aset.kode_aset}] ${aset.nama} — ${aset.lab_nama} (${aset.kondisi})`"
                                            ></option>
                                        </template>
                                    </select>
                                    <p class="text-[11px] text-slate-500 mt-1" x-show="filteredAsets.length === 0" style="display: none;">
                                        Tidak ada perangkat yang cocok dengan filter pencarian.
                                    </p>
                                    @error('aset_id')
                                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Identitas Pelapor (Nama & Kontak) -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="nama_pelapor" class="block text-sm font-semibold text-slate-700 mb-1">
                                        Nama Lengkap & Kelas / Jabatan <span class="text-rose-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        id="nama_pelapor"
                                        name="nama_pelapor"
                                        value="{{ old('nama_pelapor', Auth::user()?->nama ?? Auth::user()?->name) }}"
                                        placeholder="Contoh: Raka Pratama - XI RPL 2"
                                        class="w-full text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('nama_pelapor') border-rose-500 @enderror"
                                        required
                                    />
                                    @error('nama_pelapor')
                                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="kontak_pelapor" class="block text-sm font-semibold text-slate-700 mb-1">
                                        No. WhatsApp / Kontak (Opsional)
                                    </label>
                                    <input
                                        type="text"
                                        id="kontak_pelapor"
                                        name="kontak_pelapor"
                                        value="{{ old('kontak_pelapor', Auth::user()?->no_hp) }}"
                                        placeholder="Contoh: 081234567890"
                                        class="w-full text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('kontak_pelapor') border-rose-500 @enderror"
                                    />
                                    @error('kontak_pelapor')
                                        <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
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
                                    placeholder="Contoh: PC tidak mau menyala / Layar monitor bergaris hijau"
                                    class="w-full text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('judul') border-rose-500 @enderror"
                                    required
                                />
                                @error('judul')
                                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
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
                                    rows="3"
                                    placeholder="Jelaskan gejala yang terjadi (lampu indikator mati, bunyi bip, kabel longgar, pesan error, dll)..."
                                    class="w-full text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('deskripsi') border-rose-500 @enderror"
                                    required
                                >{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Lampiran Foto Bukti -->
                            <div>
                                <label for="fotos" class="block text-sm font-semibold text-slate-700 mb-1">
                                    Foto Bukti Kerusakan (Opsional — Maks. 3 Foto, JPG/PNG, Maks. 2MB)
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
                                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                                @enderror
                                @error('fotos.*')
                                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Submit CTA -->
                            <div class="pt-2 space-y-3">
                                <button
                                    type="submit"
                                    class="w-full py-3.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-xl shadow-md shadow-indigo-600/25 transition-all flex items-center justify-center space-x-2"
                                >
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                    </svg>
                                    <span>{{ Auth::check() ? 'Kirim Laporan Pengaduan' : 'Kirim Laporan Sekarang (Tanpa Login)' }}</span>
                                </button>

                                @guest
                                    <p class="text-center text-xs text-slate-500 leading-relaxed">
                                        Ingin memantau status perbaikan & riwayat tiket Anda?
                                        <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:text-indigo-800 underline">Masuk</a>
                                        atau
                                        <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-800 underline">Daftar Member</a>
                                        (Opsional).
                                    </p>
                                @else
                                    <p class="text-center text-xs text-emerald-700">
                                        Anda masuk sebagai <strong>{{ Auth::user()->nama }}</strong>. Laporan akan otomatis ditautkan ke akun Anda.
                                    </p>
                                @endguest
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="border-t border-slate-200 bg-white py-6 mt-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-xs text-slate-500">
                &copy; {{ date('Y') }} SiLab — Sistem Informasi Pemeliharaan dan Pencatatan Aset Laboratorium Sekolah.
            </div>
        </footer>
    </body>
</html>
