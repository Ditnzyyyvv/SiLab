<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AsetRequest;
use App\Models\Aset;
use App\Models\Kategori;
use App\Models\Lab;
use App\Services\AsetService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AsetController extends Controller
{
    protected AsetService $asetService;

    public function __construct(AsetService $asetService)
    {
        $this->asetService = $asetService;
    }

    /**
     * Tampilkan daftar aset dengan filter lab, kategori, kondisi, dan pencarian (FR-09).
     */
    public function index(Request $request): View
    {
        $query = Aset::with(['lab', 'kategori']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kode_aset', 'like', "%{$search}%")
                  ->orWhere('merek', 'like', "%{$search}%");
            });
        }

        if ($request->filled('lab_id')) {
            $query->where('lab_id', $request->lab_id);
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        $asets = $query->latest()->paginate(10)->withQueryString();

        $labs = Lab::orderBy('nama')->get();
        $kategoris = Kategori::orderBy('nama')->get();
        $kondisiList = ['Baik', 'Rusak Ringan', 'Rusak Berat', 'Dalam Perbaikan', 'Tidak Layak Pakai'];

        return view('admin.asets.index', compact('asets', 'labs', 'kategoris', 'kondisiList'));
    }

    /**
     * Formulir tambah aset baru.
     */
    public function create(): View
    {
        $labs = Lab::orderBy('nama')->get();
        $kategoris = Kategori::orderBy('nama')->get();
        $kondisiList = ['Baik', 'Rusak Ringan', 'Rusak Berat', 'Dalam Perbaikan', 'Tidak Layak Pakai'];

        return view('admin.asets.create', compact('labs', 'kategoris', 'kondisiList'));
    }

    /**
     * Simpan data aset baru via AsetService (kode otomatis & foto).
     */
    public function store(AsetRequest $request): RedirectResponse
    {
        $foto = $request->file('foto');
        $aset = $this->asetService->createAset($request->validated(), $foto);

        return redirect()->route('admin.asets.show', $aset)
            ->with('success', "Aset {$aset->nama} ({$aset->kode_aset}) berhasil ditambahkan.");
    }

    /**
     * Tampilkan detail aset, riwayat perbaikan, dan pratinjau QR Code (FR-10).
     */
    public function show(Aset $aset): View
    {
        $aset->load(['lab.penanggungJawab', 'kategori']);
        $qrSvg = $this->asetService->generateQrSvg($aset, 220);

        return view('admin.asets.show', compact('aset', 'qrSvg'));
    }

    /**
     * Formulir ubah data aset.
     */
    public function edit(Aset $aset): View
    {
        $labs = Lab::orderBy('nama')->get();
        $kategoris = Kategori::orderBy('nama')->get();
        $kondisiList = ['Baik', 'Rusak Ringan', 'Rusak Berat', 'Dalam Perbaikan', 'Tidak Layak Pakai'];

        return view('admin.asets.edit', compact('aset', 'labs', 'kategoris', 'kondisiList'));
    }

    /**
     * Perbarui data aset.
     */
    public function update(AsetRequest $request, Aset $aset): RedirectResponse
    {
        $foto = $request->file('foto');
        $this->asetService->updateAset($aset, $request->validated(), $foto);

        return redirect()->route('admin.asets.show', $aset)
            ->with('success', 'Data aset berhasil diperbarui.');
    }

    /**
     * Hapus aset (soft delete).
     */
    public function destroy(Aset $aset): RedirectResponse
    {
        $this->asetService->deleteAset($aset);

        return redirect()->route('admin.asets.index')
            ->with('success', "Aset {$aset->nama} ({$aset->kode_aset}) berhasil dihapus.");
    }

    /**
     * Halaman cetak label stiker QR Code untuk 1 aset (FR-07b).
     */
    public function printQr(Aset $aset): View
    {
        $aset->load(['lab', 'kategori']);
        $qrSvg = $this->asetService->generateQrSvg($aset, 180);

        return view('admin.asets.print-qr', compact('aset', 'qrSvg'));
    }

    /**
     * Cetak massal label stiker QR Code (FR-07b).
     */
    public function printAllQr(Request $request): View
    {
        $query = Aset::with(['lab', 'kategori']);

        if ($request->filled('lab_id')) {
            $query->where('lab_id', $request->lab_id);
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        $asets = $query->orderBy('kode_aset')->get();

        $qrSvgs = [];
        foreach ($asets as $item) {
            $qrSvgs[$item->id] = $this->asetService->generateQrSvg($item, 140);
        }

        return view('admin.asets.print-all-qr', compact('asets', 'qrSvgs'));
    }
}
