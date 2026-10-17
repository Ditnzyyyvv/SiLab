<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah User Baru</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 p-8">
    <div class="max-w-md mx-auto bg-white p-6 rounded-xl shadow-md">
        <h1 class="text-2xl font-bold text-slate-800 mb-6">Tambah User Baru</h1>

        <!-- Form mengarah ke route user.store dengan method POST -->
        <form action="{{ route('user.store') }}" method="POST" class="space-y-4">
            <!-- WAJIB: Token Keamanan Laravel CSRF -->
            @csrf

            <!-- Input Nama -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Masukkan nama"
                    class="w-full border rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Input Email -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="contoh@mail.com"
                    class="w-full border rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Input Password -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                <input type="password" name="password" placeholder="Minimal 6 karakter"
                    class="w-full border rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tombol Simpan & Batal -->
            <div class="flex gap-2 pt-2">
                <button type="submit" class="bg-blue-600 text-white px-5 py-2.5 rounded-lg hover:bg-blue-700 font-medium">
                    Simpan
                </button>
                <a href="{{ route('user.index') }}" class="bg-slate-200 text-slate-700 px-5 py-2.5 rounded-lg hover:bg-slate-300 font-medium">
                    Batal
                </a>
            </div>
        </form>
    </div>
</body>
</html>
