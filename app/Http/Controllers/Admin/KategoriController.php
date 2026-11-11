<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\KategoriRequest;
use App\Models\Kategori;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KategoriController extends Controller
{
    /**
     * Tampilkan daftar kategori aset dengan jumlah aset masing-masing.
     */
    public function index(Request $request): View
    {
        $query = Kategori::withCount('asets');

        if ($request->filled('search')) {
            $query->where('nama', 'like', "%{$request->search}%");
        }

        $kategoris = $query->latest()->paginate(10)->withQueryString();

        return view('admin.kategoris.index', compact('kategoris'));
    }

    /**
     * Formulir tambah kategori.
     */
    public function create(): View
    {
        return view('admin.kategoris.create');
    }

    /**
     * Simpan kategori baru.
     */
    public function store(KategoriRequest $request): RedirectResponse
    {
        Kategori::create($request->validated());

        return redirect()->route('admin.kategoris.index')
            ->with('success', 'Kategori aset berhasil ditambahkan.');
    }

    /**
     * Formulir edit kategori.
     */
    public function edit(Kategori $kategori): View
    {
        return view('admin.kategoris.edit', compact('kategori'));
    }

    /**
     * Perbarui data kategori.
     */
    public function update(KategoriRequest $request, Kategori $kategori): RedirectResponse
    {
        $kategori->update($request->validated());

        return redirect()->route('admin.kategoris.index')
            ->with('success', 'Kategori aset berhasil diperbarui.');
    }

    /**
     * Hapus kategori aset.
     */
    public function destroy(Kategori $kategori): RedirectResponse
    {
        if ($kategori->asets()->count() > 0) {
            return redirect()->route('admin.kategoris.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh aset.');
        }

        $kategori->delete();

        return redirect()->route('admin.kategoris.index')
            ->with('success', 'Kategori aset berhasil dihapus.');
    }
}
