<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Tampilkan daftar seluruh pengguna dengan filter role & pencarian.
     */
    public function index(Request $request): View
    {
        $query = User::withCount([
            'tiketsSebagaiTeknisi as tugas_aktif_count' => function ($q) {
                $q->whereIn('status', ['Pending', 'Diproses', 'Menunggu Komponen/Sparepart']);
            },
            'tiketsSebagaiPelapor as laporan_count',
        ]);

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('no_hp', 'like', "%{$search}%");
            });
        }

        $users = $query->orderByRaw("CASE role WHEN 'admin' THEN 1 WHEN 'teknisi' THEN 2 ELSE 3 END")
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        $statCounts = [
            'total' => User::count(),
            'admin' => User::where('role', 'admin')->count(),
            'teknisi' => User::where('role', 'teknisi')->count(),
            'member' => User::where('role', 'member')->count(),
        ];

        return view('admin.users.index', compact('users', 'statCounts'));
    }

    /**
     * Tampilkan formulir tambah pengguna baru.
     */
    public function create(): View
    {
        return view('admin.users.create');
    }

    /**
     * Simpan akun pengguna baru.
     */
    public function store(UserRequest $request): RedirectResponse
    {
        $data = $request->validated();

        User::create([
            'nama' => $data['nama'],
            'email' => $data['email'],
            'role' => $data['role'],
            'no_hp' => $data['no_hp'] ?? null,
            'password' => $data['password'],
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', "Akun pengguna {$data['nama']} ({$data['role']}) berhasil ditambahkan.");
    }

    /**
     * Tampilkan formulir edit pengguna.
     */
    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Perbarui data, peran, dan kata sandi (opsional) pengguna.
     */
    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        // Guard: Jangan turunkan role Admin terakhir
        if ($user->role === 'admin' && $data['role'] !== 'admin') {
            if (User::where('role', 'admin')->count() <= 1) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Peran Administrator terakhir tidak dapat diubah demi menjaga akses pengelolaan sistem.');
            }
        }

        // Guard: Jangan ubah role Teknisi yang masih memegang tugas aktif
        if ($user->role === 'teknisi' && $data['role'] !== 'teknisi') {
            $activeTasks = $user->tiketsSebagaiTeknisi()
                ->whereIn('status', ['Pending', 'Diproses', 'Menunggu Komponen/Sparepart'])
                ->count();

            if ($activeTasks > 0) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', "Peran teknisi {$user->nama} tidak dapat diubah karena masih menangani {$activeTasks} tiket aktif. Alihkan disposisi terlebih dahulu.");
            }
        }

        $payload = [
            'nama' => $data['nama'],
            'email' => $data['email'],
            'role' => $data['role'],
            'no_hp' => $data['no_hp'] ?? null,
        ];

        if (! empty($data['password'])) {
            $payload['password'] = $data['password'];
        }

        $user->update($payload);

        return redirect()->route('admin.users.index')
            ->with('success', "Data pengguna {$user->nama} berhasil diperbarui.");
    }

    /**
     * Hapus akun pengguna dengan guard keamanan berlapis.
     */
    public function destroy(User $user): RedirectResponse
    {
        // Guard 1: Tidak boleh menghapus akun sendiri dari menu manajemen user
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menghapus akun yang sedang Anda gunakan.');
        }

        // Guard 2: Tidak boleh menghapus Administrator terakhir
        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Administrator terakhir tidak dapat dihapus.');
        }

        // Guard 3: Tidak boleh menghapus Teknisi yang masih memiliki tugas perbaikan aktif
        if ($user->role === 'teknisi') {
            $activeTasks = $user->tiketsSebagaiTeknisi()
                ->whereIn('status', ['Pending', 'Diproses', 'Menunggu Komponen/Sparepart'])
                ->count();

            if ($activeTasks > 0) {
                return redirect()->route('admin.users.index')
                    ->with('error', "Akun teknisi {$user->nama} tidak dapat dihapus karena masih memiliki {$activeTasks} tugas perbaikan aktif.");
            }
        }

        $nama = $user->nama;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "Akun pengguna {$nama} berhasil dihapus.");
    }
}
