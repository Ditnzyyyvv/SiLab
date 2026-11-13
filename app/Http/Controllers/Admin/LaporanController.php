<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aset;
use App\Models\Kategori;
use App\Models\Lab;
use App\Models\Tiket;
use App\Models\User;
use App\Services\LaporanService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanController extends Controller
{
    protected LaporanService $laporanService;

    public function __construct(LaporanService $laporanService)
    {
        $this->laporanService = $laporanService;
    }

    /**
     * Hub utama modul laporan & rekapitulasi (FR-29, FR-30).
     */
    public function index(): View
    {
        $totalAset = Aset::count();
        $totalPerbaikan = Tiket::count();
        $perbaikanSelesai = Tiket::where('status', 'Selesai')->count();

        return view('admin.laporan.index', compact('totalAset', 'totalPerbaikan', 'perbaikanSelesai'));
    }

    /**
     * Pratinjau tabel Laporan Aset dengan filter (FR-29).
     */
    public function aset(Request $request): View
    {
        $filters = $request->only(['lab_id', 'kategori_id', 'kondisi']);
        $query = $this->laporanService->getLaporanAsetQuery($filters);

        // Rekapitulasi kondisi terfilter
        $rekapKondisi = [
            'total' => (clone $query)->count(),
            'baik' => (clone $query)->where('kondisi', 'Baik')->count(),
            'rusak' => (clone $query)->whereIn('kondisi', ['Rusak Ringan', 'Rusak Berat'])->count(),
            'perbaikan' => (clone $query)->where('kondisi', 'Dalam Perbaikan')->count(),
            'afkir' => (clone $query)->where('kondisi', 'Tidak Layak Pakai')->count(),
        ];

        $asets = $query->paginate(15)->withQueryString();
        $labs = Lab::orderBy('nama')->get();
        $kategoris = Kategori::orderBy('nama')->get();
        $kondisiList = ['Baik', 'Rusak Ringan', 'Rusak Berat', 'Dalam Perbaikan', 'Tidak Layak Pakai'];

        return view('admin.laporan.aset', compact('asets', 'rekapKondisi', 'labs', 'kategoris', 'kondisiList', 'filters'));
    }

    /**
     * Ekspor Laporan Aset ke format PDF (FR-29).
     */
    public function asetPdf(Request $request)
    {
        $filters = $request->only(['lab_id', 'kategori_id', 'kondisi']);
        $pdf = $this->laporanService->generateAsetPdf($filters);

        return $pdf->download('Laporan_Aset_SiLab_' . now()->format('Ymd') . '.pdf');
    }

    /**
     * Ekspor Laporan Aset ke format Excel / Spreadsheet (FR-29).
     */
    public function asetExcel(Request $request): StreamedResponse
    {
        $filters = $request->only(['lab_id', 'kategori_id', 'kondisi']);

        return $this->laporanService->exportAsetExcel($filters);
    }

    /**
     * Pratinjau tabel Laporan Perbaikan dengan filter periode, lab, teknisi, status (FR-30).
     */
    public function perbaikan(Request $request): View
    {
        $filters = $request->only(['tanggal_mulai', 'tanggal_selesai', 'lab_id', 'teknisi_id', 'status', 'prioritas']);
        $query = $this->laporanService->getLaporanPerbaikanQuery($filters);

        // Rekapitulasi status tiket terfilter
        $rekapStatus = [
            'total' => (clone $query)->count(),
            'selesai' => (clone $query)->where('status', 'Selesai')->count(),
            'proses' => (clone $query)->whereIn('status', ['Diproses', 'Menunggu Komponen/Sparepart'])->count(),
            'pending' => (clone $query)->where('status', 'Pending')->count(),
            'rusak_total' => (clone $query)->where('status', 'Rusak Total')->count(),
        ];

        $tikets = $query->paginate(15)->withQueryString();
        $labs = Lab::orderBy('nama')->get();
        $teknisis = User::where('role', 'teknisi')->orderBy('nama')->get();
        $statusList = ['Pending', 'Diproses', 'Menunggu Komponen/Sparepart', 'Selesai', 'Rusak Total', 'Ditolak'];
        $prioritasList = ['Rendah', 'Sedang', 'Tinggi'];

        return view('admin.laporan.perbaikan', compact('tikets', 'rekapStatus', 'labs', 'teknisis', 'statusList', 'prioritasList', 'filters'));
    }

    /**
     * Ekspor Laporan Perbaikan ke format PDF (FR-30).
     */
    public function perbaikanPdf(Request $request)
    {
        $filters = $request->only(['tanggal_mulai', 'tanggal_selesai', 'lab_id', 'teknisi_id', 'status', 'prioritas']);
        $pdf = $this->laporanService->generatePerbaikanPdf($filters);

        return $pdf->download('Laporan_Perbaikan_SiLab_' . now()->format('Ymd') . '.pdf');
    }

    /**
     * Ekspor Laporan Perbaikan ke format Excel / Spreadsheet (FR-30).
     */
    public function perbaikanExcel(Request $request): StreamedResponse
    {
        $filters = $request->only(['tanggal_mulai', 'tanggal_selesai', 'lab_id', 'teknisi_id', 'status', 'prioritas']);

        return $this->laporanService->exportPerbaikanExcel($filters);
    }
}
