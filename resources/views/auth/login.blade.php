<x-guest-layout>
    <div class="mb-5">
        <h1 class="text-lg font-bold text-slate-900">Masuk ke Akun SiLab</h1>
        <p class="text-xs text-slate-500 mt-0.5">Masuk sebagai Admin, Teknisi, atau Member terdaftar untuk mengakses dashboard.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Alamat Email" />
            <x-text-input id="email" class="block mt-1 w-full text-sm" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@silab.sch.id" />
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" value="Kata Sandi" />

            <x-text-input id="password" class="block mt-1 w-full text-sm"
                            type="password"
                            name="password"
                            required autocomplete="current-password"
                            placeholder="Masukkan kata sandi" />

            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-xs text-slate-600">Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-xs font-medium text-indigo-600 hover:text-indigo-800 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    Lupa kata sandi?
                </a>
            @endif
        </div>

        <div class="pt-2 space-y-3">
            <button type="submit" class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-colors">
                Masuk
            </button>

            <p class="text-center text-xs text-slate-500">
                Belum punya akun member?
                <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-800">Daftar di sini</a>
            </p>
        </div>
    </form>
</x-guest-layout>
