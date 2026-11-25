<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Inventaris Aset Laboratorium</title>
    <style>
        @page {
            margin: 1.5cm 1.2cm 1.5cm 1.2cm;
            size: a4 landscape;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9pt;
            color: #1e293b;
            line-height: 1.35;
        }
        /* Kop Surat Resmi */
        .kop-table {
            width: 100%;
            border-bottom: 3px double #0f172a;
            padding-bottom: 8px;
            margin-bottom: 14px;
        }
        .kop-instansi {
            font-size: 11pt;
            font-weight: bold;
            color: #334155;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .kop-sekolah {
            font-size: 15pt;
            font-weight: 800;
            color: #0f172a;
            margin: 2px 0;
            text-transform: uppercase;
        }
        .kop-alamat {
            font-size: 8pt;
            color: #64748b;
        }
        /* Judul Laporan */
        .judul-laporan {
            text-align: center;
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 4px;
            color: #0f172a;
        }
        .subjudul-laporan {
            text-align: center;
            font-size: 8.5pt;
            color: #64748b;
            margin-bottom: 12px;
        }
        /* Filter Info & Info Bar */
        .info-bar {
            width: 100%;
            margin-bottom: 10px;
            font-size: 8pt;
        }
        .info-bar td {
            vertical-align: top;
        }
        /* Tabel Data */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        table.data-table th {
            background-color: #f1f5f9;
            color: #334155;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 7.5pt;
            border: 1px solid #cbd5e1;
            padding: 5px 6px;
            text-align: left;
        }
        table.data-table td {
            border: 1px solid #e2e8f0;
            padding: 5px 6px;
            font-size: 8pt;
            vertical-align: top;
        }
        table.data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .font-mono {
            font-family: "Courier New", Courier, monospace;
            font-weight: bold;
        }
        .badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 7pt;
            font-weight: bold;
        }
        .badge-baik { background-color: #dcfce7; color: #15803d; }
        .badge-rusak-ringan { background-color: #fef3c7; color: #b45309; }
        .badge-rusak-berat { background-color: #fee2e2; color: #b91c1c; }
        .badge-perbaikan { background-color: #dbeafe; color: #1d4ed8; }
        .badge-afkir { background-color: #f1f5f9; color: #475569; }

        /* Rekap Mini Table */
        .rekap-box {
            margin-bottom: 12px;
            border: 1px solid #cbd5e1;
            background-color: #f8fafc;
            padding: 6px 10px;
            font-size: 8pt;
        }
        /* Tanda Tangan */
        .ttd-table {
            width: 100%;
            margin-top: 20px;
            page-break-inside: avoid;
        }
        .ttd-table td {
            vertical-align: top;
            font-size: 8.5pt;
        }
    </style>
</head>
<body>

    @php
        $logoPath = public_path('images/silab-logo.png');
        $logoBase64 = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : null;
    @endphp

    <!-- KOP SURAT RESMI -->
    <table class="kop-table">
        <tr>
            @if($logoBase64)
                <td style="width: 72px; text-align: center; vertical-align: middle;">
                    <img src="{{ $logoBase64 }}" alt="Logo SiLab" style="width: 58px; height: 58px; object-fit: contain;" />
                </td>
            @endif
            <td style="text-align: center; vertical-align: middle;">
                <div class="kop-instansi">{{ config('silab.instansi') }}</div>
                <div class="kop-sekolah">{{ config('silab.nama_sekolah') }}</div>
                <div class="kop-alamat">{{ config('silab.alamat') }}</div>
            </td>
            @if($logoBase64)
                <td style="width: 72px;"></td>
            @endif
        </tr>
    </table>

    <!-- JUDUL LAPORAN -->
    <div class="judul-laporan">Laporan Inventaris Aset Laboratorium Sekolah</div>
    <div class="subjudul-laporan">Sistem Informasi Laboratorium (SiLab) — Tanggal Cetak: {{ $tanggalCetak }} WIB</div>

    <!-- PARAMETER FILTER -->
    <table class="info-bar">
        <tr>
            <td style="width: 50%;">
                <strong>Kriteria Filter Data:</strong><br>
                • Laboratorium: {{ !empty($filters['lab_id']) ? (\App\Models\Lab::find($filters['lab_id'])->nama ?? 'Semua') : 'Semua Lab' }}<br>
                • Kategori Aset: {{ !empty($filters['kategori_id']) ? (\App\Models\Kategori::find($filters['kategori_id'])->nama ?? 'Semua') : 'Semua Kategori' }}<br>
                • Kondisi Fisik: {{ !empty($filters['kondisi']) ? $filters['kondisi'] : 'Semua Kondisi' }}
            </td>
            <td style="width: 50%; text-align: right;">
                <strong>Total Aset Terdaftar:</strong> {{ $asets->count() }} Unit<br>
                <strong>Status Audit:</strong> Resmi Terverifikasi Sistem<br>
                <strong>Dokumen:</strong> Lampiran Inventarisasi Sarpras
            </td>
        </tr>
    </table>

    <!-- TABEL DATA ASET -->
    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 25px;">No</th>
                <th style="width: 85px;">Kode Aset</th>
                <th style="width: 130px;">Nama Perangkat</th>
                <th style="width: 100px;">Laboratorium</th>
                <th style="width: 90px;">Kategori</th>
                <th style="width: 90px;">Merek</th>
                <th>Spesifikasi Teknis</th>
                <th class="text-center" style="width: 70px;">Perolehan</th>
                <th class="text-center" style="width: 80px;">Kondisi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($asets as $index => $aset)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="font-mono">{{ $aset->kode_aset }}</td>
                    <td><strong>{{ $aset->nama }}</strong></td>
                    <td>{{ $aset->lab->nama ?? '-' }}</td>
                    <td>{{ $aset->kategori->nama ?? '-' }}</td>
                    <td>{{ $aset->merek ?? '-' }}</td>
                    <td>{{ $aset->spesifikasi ?? '-' }}</td>
                    <td class="text-center">{{ $aset->tanggal_perolehan ? $aset->tanggal_perolehan->format('d/m/Y') : '-' }}</td>
                    <td class="text-center">
                        @php
                            $badgeClass = match($aset->kondisi) {
                                'Baik' => 'badge-baik',
                                'Rusak Ringan' => 'badge-rusak-ringan',
                                'Rusak Berat' => 'badge-rusak-berat',
                                'Dalam Perbaikan' => 'badge-perbaikan',
                                default => 'badge-afkir'
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ $aset->kondisi }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center" style="padding: 16px; color: #94a3b8;">
                        Tidak ada data inventaris aset yang sesuai dengan kriteria filter.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- TANDA TANGAN PEJABAT BERWENANG -->
    <table class="ttd-table">
        <tr>
            <td style="width: 60%;">
                <div style="font-size: 8pt; color: #64748b;">
                    <em>Catatan: Dokumen ini dicetak secara otomatis melalui Sistem Informasi Laboratorium (SiLab) SMK Negeri dan sah dijadikan lampiran audit aset sekolah.</em>
                </div>
            </td>
            <td style="width: 40%; text-align: center;">
                <div>Ditetapkan di: {{ config('silab.kota') }}</div>
                <div>Pada tanggal: {{ now()->isoFormat('D MMMM Y') }}</div>
                <div style="margin-top: 6px; font-weight: bold;">Kepala Pengelola Laboratorium & Sarpras,</div>
                <div style="height: 55px;"></div>
                <div style="font-weight: bold; text-decoration: underline;">{{ config('silab.kepala_lab_nama') }}</div>
                <div style="font-size: 8pt; color: #475569;">{{ config('silab.kepala_lab_nip') }}</div>
            </td>
        </tr>
    </table>

</body>
</html>

