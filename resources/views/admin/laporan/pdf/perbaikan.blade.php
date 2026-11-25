<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pemeliharaan & Perbaikan Aset Laboratorium</title>
    <style>
        @page {
            margin: 1.5cm 1.2cm 1.5cm 1.2cm;
            size: a4 landscape;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8.5pt;
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
            font-size: 7.8pt;
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
            font-size: 6.8pt;
            font-weight: bold;
        }
        .badge-pending { background-color: #fef3c7; color: #b45309; }
        .badge-proses { background-color: #dbeafe; color: #1d4ed8; }
        .badge-sparepart { background-color: #f3e8ff; color: #6b21a8; }
        .badge-selesai { background-color: #dcfce7; color: #15803d; }
        .badge-rusak-total { background-color: #fee2e2; color: #b91c1c; }
        .badge-ditolak { background-color: #f1f5f9; color: #475569; }

        .badge-prio-tinggi { background-color: #fee2e2; color: #b91c1c; }
        .badge-prio-sedang { background-color: #fef3c7; color: #b45309; }
        .badge-prio-rendah { background-color: #f1f5f9; color: #475569; }

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
    <div class="judul-laporan">Laporan Pemeliharaan & Perbaikan Aset Laboratorium</div>
    <div class="subjudul-laporan">Sistem Informasi Laboratorium (SiLab) — Tanggal Cetak: {{ $tanggalCetak }} WIB</div>

    <!-- PARAMETER FILTER -->
    <table class="info-bar">
        <tr>
            <td style="width: 50%;">
                <strong>Kriteria Filter Data:</strong><br>
                • Periode Tanggal: {{ !empty($filters['tanggal_mulai']) ? $filters['tanggal_mulai'] : 'Awal' }} s/d {{ !empty($filters['tanggal_selesai']) ? $filters['tanggal_selesai'] : 'Sekarang' }}<br>
                • Laboratorium: {{ !empty($filters['lab_id']) ? (\App\Models\Lab::find($filters['lab_id'])->nama ?? 'Semua') : 'Semua Lab' }}<br>
                • Teknisi: {{ !empty($filters['teknisi_id']) ? (\App\Models\User::find($filters['teknisi_id'])->nama ?? 'Semua') : 'Semua Teknisi' }}<br>
                • Status / Prioritas: {{ !empty($filters['status']) ? $filters['status'] : 'Semua Status' }} / {{ !empty($filters['prioritas']) ? $filters['prioritas'] : 'Semua Prioritas' }}
            </td>
            <td style="width: 50%; text-align: right;">
                <strong>Total Pengaduan Terfilter:</strong> {{ $tikets->count() }} Tiket<br>
                <strong>Selesai Tuntas:</strong> {{ $tikets->where('status', 'Selesai')->count() }} Tiket<br>
                <strong>Tingkat Penyelesaian:</strong> {{ $tikets->count() > 0 ? round(($tikets->where('status', 'Selesai')->count() / $tikets->count()) * 100, 1) : 0 }}%
            </td>
        </tr>
    </table>

    <!-- TABEL DATA PERBAIKAN -->
    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 20px;">No</th>
                <th style="width: 75px;">No Tiket</th>
                <th style="width: 65px;">Tgl Lapor</th>
                <th style="width: 95px;">Pelapor</th>
                <th style="width: 120px;">Aset & Laboratorium</th>
                <th>Keluhan Kerusakan</th>
                <th class="text-center" style="width: 50px;">Prioritas</th>
                <th class="text-center" style="width: 70px;">Status</th>
                <th style="width: 85px;">Teknisi</th>
                <th class="text-center" style="width: 65px;">Tgl Selesai</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tikets as $index => $t)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="font-mono">{{ $t->nomor_tiket }}</td>
                    <td>{{ $t->created_at->format('d/m/Y') }}<br><small style="color: #64748b;">{{ $t->created_at->format('H:i') }}</small></td>
                    <td>
                        <strong>{{ $t->nama_pelapor }}</strong><br>
                        <small style="color: #64748b;">{{ $t->isGuest() ? 'Tamu (QR)' : 'Member' }}</small>
                    </td>
                    <td>
                        <strong>{{ $t->aset->nama ?? '-' }}</strong><br>
                        <small style="color: #475569;">{{ $t->aset->kode_aset ?? '-' }} ({{ $t->aset->lab->nama ?? '-' }})</small>
                    </td>
                    <td>
                        <strong>{{ $t->judul }}</strong><br>
                        <small style="color: #64748b;">{{ \Illuminate\Support\Str::limit($t->deskripsi, 80) }}</small>
                    </td>
                    <td class="text-center">
                        @php
                            $prioClass = match($t->prioritas) {
                                'Tinggi' => 'badge-prio-tinggi',
                                'Sedang' => 'badge-prio-sedang',
                                default => 'badge-prio-rendah'
                            };
                        @endphp
                        <span class="badge {{ $prioClass }}">{{ $t->prioritas ?? '-' }}</span>
                    </td>
                    <td class="text-center">
                        @php
                            $statusClass = match($t->status) {
                                'Pending' => 'badge-pending',
                                'Diproses' => 'badge-proses',
                                'Menunggu Komponen/Sparepart' => 'badge-sparepart',
                                'Selesai' => 'badge-selesai',
                                'Rusak Total' => 'badge-rusak-total',
                                default => 'badge-ditolak'
                            };
                        @endphp
                        <span class="badge {{ $statusClass }}">{{ $t->status }}</span>
                    </td>
                    <td>{{ $t->teknisi->nama ?? 'Belum Ditugaskan' }}</td>
                    <td class="text-center">{{ $t->tanggal_selesai ? $t->tanggal_selesai->format('d/m/Y') : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center" style="padding: 16px; color: #94a3b8;">
                        Tidak ada data tiket perbaikan yang sesuai dengan kriteria filter.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- TANDA TANGAN -->
    <table class="ttd-table">
        <tr>
            <td style="width: 50%; text-align: center;">
                <div>Mengetahui,</div>
                <div style="font-weight: bold;">Koordinator Tim Teknisi Laboratorium,</div>
                <div style="height: 55px;"></div>
                <div style="font-weight: bold; text-decoration: underline;">{{ config('silab.koordinator_teknisi_nama') }}</div>
                <div style="font-size: 8pt; color: #475569;">{{ config('silab.koordinator_teknisi_nip') }}</div>
            </td>
            <td style="width: 50%; text-align: center;">
                <div>Ditetapkan di: {{ config('silab.kota') }}, {{ now()->isoFormat('D MMMM Y') }}</div>
                <div style="font-weight: bold;">Kepala Laboratorium & Sarpras,</div>
                <div style="height: 55px;"></div>
                <div style="font-weight: bold; text-decoration: underline;">{{ config('silab.kepala_lab_nama') }}</div>
                <div style="font-size: 8pt; color: #475569;">{{ config('silab.kepala_lab_nip') }}</div>
            </td>
        </tr>
    </table>

</body>
</html>

