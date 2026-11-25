<?php

namespace App\Http\Controllers;

use App\Http\Requests\GuestLaporRequest;
use App\Models\Aset;
use App\Models\Lab;
use App\Models\Tiket;
use App\Services\TiketService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PublicLaporController extends Controller
{
    protected TiketService $tiketService;

    public function __construct(TiketService $tiketService)
    {
        $this->tiketService = $tiketService;
    }

    /**
     * Halaman Beranda Publik: Langsung menampilkan formulir pengaduan kerusakan aset tanpa login.
     */
    public function home(): View
    {
        $labs = Lab::orderBy('nama')->get(['id', 'kode', 'nama', 'lokasi']);
        $asets = Aset::with('lab:id,nama,lokasi')
            ->orderBy('lab_id')
            ->orderBy('kode_aset')
            ->get(['id', 'kode_aset', 'nama', 'lab_id', 'kondisi']);

        return view('welcome', compact('labs', 'asets'));
    }

    /**
     * Simpan pengaduan kerusakan yang dikirim langsung dari formulir Beranda tanpa login.
     */
    public function storePublic(GuestLaporRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $fotos = $request->file('fotos', []);

        // Jika pelapor ternyata sedang dalam keadaan login, kaitkan akunnya
        $pelapor = Auth::user();

        $tiket = $this->tiketService->createTiket($data, $fotos, $pelapor);

        // Catat nomor tiket pada session pelapor agar hanya pengirim (atau pemilik/admin) yang bisa membuka halaman sukses
        $recentTikets = $request->session()->get('recent_guest_tikets', []);
        $recentTikets[] = $tiket->nomor_tiket;
        $request->session()->put('recent_guest_tikets', array_values(array_unique($recentTikets)));

        return redirect()->route('lapor.success', ['nomor_tiket' => $tiket->nomor_tiket])
            ->with('success', 'Laporan kerusakan Anda berhasil dikirim.');
    }

    /**
     * Halaman Publik: Formulir Lapor Cepat Kerusakan via Scan QR (Alur 1 / FR-13).
     * Dapat diakses siapa saja di lab tanpa kewajiban login.
     */
    public function create(string $kode_aset): View
    {
        $aset = Aset::with(['lab', 'kategori'])->where('kode_aset', $kode_aset)->firstOrFail();

        return view('lapor.form', compact('aset'));
    }

    /**
     * Simpan pengaduan kerusakan dari scan QR tanpa login.
     */
    public function store(GuestLaporRequest $request, string $kode_aset): RedirectResponse
    {
        $aset = Aset::where('kode_aset', $kode_aset)->firstOrFail();

        $data = $request->validated();
        $data['aset_id'] = $aset->id;

        $fotos = $request->file('fotos', []);

        // Jika pelapor ternyata sedang dalam keadaan login, kaitkan akunnya
        $pelapor = Auth::user();

        $tiket = $this->tiketService->createTiket($data, $fotos, $pelapor);

        // Catat nomor tiket pada session pelapor agar hanya pengirim (atau pemilik/admin) yang bisa membuka halaman sukses
        $recentTikets = $request->session()->get('recent_guest_tikets', []);
        $recentTikets[] = $tiket->nomor_tiket;
        $request->session()->put('recent_guest_tikets', array_values(array_unique($recentTikets)));

        return redirect()->route('lapor.success', ['nomor_tiket' => $tiket->nomor_tiket])
            ->with('success', 'Laporan kerusakan Anda berhasil dikirim.');
    }

    /**
     * Halaman Konfirmasi Sukses Pelaporan (terproteksi berbasis session, pemilik, atau Admin).
     */
    public function success(Request $request, string $nomor_tiket): View
    {
        $tiket = Tiket::with(['aset.lab', 'fotos'])->where('nomor_tiket', $nomor_tiket)->firstOrFail();

        $recentTikets = $request->session()->get('recent_guest_tikets', []);
        $isSessionSubmitter = in_array($tiket->nomor_tiket, $recentTikets, true);
        $isOwner = Auth::check() && $tiket->pelapor_id === Auth::id();
        $isAdmin = Auth::check() && Auth::user()->role === 'admin';

        if (! $isSessionSubmitter && ! $isOwner && ! $isAdmin) {
            abort(403, 'Akses Ditolak: Halaman konfirmasi tiket ini hanya dapat dilihat oleh pelapor yang baru saja mengirimkannya atau pengelola berwenang.');
        }

        return view('lapor.success', compact('tiket'));
    }
}

