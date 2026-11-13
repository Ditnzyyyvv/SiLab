<?php

namespace App\Services;

use App\Models\Aset;
use App\Models\Tiket;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanService
{
    /**
     * Membangun query laporan aset berdasarkan filter (FR-29).
     */
    public function getLaporanAsetQuery(array $filters): Builder
    {
        $query = Aset::with(['lab', 'kategori']);

        if (! empty($filters['lab_id'])) {
            $query->where('lab_id', $filters['lab_id']);
        }

        if (! empty($filters['kategori_id'])) {
            $query->where('kategori_id', $filters['kategori_id']);
        }

        if (! empty($filters['kondisi'])) {
            $query->where('kondisi', $filters['kondisi']);
        }

        return $query->orderBy('kode_aset');
    }

    /**
     * Membangun query laporan perbaikan / tiket berdasarkan filter (FR-30).
     */
    public function getLaporanPerbaikanQuery(array $filters): Builder
    {
        $query = Tiket::with(['aset.lab', 'pelapor', 'teknisi']);

        if (! empty($filters['tanggal_mulai'])) {
            $query->whereDate('created_at', '>=', $filters['tanggal_mulai']);
        }

        if (! empty($filters['tanggal_selesai'])) {
            $query->whereDate('created_at', '<=', $filters['tanggal_selesai']);
        }

        if (! empty($filters['lab_id'])) {
            $query->whereHas('aset', function ($q) use ($filters) {
                $q->where('lab_id', $filters['lab_id']);
            });
        }

        if (! empty($filters['teknisi_id'])) {
            $query->where('teknisi_id', $filters['teknisi_id']);
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['prioritas'])) {
            $query->where('prioritas', $filters['prioritas']);
        }

        return $query->latest('created_at');
    }

    /**
     * Generate berkas PDF Laporan Aset (FR-29).
     */
    public function generateAsetPdf(array $filters)
    {
        $asets = $this->getLaporanAsetQuery($filters)->get();
        $tanggalCetak = now()->isoFormat('D MMMM Y, HH:mm');

        $pdf = Pdf::loadView('admin.laporan.pdf.aset', compact('asets', 'filters', 'tanggalCetak'))
            ->setPaper('a4', 'landscape');

        return $pdf;
    }

    /**
     * Stream unduhan file Excel / Spreadsheet Laporan Aset (FR-29).
     */
    public function exportAsetExcel(array $filters): StreamedResponse
    {
        $asets = $this->getLaporanAsetQuery($filters)->get();
        $filename = 'Laporan_Aset_SiLab_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($asets) {
            $output = fopen('php://output', 'w');

            // UTF-8 BOM untuk kompatibilitas Microsoft Excel
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

            // Judul Dokumen
            fputcsv($output, ['LAPORAN DATA INVENTARIS ASET LABORATORIUM SEKOLAH (SILAB)']);
            fputcsv($output, ['Tanggal Ekspor: ' . now()->isoFormat('D MMMM Y, HH:mm') . ' WIB']);
            fputcsv($output, []); // Baris kosong

            // Header Tabel
            fputcsv($output, [
                'No',
                'Kode Aset',
                'Nama Perangkat',
                'Laboratorium',
                'Kategori',
                'Merek',
                'Spesifikasi Teknis',
                'Tanggal Perolehan',
                'Kondisi Fisik',
            ]);

            // Baris Data
            foreach ($asets as $index => $aset) {
                fputcsv($output, [
                    $index + 1,
                    $aset->kode_aset,
                    $aset->nama,
                    $aset->lab->nama ?? '-',
                    $aset->kategori->nama ?? '-',
                    $aset->merek ?? '-',
                    $aset->spesifikasi ?? '-',
                    $aset->tanggal_perolehan ? $aset->tanggal_perolehan->format('d/m/Y') : '-',
                    $aset->kondisi,
                ]);
            }

            fclose($output);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ]);
    }

    /**
     * Generate berkas PDF Laporan Perbaikan (FR-30).
     */
    public function generatePerbaikanPdf(array $filters)
    {
        $tikets = $this->getLaporanPerbaikanQuery($filters)->get();
        $tanggalCetak = now()->isoFormat('D MMMM Y, HH:mm');

        $pdf = Pdf::loadView('admin.laporan.pdf.perbaikan', compact('tikets', 'filters', 'tanggalCetak'))
            ->setPaper('a4', 'landscape');

        return $pdf;
    }

    /**
     * Stream unduhan file Excel / Spreadsheet Laporan Perbaikan (FR-30).
     */
    public function exportPerbaikanExcel(array $filters): StreamedResponse
    {
        $tikets = $this->getLaporanPerbaikanQuery($filters)->get();
        $filename = 'Laporan_Perbaikan_SiLab_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($tikets) {
            $output = fopen('php://output', 'w');

            // UTF-8 BOM untuk kompatibilitas Microsoft Excel
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

            // Judul Dokumen
            fputcsv($output, ['LAPORAN PEMELIHARAAN DAN PERBAIKAN ASET LABORATORIUM SEKOLAH (SILAB)']);
            fputcsv($output, ['Tanggal Ekspor: ' . now()->isoFormat('D MMMM Y, HH:mm') . ' WIB']);
            fputcsv($output, []); // Baris kosong

            // Header Tabel
            fputcsv($output, [
                'No',
                'Nomor Tiket',
                'Tanggal Lapor',
                'Nama Pelapor',
                'Tipe Pelapor',
                'Kontak Pelapor',
                'Kode Aset',
                'Nama Perangkat',
                'Laboratorium',
                'Keluhan Kerusakan',
                'Prioritas',
                'Status Akhir',
                'Teknisi Penanggung Jawab',
                'Tanggal Disposisi',
                'Tanggal Selesai',
                'Tanggal Konfirmasi',
            ]);

            // Baris Data
            foreach ($tikets as $index => $t) {
                fputcsv($output, [
                    $index + 1,
                    $t->nomor_tiket,
                    $t->created_at->format('d/m/Y H:i'),
                    $t->nama_pelapor,
                    $t->isGuest() ? 'Tamu (QR Scan)' : 'Member Login',
                    $t->kontak_pelapor ?? '-',
                    $t->aset->kode_aset ?? '-',
                    $t->aset->nama ?? '-',
                    $t->aset->lab->nama ?? '-',
                    $t->judul,
                    $t->prioritas ?? 'Belum Diatur',
                    $t->status,
                    $t->teknisi->nama ?? 'Belum Ditugaskan',
                    $t->tanggal_disposisi ? $t->tanggal_disposisi->format('d/m/Y H:i') : '-',
                    $t->tanggal_selesai ? $t->tanggal_selesai->format('d/m/Y H:i') : '-',
                    $t->dikonfirmasi_at ? $t->dikonfirmasi_at->format('d/m/Y H:i') : '-',
                ]);
            }

            fclose($output);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ]);
    }
}
