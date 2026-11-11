<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Stiker QR — {{ $aset->kode_aset }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            .print-card { border: 1px solid #333 !important; box-shadow: none !important; }
        }
    </style>
</head>
<body class="bg-slate-100 p-8 min-h-screen flex flex-col items-center">
    <!-- Action Bar (Hidden on print) -->
    <div class="no-print max-w-md w-full mb-6 flex items-center justify-between bg-white p-4 rounded-xl shadow-xs border border-slate-200">
        <span class="text-xs font-semibold text-slate-700">Label Siap Cetak (1 Perangkat)</span>
        <div class="space-x-2">
            <button onclick="window.print()" class="px-3.5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-sm">
                🖨️ Cetak Label
            </button>
            <button onclick="window.close()" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-medium rounded-lg">
                Tutup
            </button>
        </div>
    </div>

    <!-- Sticker Card (Compact for Physical Tag) -->
    <div class="print-card w-[360px] bg-white border-2 border-slate-800 rounded-xl p-4 shadow-md text-slate-900">
        <!-- Header -->
        <div class="flex items-center justify-between border-b-2 border-slate-800 pb-2 mb-3">
            <div class="flex items-center space-x-2">
                <div class="w-6 h-6 bg-slate-900 text-white rounded font-bold text-xs flex items-center justify-center">
                    SL
                </div>
                <span class="text-xs font-extrabold tracking-wider uppercase">SiLab — Inventaris Lab</span>
            </div>
            <span class="text-[10px] font-bold text-slate-600 uppercase">{{ $aset->lab->kode }}</span>
        </div>

        <!-- Body -->
        <div class="flex items-center gap-4">
            <!-- QR Code -->
            <div class="w-28 h-28 bg-white p-1 rounded-lg border border-slate-300 shrink-0 flex items-center justify-center">
                {!! $qrSvg !!}
            </div>

            <!-- Details -->
            <div class="flex-1 min-w-0">
                <div class="font-mono text-base font-extrabold tracking-tight text-slate-950 truncate">
                    {{ $aset->kode_aset }}
                </div>
                <div class="text-xs font-bold text-slate-800 line-clamp-2 mt-0.5 leading-snug">
                    {{ $aset->nama }}
                </div>
                <div class="text-[10px] text-slate-600 mt-1">
                    {{ $aset->lab->nama }}
                </div>
                <div class="text-[9px] text-slate-500 truncate">
                    {{ $aset->kategori->nama }} {{ $aset->merek ? '• ' . $aset->merek : '' }}
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-3 pt-2 border-t border-slate-200 flex items-center justify-between text-[10px] text-slate-500 font-medium">
            <span>📷 Pindai QR untuk Lapor Kerusakan</span>
            <span class="font-mono text-[9px]">v1.1</span>
        </div>
    </div>
</body>
</html>
