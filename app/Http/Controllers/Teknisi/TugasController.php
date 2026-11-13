<?php

namespace App\Http\Controllers\Teknisi;

use App\Http\Controllers\Controller;
use App\Http\Requests\TeknisiUpdateStatusRequest;
use App\Models\Tiket;
use App\Services\TiketService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TugasController extends Controller
{
    protected TiketService $tiketService;

    public function __construct(TiketService $tiketService)
    {
        $this->tiketService = $tiketService;
    }

    /**
     * Tampilkan daftar tugas yang ditugaskan kepada teknisi yang sedang login (FR-20).
     */
    public function index(Request $request): View
    {
        $query = Tiket::with(['aset.lab', 'pelapor'])
            ->where('teknisi_id', Auth::id());

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter prioritas
        if ($request->filled('prioritas')) {
            $query->where('prioritas', $request->prioritas);
        }

        // Filter search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_tiket', 'like', "%{$search}%")
                  ->orWhere('judul', 'like', "%{$search}%")
                  ->orWhereHas('aset', function ($asetQuery) use ($search) {
                      $asetQuery->where('nama', 'like', "%{$search}%")
                                ->orWhere('kode_aset', 'like', "%{$search}%");
                  });
            });
        }

        $tugas = $query->latest('tanggal_disposisi')->paginate(10)->withQueryString();

        // Counter untuk teknisi
        $statCounts = [
            'aktif' => Tiket::where('teknisi_id', Auth::id())->where('status', 'Diproses')->count(),
            'sparepart' => Tiket::where('teknisi_id', Auth::id())->where('status', 'Menunggu Komponen/Sparepart')->count(),
            'selesai' => Tiket::where('teknisi_id', Auth::id())->where('status', 'Selesai')->count(),
            'total' => Tiket::where('teknisi_id', Auth::id())->count(),
        ];

        return view('teknisi.tugas.index', compact('tugas', 'statCounts'));
    }

    /**
     * Tampilkan detail tugas perbaikan untuk teknisi (FR-20, FR-21, FR-22).
     */
    public function show(Tiket $tiket): View
    {
        // Pastikan teknisi hanya dapat membuka tugas yang ditugaskan kepadanya (FR-20)
        if ($tiket->teknisi_id !== Auth::id()) {
            abort(403, 'Akses Ditolak: Anda tidak ditugaskan untuk menangani tiket ini.');
        }

        $tiket->load([
            'aset.lab.penanggungJawab',
            'aset.kategori',
            'pelapor',
            'fotos',
            'logs.user',
        ]);

        return view('teknisi.tugas.show', compact('tiket'));
    }

    /**
     * Perbarui status penanganan dan catat log progres (FR-21, FR-22, FR-23, FR-24).
     */
    public function updateStatus(TeknisiUpdateStatusRequest $request, Tiket $tiket): RedirectResponse
    {
        // Pastikan teknisi yang login adalah yang ditugaskan
        if ($tiket->teknisi_id !== Auth::id()) {
            abort(403, 'Akses Ditolak: Anda tidak memiliki wewenang memperbarui tiket ini.');
        }

        try {
            $this->tiketService->updateStatusTeknisi(
                $tiket,
                $request->status,
                $request->catatan,
                $request->komponen_dibutuhkan,
                $request->file('foto_bukti'),
                Auth::user()
            );

            return redirect()->route('teknisi.tugas.show', $tiket)
                ->with('success', "Status tiket {$tiket->nomor_tiket} berhasil diperbarui menjadi {$request->status}.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }
}

