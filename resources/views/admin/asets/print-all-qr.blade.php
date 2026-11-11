<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cetak Massal Stiker QR Aset — SiLab</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            .print-grid { gap: 12px !important; }
            .print-card { break-inside: avoid; border: 1px solid #000 !important; }
        }
    </style>
</head>
<body class="bg-slate-100 p-6 min-h-screen">
    <!-- Top Action Toolbar -->
    <div class="no-print max-w-5xl mx-auto mb-6 flex items-center justify-between bg-white p-4 rounded-xl shadow-xs border border-slate-200">
        <div>
            <h1 class="text-sm font-bold text-slate-800">Cetak Massal Label Stiker QR ({{ $asets->count() }} Perangkat)</h1>
            <p class="text-xs text-slate-500">Tata letak dioptimalkan untuk cetak kertas stiker A4 (2 kolom).</p>
        </div>
        <div class="space-x-2">
            <button onclick="window.print()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-sm">
                🖨️ Cetak Semua Halaman
            </button>
            <button onclick="window.close()" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-medium rounded-lg">
                Tutup
            </button>
        </div>
    </div>

    <!-- Stickers Grid -->
    <div class="print-grid max-w-5xl mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 gap-4">
        @foreach($asets as $aset)
            <div class="print-card bg-white border-2 border-slate-800 rounded-xl p-3.5 text-slate-900 shadow-xs flex flex-col justify-between">
                <!-- Header -->
                <div class="flex items-center justify-between border-b border-slate-800 pb-1.5 mb-2">
                    <div class="flex items-center space-x-1.5">
                        <div class="w-5 h-5 bg-slate-900 text-white rounded font-bold text-[10px] flex items-center justify-center">
                            SL
                        </div>
                        <span class="text-[11px] font-extrabold tracking-wider uppercase">SiLab — Lab Sekolah</span>
                    </div>
                    <span class="text-[10px] font-bold text-slate-600">{{ $aset->lab->kode }}</span>
                </div>

                <!-- Body -->
                <div class="flex items-center gap-3">
                    <div class="w-24 h-24 bg-white p-0.5 rounded border border-slate-300 shrink-0 flex items-center justify-center">
                        {!! $qrSvgs[$aset->id] ?? '' !!}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-mono text-sm font-extrabold text-slate-950 truncate">
                            {{ $aset->kode_aset }}
                        </div>
                        <div class="text-xs font-bold text-slate-800 line-clamp-1 mt-0.5">
                            {{ $aset->nama }}
                        </div>
                        <div class="text-[10px] text-slate-600 truncate mt-0.5">
                            {{ $aset->lab->nama }}
                        </div>
                        <div class="text-[9px] text-slate-500 truncate">
                            {{ $aset->kategori->nama }} {{ $aset->merek ? '• ' . $aset->merek : '' }}
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="mt-2 pt-1 border-t border-slate-200 flex items-center justify-between text-[9px] text-slate-500 font-medium">
                    <span>📷 Scan QR untuk Lapor Kerusakan</span>
                    <span class="font-mono">SiLab</span>
                </div>
            </div>
        @endforeach
    </div>
</body>
</html>

