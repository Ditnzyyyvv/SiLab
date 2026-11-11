<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LabRequest;
use App\Models\Lab;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LabController extends Controller
{
    /**
     * Tampilkan daftar laboratorium dengan jumlah aset dan penanggung jawab.
     */
    public function index(Request $request): View
    {
        $query = Lab::with('penanggungJawab')->withCount('asets');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kode', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }

        $labs = $query->latest()->paginate(10)->withQueryString();

        return view('admin.labs.index', compact('labs'));
    }

    /**
     * Formulir tambah lab baru.
     */
    public function create(): View
    {
        $penanggungJawabs = User::whereIn('role', ['admin', 'teknisi'])
            ->orderBy('nama')
            ->get();

        return view('admin.labs.create', compact('penanggungJawabs'));
    }

    /**
     * Simpan data laboratorium.
     */
    public function store(LabRequest $request): RedirectResponse
    {
        Lab::create($request->validated());

        return redirect()->route('admin.labs.index')
            ->with('success', 'Laboratorium berhasil ditambahkan.');
    }

    /**
     * Formulir edit laboratorium.
     */
    public function edit(Lab $lab): View
    {
        $penanggungJawabs = User::whereIn('role', ['admin', 'teknisi'])
            ->orderBy('nama')
            ->get();

        return view('admin.labs.edit', compact('lab', 'penanggungJawabs'));
    }

    /**
     * Perbarui data laboratorium.
     */
    public function update(LabRequest $request, Lab $lab): RedirectResponse
    {
        $lab->update($request->validated());

        return redirect()->route('admin.labs.index')
            ->with('success', 'Data laboratorium berhasil diperbarui.');
    }

    /**
     * Hapus laboratorium (soft delete).
     */
    public function destroy(Lab $lab): RedirectResponse
    {
        if ($lab->asets()->count() > 0) {
            return redirect()->route('admin.labs.index')
                ->with('error', 'Tidak dapat menghapus lab karena masih memiliki aset terdaftar.');
        }

        $lab->delete();

        return redirect()->route('admin.labs.index')
            ->with('success', 'Laboratorium berhasil dihapus.');
    }
}

