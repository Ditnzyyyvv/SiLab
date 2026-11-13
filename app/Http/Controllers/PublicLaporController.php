<?php

namespace App\Http\Controllers;

use App\Http\Requests\GuestLaporRequest;
use App\Models\Aset;
use App\Models\Tiket;
use App\Services\TiketService;
use Illuminate\Http\RedirectResponse;
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

        return redirect()->route('lapor.success', ['nomor_tiket' => $tiket->nomor_tiket])
            ->with('success', 'Laporan kerusakan Anda berhasil dikirim.');
    }

    /**
     * Halaman Konfirmasi Sukses Pelaporan untuk Tamu via Scan QR.
     */
    public function success(string $nomor_tiket): View
    {
        $tiket = Tiket::with(['aset.lab', 'fotos'])->where('nomor_tiket', $nomor_tiket)->firstOrFail();

        return view('lapor.success', compact('tiket'));
    }
}

