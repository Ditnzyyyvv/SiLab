<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pengguna</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-100 p-8">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-xl shadow-md">

        <!-- Notifikasi Sukses Jika Ada -->
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-slate-800">Daftar Pengguna</h1>
            <!-- Tombol ke halaman create -->
            <a href="{{ route('users.create') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-medium">
                + Tambah User
            </a>
        </div>

        <!-- Tabel Daftar User -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b bg-slate-50 text-slate-600">
                        <th class="p-3">No</th>
                        <th class="p-3">Nama</th>
                        <th class="p-3">Email</th>
                        <th class="p-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $index => $item)
                        <tr class="border-b hover:bg-slate-50">
                            <td class="p-3">{{ $index + 1 }}</td>
                            <td class="p-3 font-medium text-slate-800">{{ $item->name }}</td>
                            <td class="p-3 text-slate-600">{{ $item->email }}</td>
                            <td class="p-3 text-slate-400 text-sm">
                                (Langkah berikutnya: Edit & Hapus)
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-6 text-center text-slate-500">
                                Belum ada data user. Silakan klik tombol Tambah User.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
