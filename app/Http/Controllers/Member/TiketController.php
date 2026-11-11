<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Http\Requests\MemberReopenTiketRequest;
use App\Http\Requests\MemberTiketRequest;
use App\Models\Aset;
use App\Models\Tiket;
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
     * Tampilkan riwayat tiket milik member yang login ("Tiket Saya" - FR-15).
     */
    public function index(Request $request): View
    {
        $query = Tiket::with(['aset.lab', 'fotos'])
            ->where('pelapor_id', Auth::id());

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

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

        $tikets = $query->latest()->paginate(10)->withQueryString();

        $statusList = ['Pending', 'Diproses', 'Menunggu Komponen/Sparepart', 'Selesai', 'Rusak Total', 'Ditolak'];

        return view('member.tikets.index', compact('tikets', 'statusList'));
    }

    /**
     * Formulir buat tiket pengaduan oleh member yang login.
     */
    public function create(Request $request): View
    {
        $asets = Aset::with(['lab', 'kategori'])->orderBy('nama')->get();
        $selectedAsetId = $request->query('aset_id');

        if (! $selectedAsetId && $request->filled('kode_aset')) {
            $found = Aset::where('kode_aset', $request->query('kode_aset'))->first();
            $selectedAsetId = $found?->id;
        }

        return view('member.tikets.create', compact('asets', 'selectedAsetId'));
    }

    /**
     * Simpan tiket pengaduan dari member login.
     */
    public function store(MemberTiketRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $fotos = $request->file('fotos', []);

        $tiket = $this->tiketService->createTiket($data, $fotos, Auth::user());

        return redirect()->route('member.tikets.show', $tiket)
            ->with('success', "Tiket pengaduan {$tiket->nomor_tiket} berhasil diajukan.");
    }

    /**
     * Tampilkan detail tiket pengaduan beserta visual timeline status (FR-15).
     */
    public function show(Tiket $tiket): View
    {
        // Pastikan hanya member pemilik tiket yang bisa mengakses
        if ($tiket->pelapor_id !== Auth::id()) {
            abort(403, 'Akses Ditolak: Anda hanya dapat melihat tiket yang Anda laporkan sendiri.');
        }

        $tiket->load(['aset.lab.penanggungJawab', 'aset.kategori', 'fotos', 'logs.user', 'teknisi']);

        return view('member.tikets.show', compact('tiket'));
    }

    /**
     * Konfirmasi hasil perbaikan (Setuju) oleh Member pelapor (FR-19).
     */
    public function konfirmasi(Request $request, Tiket $tiket): RedirectResponse
    {
        if ($tiket->pelapor_id !== Auth::id()) {
            abort(403, 'Akses Ditolak: Anda hanya dapat mengonfirmasi tiket milik Anda sendiri.');
        }

        try {
            $this->tiketService->konfirmasiSelesaiMember($tiket, Auth::user());

            return redirect()->route('member.tikets.show', $tiket)
                ->with('success', "Terima kasih! Penyelesaian tiket {$tiket->nomor_tiket} telah berhasil Anda konfirmasi.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Buka kembali tiket jika hasil perbaikan belum tuntas oleh Member pelapor (FR-19).
     */
    public function reopen(MemberReopenTiketRequest $request, Tiket $tiket): RedirectResponse
    {
        if ($tiket->pelapor_id !== Auth::id()) {
            abort(403, 'Akses Ditolak: Anda hanya dapat membuka kembali tiket milik Anda sendiri.');
        }

        try {
            $this->tiketService->bukaKembaliMember($tiket, $request->alasan, Auth::user());

            return redirect()->route('member.tikets.show', $tiket)
                ->with('success', "Tiket {$tiket->nomor_tiket} telah dibuka kembali dan dikembalikan ke penanganan teknisi.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }
}
