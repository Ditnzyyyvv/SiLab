<x-guest-layout>
    <div class="mb-4">
        <h1 class="text-lg font-bold text-slate-900">Lupa Kata Sandi</h1>
        <p class="text-xs text-slate-500 mt-1 leading-relaxed">
            Masukkan alamat email yang terdaftar di SiLab, dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Alamat Email" />
            <x-text-input id="email" class="block mt-1 w-full text-sm" type="email" name="email" :value="old('email')" required autofocus placeholder="nama@silab.sch.id" />
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <div class="pt-1 space-y-3">
            <button type="submit" class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-colors">
                Kirim Tautan Atur Ulang Kata Sandi
            </button>
            <p class="text-center text-xs text-slate-500">
                <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:text-indigo-800">← Kembali ke halaman Masuk</a>
            </p>
        </div>
    </form>
</x-guest-layout>
