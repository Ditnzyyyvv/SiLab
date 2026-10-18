<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
        <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50 min-h-screen flex flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white">
            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
                    <div class="flex lg:justify-center lg:col-start-2">
                        <span class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">SiLab</span>
                    </div>
                    @if (Route::has('login'))
                        <nav class="-mx-3 flex flex-1 justify-end">
                            @auth
                                <a
                                    href="{{ url('/dashboard') }}"
                                    class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                                >
                                    Dashboard
                                </a>
                            @else
                                <a
                                    href="{{ route('login') }}"
                                    class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                                >
                                    Log in
                                </a>

                                @if (Route::has('register'))
                                    <a
                                        href="{{ route('register') }}"
                                        class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                                    >
                                        Register
                                    </a>
                                @endif
                            @endauth
                        </nav>
                    @endif
                </header>

                <main class="mt-6 flex flex-col items-center justify-center text-center py-16">
                    <h1 class="text-4xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-6xl">
                        Selamat Datang di SiLab
                    </h1>
                    <p class="mt-6 text-lg leading-8 text-gray-600 dark:text-gray-300 max-w-xl">
                        Aplikasi Sistem Informasi Laboratorium dengan autentikasi Laravel Breeze bawaan standar yang bersih dan aman.
                    </p>
                    <div class="mt-10 flex items-center justify-center gap-x-6">
                        @auth
                            <a href="{{ route('dashboard') }}" class="rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition">
                                Buka Dashboard &rarr;
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition">
                                Masuk (Login)
                            </a>
                            <a href="{{ route('register') }}" class="text-sm font-semibold leading-6 text-gray-900 dark:text-white hover:underline">
                                Daftar Akun (Register) <span aria-hidden="true">→</span>
                            </a>
                        @endauth
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
