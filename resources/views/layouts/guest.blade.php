@php
    $showIntro = request()->routeIs('login', 'register') && ! $errors->any() && ! session('status');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SiLab') }} — Autentikasi</title>
        <link rel="icon" type="image/png" href="{{ asset('images/silab-logo.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-800 antialiased bg-gradient-to-br from-slate-50 via-white to-sky-50/60 min-h-screen">
        @if($showIntro)
            <x-silab-intro />
        @endif

        <div id="silab-auth-shell" class="silab-auth-shell {{ $showIntro ? '' : 'is-visible' }} min-h-screen flex flex-col sm:justify-center items-center py-8 px-4">
            <div class="text-center">
                <a href="{{ url('/') }}" class="inline-flex flex-col items-center group">
                    <div class="w-16 h-16 rounded-2xl bg-white border border-slate-200 p-2 shadow-sm flex items-center justify-center group-hover:border-indigo-300 group-hover:scale-105 transition-all duration-300">
                        <x-application-logo class="w-full h-full" />
                    </div>
                    <span class="mt-2.5 text-xl font-extrabold text-slate-900 tracking-tight">SiLab</span>
                    <span class="text-xs text-slate-500 font-medium">Sistem Informasi Aset Laboratorium Sekolah</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-7 bg-white/95 backdrop-blur-sm shadow-sm border border-slate-200/80 overflow-hidden rounded-2xl">
                {{ $slot }}
            </div>

            <div class="mt-6 flex items-center justify-center gap-4 text-center">
                <a href="{{ url('/') }}" class="text-xs font-medium text-slate-500 hover:text-indigo-600 transition-colors">
                    ← Kembali ke Beranda & Form Lapor Cepat
                </a>
            </div>
        </div>

        @if($showIntro)
            <script>
                (function () {
                    const overlay = document.getElementById('silab-intro-overlay');
                    const stage = document.getElementById('silab-intro-stage');
                    const shell = document.getElementById('silab-auth-shell');

                    if (!overlay || !stage || !shell) return;

                    const reducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                    if (reducedMotion) {
                        overlay.remove();
                        shell.classList.add('is-visible');
                        return;
                    }

                    let dismissed = false;
                    let finishTimer = null;

                    function dismissIntro() {
                        if (dismissed) return;
                        dismissed = true;
                        if (finishTimer) clearTimeout(finishTimer);

                        overlay.style.opacity = '0';
                        overlay.style.transform = 'scale(1.03)';
                        overlay.style.pointerEvents = 'none';

                        requestAnimationFrame(() => {
                            shell.classList.add('is-visible');
                        });

                        setTimeout(() => {
                            overlay.remove();
                        }, 680);
                    }

                    function startIntro() {
                        if (dismissed) return;
                        requestAnimationFrame(() => {
                            stage.classList.add('is-playing');
                        });
                        finishTimer = setTimeout(dismissIntro, 2350);
                    }

                    // Tunggu seluruh layer gambar selesai di-decode (dengan batas tunggu 350ms agar tidak pernah macet)
                    const imgs = Array.from(stage.querySelectorAll('img'));
                    const decodePromises = imgs.map((img) => {
                        if (img.complete && img.naturalWidth > 0) return Promise.resolve();
                        return new Promise((resolve) => {
                            img.addEventListener('load', resolve, { once: true });
                            img.addEventListener('error', resolve, { once: true });
                        });
                    });

                    Promise.race([
                        Promise.all(decodePromises),
                        new Promise((resolve) => setTimeout(resolve, 350)),
                    ]).then(startIntro);
                })();
            </script>
        @endif
    </body>
</html>
