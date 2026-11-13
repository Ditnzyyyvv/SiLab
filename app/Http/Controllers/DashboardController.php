<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\Kategori;
use App\Models\Lab;
use App\Models\Tiket;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

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
     * Admin Dashboard view (FR-25).
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

        // FR-25: Tiket prioritas tinggi yang belum selesai
        $tiketPrioritasTinggi = Tiket::with(['aset.lab', 'teknisi'])
            ->where('prioritas', 'Tinggi')
            ->whereNotIn('status', ['Selesai', 'Rusak Total', 'Ditolak'])
            ->latest()
            ->take(5)
            ->get();

        // FR-25: Beban kerja per teknisi
        $teknisiWorkload = User::where('role', 'teknisi')
            ->withCount([
                'tiketsSebagaiTeknisi as aktif_count' => function ($q) {
                    $q->whereIn('status', ['Diproses', 'Menunggu Komponen/Sparepart']);
                },
                'tiketsSebagaiTeknisi as selesai_count' => function ($q) {
                    $q->where('status', 'Selesai');
                },
            ])
            ->get();

        // FR-25: Distribusi kondisi aset untuk Chart
        $kondisiCounts = [
            'Baik' => $asetBaik,
            'Rusak Ringan' => Aset::where('kondisi', 'Rusak Ringan')->count(),
            'Rusak Berat' => Aset::where('kondisi', 'Rusak Berat')->count(),
            'Dalam Perbaikan' => $asetPerbaikan,
            'Tidak Layak Pakai' => Aset::where('kondisi', 'Tidak Layak Pakai')->count(),
        ];

        // FR-25: Tiket per status untuk Chart
        $statusCounts = [
            'Pending' => $pendingTiketCount,
            'Diproses' => Tiket::where('status', 'Diproses')->count(),
            'Menunggu Sparepart' => Tiket::where('status', 'Menunggu Komponen/Sparepart')->count(),
            'Selesai' => $selesaiTiketCount,
            'Rusak Total' => Tiket::where('status', 'Rusak Total')->count(),
            'Ditolak' => Tiket::where('status', 'Ditolak')->count(),
        ];

        // FR-25: Grafik tren tiket masuk & selesai per bulan (6 bulan terakhir)
        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months->push([
                'label' => $date->isoFormat('MMM Y'),
                'year' => $date->year,
                'month' => $date->month,
            ]);
        }

        $monthlyChart = [
            'labels' => $months->pluck('label')->toArray(),
            'masuk' => $months->map(function ($m) {
                return Tiket::whereYear('created_at', $m['year'])
                    ->whereMonth('created_at', $m['month'])
                    ->count();
            })->toArray(),
            'selesai' => $months->map(function ($m) {
                return Tiket::where('status', 'Selesai')
                    ->whereYear('tanggal_selesai', $m['year'])
                    ->whereMonth('tanggal_selesai', $m['month'])
                    ->count();
            })->toArray(),
        ];

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
            'tiketPrioritasTinggi',
            'teknisiWorkload',
            'kondisiCounts',
            'statusCounts',
            'monthlyChart',
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

        // Chart komposisi status tugas
        $tugasStatusCounts = [
            'Diproses' => $tugasAktifCount,
            'Menunggu Sparepart' => $tugasSparepartCount,
            'Selesai' => Tiket::where('teknisi_id', $teknisiId)->where('status', 'Selesai')->count(),
            'Rusak Total' => Tiket::where('teknisi_id', $teknisiId)->where('status', 'Rusak Total')->count(),
        ];

        // Chart tren bulanan tugas selesai (6 bulan terakhir)
        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months->push([
                'label' => $date->isoFormat('MMM Y'),
                'year' => $date->year,
                'month' => $date->month,
            ]);
        }

        $teknisiMonthlyChart = [
            'labels' => $months->pluck('label')->toArray(),
            'selesai' => $months->map(function ($m) use ($teknisiId) {
                return Tiket::where('teknisi_id', $teknisiId)
                    ->where('status', 'Selesai')
                    ->whereYear('tanggal_selesai', $m['year'])
                    ->whereMonth('tanggal_selesai', $m['month'])
                    ->count();
            })->toArray(),
        ];

        $recentTugas = Tiket::with(['aset.lab'])
            ->where('teknisi_id', $teknisiId)
            ->latest('tanggal_disposisi')
            ->take(5)
            ->get();

        return view('teknisi.dashboard', compact(
            'tugasAktifCount',
            'tugasSparepartCount',
            'tugasSelesaiBulanIni',
            'tugasStatusCounts',
            'teknisiMonthlyChart',
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

        // Chart proporsi status tiket saya (FR-27)
        $memberStatusCounts = [
            'Pending' => $pendingCount,
            'Diproses' => Tiket::where('pelapor_id', $userId)->where('status', 'Diproses')->count(),
            'Menunggu Sparepart' => Tiket::where('pelapor_id', $userId)->where('status', 'Menunggu Komponen/Sparepart')->count(),
            'Selesai' => $selesaiCount,
            'Rusak Total' => Tiket::where('pelapor_id', $userId)->where('status', 'Rusak Total')->count(),
            'Ditolak' => Tiket::where('pelapor_id', $userId)->where('status', 'Ditolak')->count(),
        ];

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
            'memberStatusCounts',
            'recentTikets'
        ));
    }
}
