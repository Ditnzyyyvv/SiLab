<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\Kategori;
use App\Models\Lab;
use App\Models\Tiket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
    /**
     * Redirect authenticated users to their role-specific dashboard.
     */
    public function index(Request $request): RedirectResponse
    {
        return match ($request->user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'teknisi' => redirect()->route('teknisi.dashboard'),
            'member' => redirect()->route('member.dashboard'),
            default => abort(403, 'Role pengguna tidak dikenali.'),
        };
    }

    /**
     * Admin Dashboard view.
     */
    public function admin(): View
    {
        $totalAset = Aset::count();
        $asetBaik = Aset::where('kondisi', 'Baik')->count();
        $asetRusak = Aset::whereIn('kondisi', ['Rusak Ringan', 'Rusak Berat'])->count();
        $asetPerbaikan = Aset::where('kondisi', 'Dalam Perbaikan')->count();
        $totalLab = Lab::count();
        $totalKategori = Kategori::count();

        // Tiket metrics
        $pendingTiketCount = Tiket::where('status', 'Pending')->count();
        $diprosesTiketCount = Tiket::whereIn('status', ['Diproses', 'Menunggu Komponen/Sparepart'])->count();
        $selesaiTiketCount = Tiket::where('status', 'Selesai')->count();

        $recentAsets = Aset::with(['lab', 'kategori'])->latest()->take(5)->get();
        $recentTikets = Tiket::with(['aset.lab', 'pelapor', 'teknisi'])->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalAset',
            'asetBaik',
            'asetRusak',
            'asetPerbaikan',
            'totalLab',
            'totalKategori',
            'pendingTiketCount',
            'diprosesTiketCount',
            'selesaiTiketCount',
            'recentAsets',
            'recentTikets'
        ));
    }

    /**
     * Teknisi Dashboard view (FR-26).
     */
    public function teknisi(): View
    {
        $teknisiId = Auth::id();

        $tugasAktifCount = Tiket::where('teknisi_id', $teknisiId)->where('status', 'Diproses')->count();
        $tugasSparepartCount = Tiket::where('teknisi_id', $teknisiId)->where('status', 'Menunggu Komponen/Sparepart')->count();
        $tugasSelesaiBulanIni = Tiket::where('teknisi_id', $teknisiId)
            ->where('status', 'Selesai')
            ->whereMonth('tanggal_selesai', now()->month)
            ->whereYear('tanggal_selesai', now()->year)
            ->count();

        $recentTugas = Tiket::with(['aset.lab'])
            ->where('teknisi_id', $teknisiId)
            ->latest('tanggal_disposisi')
            ->take(5)
            ->get();

        return view('teknisi.dashboard', compact(
            'tugasAktifCount',
            'tugasSparepartCount',
            'tugasSelesaiBulanIni',
            'recentTugas'
        ));
    }

    /**
     * Member Dashboard view (FR-27).
     */
    public function member(): View
    {
        $userId = Auth::id();

        $pendingCount = Tiket::where('pelapor_id', $userId)->where('status', 'Pending')->count();
        $diprosesCount = Tiket::where('pelapor_id', $userId)->whereIn('status', ['Diproses', 'Menunggu Komponen/Sparepart'])->count();
        $selesaiCount = Tiket::where('pelapor_id', $userId)->where('status', 'Selesai')->count();
        $totalTiketSaya = Tiket::where('pelapor_id', $userId)->count();

        $recentTikets = Tiket::with('aset.lab')
            ->where('pelapor_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        return view('member.dashboard', compact(
            'pendingCount',
            'diprosesCount',
            'selesaiCount',
            'totalTiketSaya',
            'recentTikets'
        ));
    }
}

