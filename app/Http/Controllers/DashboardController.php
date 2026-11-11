<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\Kategori;
use App\Models\Lab;
use Illuminate\Http\Request;
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

        $recentAsets = Aset::with(['lab', 'kategori'])->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalAset',
            'asetBaik',
            'asetRusak',
            'asetPerbaikan',
            'totalLab',
            'totalKategori',
            'recentAsets'
        ));
    }

    /**
     * Teknisi Dashboard view.
     */
    public function teknisi(): View
    {
        return view('teknisi.dashboard');
    }

    /**
     * Member Dashboard view.
     */
    public function member(): View
    {
        return view('member.dashboard');
    }
}

