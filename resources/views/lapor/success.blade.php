<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Terkirim: {{ $tiket->nomor_tiket }} — SiLab</title>
    <link rel="icon" type="image/png" href="{{ asset('images/silab-logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-slate-800 bg-slate-100 min-h-screen py-10 px-4 flex flex-col justify-center items-center">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl border border-slate-200/80 overflow-hidden text-center p-8 space-y-6">
        <!-- Success Icon -->
        <div class="w-20 h-20 rounded-full bg-emerald-100 text-emerald-600 mx-auto flex items-center justify-center shadow-inner">
            <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
            </svg>
        </div>

        <div>
            <h1 class="text-2xl font-bold text-slate-900">Laporan Berhasil Terkirim!</h1>
            <p class="text-xs text-slate-500 mt-1">Terima kasih atas laporan kerusakan yang Anda sampaikan.</p>
        </div>

        <!-- Ticket Card Box -->
        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 text-left space-y-3">
            <div>
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block">Nomor Tiket Anda</span>
                <span class="font-mono text-xl font-extrabold text-indigo-700 tracking-tight block mt-0.5">
                    {{ $tiket->nomor_tiket }}
                </span>
            </div>

            <div class="pt-2 border-t border-slate-200/60 text-xs space-y-1.5">
                <div class="flex justify-between">
                    <span class="text-slate-500">Perangkat:</span>
                    <span class="font-semibold text-slate-800 text-right truncate max-w-[200px]">{{ $tiket->aset->nama }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Kode Aset:</span>
                    <span class="font-mono font-bold text-slate-700">{{ $tiket->aset->kode_aset }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Laboratorium:</span>
                    <span class="font-semibold text-slate-800">{{ $tiket->aset->lab->nama }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Pelapor:</span>
                    <span class="font-semibold text-slate-800">{{ $tiket->nama_pelapor }}</span>
                </div>
                <div class="flex justify-between items-center pt-1">
                    <span class="text-slate-500">Status Awal:</span>
                    <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-slate-200 text-slate-700">
                        {{ $tiket->status }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Reminder Notice (PRD Alur 1) -->
        @if(Auth::check() && $tiket->pelapor_id === Auth::id())
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-950 text-left leading-relaxed">
                <p class="font-bold mb-1">✓ Terhubung ke Akun Anda</p>
                <p>
                    Laporan ini otomatis tercatat di akun <strong>{{ Auth::user()->nama }}</strong>. Anda dapat memantau progres penanganan dan notifikasi langsung dari dashboard.
                </p>
            </div>
        @else
            <div class="p-4 rounded-xl bg-indigo-50 border border-indigo-100 text-xs text-indigo-950 text-left leading-relaxed">
                <p class="font-bold mb-1">ℹ️ Informasi Pelapor Tamu</p>
                <p>
                    Laporan ini telah masuk ke sistem antrean Admin Lab. Karena Anda melapor cepat tanpa login, riwayat tiket tidak tersimpan dalam akun dashboard. Silakan simpan / tangkap layar (screenshot) nomor tiket ini.
                </p>
            </div>
        @endif

        <!-- Action Links -->
        <div class="space-y-2 pt-2">
            @if(Auth::check() && Auth::user()->role === 'member' && $tiket->pelapor_id === Auth::id())
                <a href="{{ route('member.tikets.show', $tiket) }}" class="block w-full py-3 px-4 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-sm">
                    Lihat Progres di Tiket Saya →
                </a>
                <a href="{{ url('/') }}" class="block w-full py-2.5 px-4 rounded-xl text-xs font-semibold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 transition-colors">
                    Kembali ke Beranda
                </a>
            @else
                <a href="{{ url('/') }}" class="block w-full py-3 px-4 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-sm">
                    Kembali ke Beranda
                </a>
                @guest
                    <a href="{{ route('register') }}" class="block w-full py-2.5 px-4 rounded-xl text-xs font-semibold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 transition-colors">
                        Daftar Akun Member (Untuk Pantau Riwayat)
                    </a>
                @endguest
            @endif
        </div>
    </div>
</body>
</html>

