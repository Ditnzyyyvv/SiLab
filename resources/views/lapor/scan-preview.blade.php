<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pindai Aset: {{ $aset->nama }} — SiLab</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-slate-800 bg-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
        <div class="bg-indigo-600 p-6 text-white text-center">
            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-white/20 mb-2">
                Aset Terverifikasi
            </span>
            <h1 class="text-xl font-bold">{{ $aset->nama }}</h1>
            <p class="text-indigo-200 font-mono text-sm mt-1">{{ $aset->kode_aset }}</p>
        </div>

        <div class="p-6 space-y-4">
            <div class="space-y-2 text-sm">
                <div class="flex justify-between py-1 border-b border-slate-100">
                    <span class="text-slate-500">Laboratorium</span>
                    <span class="font-semibold text-slate-800">{{ $aset->lab->nama }} ({{ $aset->lab->lokasi }})</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-100">
                    <span class="text-slate-500">Kategori</span>
                    <span class="font-semibold text-slate-800">{{ $aset->kategori->nama }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-100">
                    <span class="text-slate-500">Merek / Seri</span>
                    <span class="font-semibold text-slate-800">{{ $aset->merek ?? '-' }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-100">
                    <span class="text-slate-500">Kondisi Terkini</span>
                    <span class="inline-flex px-2 py-0.5 rounded text-xs font-semibold
                        @if($aset->kondisi === 'Baik') bg-emerald-100 text-emerald-800
                        @elseif($aset->kondisi === 'Rusak Ringan') bg-yellow-100 text-yellow-800
                        @elseif($aset->kondisi === 'Rusak Berat') bg-rose-100 text-rose-800
                        @elseif($aset->kondisi === 'Dalam Perbaikan') bg-blue-100 text-blue-800
                        @else bg-gray-200 text-gray-800 @endif">
                        {{ $aset->kondisi }}
                    </span>
                </div>
            </div>

            <div class="p-4 rounded-xl bg-indigo-50 border border-indigo-100 text-xs text-indigo-900 text-center">
                <p class="font-semibold mb-1">🔗 Scan QR Berhasil Terhubung</p>
                <p>Formulir publik pengaduan cepat kerusakan tanpa login akan aktif penuh pada <strong>Fase 3 (Tiket Pengaduan)</strong>.</p>
            </div>

            <div class="pt-2">
                <a href="{{ url('/') }}" class="block w-full text-center py-2.5 px-4 rounded-xl text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</body>
</html>
