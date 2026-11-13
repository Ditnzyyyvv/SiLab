<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DisposisiTiketRequest;
use App\Http\Requests\TolakTiketRequest;
use App\Models\Lab;
use App\Models\Tiket;
use App\Models\User;
use App\Services\TiketService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TiketController extends Controller
{
    protected TiketService $tiketService;

    public function __construct(TiketService $tiketService)
    {
        $this->tiketService = $tiketService;
    }

    /**
     * Tampilkan seluruh tiket pengaduan dengan filter (FR-16).
     */
    public function index(Request $request): View
    {
        $query = Tiket::with(['aset.lab', 'pelapor', 'teknisi']);

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter prioritas
        if ($request->filled('prioritas')) {
            $query->where('prioritas', $request->prioritas);
        }

        // Filter laboratorium
        if ($request->filled('lab_id')) {
            $query->whereHas('aset', function ($q) use ($request) {
                $q->where('lab_id', $request->lab_id);
            });
        }

        // Filter tipe pelapor (Guest vs Member)
        if ($request->filled('tipe_pelapor')) {
            if ($request->tipe_pelapor === 'guest') {
                $query->whereNull('pelapor_id');
            } elseif ($request->tipe_pelapor === 'member') {
                $query->whereNotNull('pelapor_id');
            }
        }

        // Filter pencarian teks
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_tiket', 'like', "%{$search}%")
                  ->orWhere('judul', 'like', "%{$search}%")
                  ->orWhere('nama_pelapor', 'like', "%{$search}%")
                  ->orWhereHas('aset', function ($asetQuery) use ($search) {
                      $asetQuery->where('nama', 'like', "%{$search}%")
                                ->orWhere('kode_aset', 'like', "%{$search}%");
                  });
            });
        }

        $tikets = $query->latest()->paginate(10)->withQueryString();

        // Statistik ringkas untuk widget
        $statCounts = [
            'total' => Tiket::count(),
            'pending' => Tiket::where('status', 'Pending')->count(),
            'diproses' => Tiket::whereIn('status', ['Diproses', 'Menunggu Komponen/Sparepart'])->count(),
            'selesai' => Tiket::where('status', 'Selesai')->count(),
            'ditolak' => Tiket::where('status', 'Ditolak')->count(),
            'guest' => Tiket::whereNull('pelapor_id')->count(),
        ];

        $labs = Lab::orderBy('nama')->get();
        $teknisis = User::where('role', 'teknisi')->orderBy('nama')->get();

        return view('admin.tikets.index', compact('tikets', 'statCounts', 'labs', 'teknisis'));
    }

    /**
     * Tampilkan detail tiket pengaduan lengkap dengan log riwayat (FR-16, FR-17, FR-18).
     */
    public function show(Tiket $tiket): View
    {
        $tiket->load([
            'aset.lab.penanggungJawab',
            'aset.kategori',
            'pelapor',
            'teknisi',
            'fotos',
            'logs.user',
        ]);

        $teknisis = User::where('role', 'teknisi')->orderBy('nama')->get();

        return view('admin.tikets.show', compact('tiket', 'teknisis'));
    }

    /**
     * Validasi tiket dan tugaskan teknisi (Disposisi) (FR-17, FR-18).
     */
    public function disposisi(DisposisiTiketRequest $request, Tiket $tiket): RedirectResponse
    {
        try {
            $this->tiketService->disposisi(
                $tiket,
                (int) $request->teknisi_id,
                $request->prioritas,
                $request->catatan,
                Auth::user()
            );

            return redirect()->route('admin.tikets.show', $tiket)
                ->with('success', "Tiket {$tiket->nomor_tiket} berhasil didisposisikan ke teknisi.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Menolak tiket pengaduan dengan alasan (FR-17).
     */
    public function tolak(TolakTiketRequest $request, Tiket $tiket): RedirectResponse
    {
        try {
            $this->tiketService->tolak(
                $tiket,
                $request->alasan_penolakan,
                Auth::user()
            );

            return redirect()->route('admin.tikets.show', $tiket)
                ->with('success', "Tiket {$tiket->nomor_tiket} telah ditolak.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Konfirmasi akhir penutupan tiket tamu (tanpa login) oleh Admin (FR-19).
     */
    public function konfirmasiTamu(Request $request, Tiket $tiket): RedirectResponse
    {
        try {
            $this->tiketService->konfirmasiSelesaiAdmin($tiket, Auth::user());

            return redirect()->route('admin.tikets.show', $tiket)
                ->with('success', "Tiket tamu {$tiket->nomor_tiket} telah dikonfirmasi dan ditutup secara resmi.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }
}

