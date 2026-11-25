@php
    $showIntro = request()->routeIs('login', 'register')
        && ! $errors->any()
        && ! session('status')
        && ! session('error')
        && ! session('info');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-white">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SiLab') }} — Autentikasi</title>
        <link rel="icon" type="image/png" href="{{ asset('images/silab-logo.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-800 antialiased bg-white min-h-screen">
        @if($showIntro)
            <x-silab-intro />
        @endif

        <div id="silab-auth-shell" class="silab-auth-shell {{ $showIntro ? '' : 'is-visible' }} min-h-screen grid grid-cols-1 lg:grid-cols-2 bg-white">
            <!-- Left Panel: Clean White Auth Form -->
            <div class="flex flex-col justify-between px-6 sm:px-12 lg:px-14 xl:px-20 py-6 sm:py-8">
                <!-- Top Brand Header -->
                <div class="flex items-center justify-between">
                    <a href="{{ url('/') }}" class="inline-flex items-center space-x-2.5 group">
                        <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-200/80 p-1 flex items-center justify-center group-hover:border-[#3B49DF]/40 transition-colors">
                            <img src="{{ asset('images/intro/full.png') }}" alt="Logo SiLab" class="w-full h-full object-contain" />
                        </div>
                        <div>
                            <span class="text-base font-extrabold text-slate-900 tracking-tight block leading-none">SiLab</span>
                            <span class="text-[10px] font-medium text-slate-400">Aset Lab Sekolah</span>
                        </div>
                    </a>

                    <a href="{{ url('/') }}" class="text-xs font-semibold text-slate-500 hover:text-[#3B49DF] transition-colors inline-flex items-center">
                        ← Beranda
                    </a>
                </div>

                <!-- Main Auth Form Slot -->
                <div class="w-full max-w-[420px] mx-auto my-auto py-8">
                    {{ $slot }}
                </div>

                <!-- Bottom Copyright -->
                <div class="text-center sm:text-left text-xs text-slate-400">
                    &copy; {{ date('Y') }} SiLab — Sistem Informasi Laboratorium Sekolah. Hak Cipta Dilindungi.
                </div>
            </div>

            <!-- Right Panel: Vibrant Royal Blue Showcase (Desktop) -->
            <div class="hidden lg:flex p-4 xl:p-6 bg-white">
                <div class="w-full rounded-[28px] bg-gradient-to-br from-[#3B49DF] via-[#3443D6] to-[#2531B3] p-10 xl:p-12 flex flex-col justify-between relative overflow-hidden text-white shadow-2xl shadow-blue-900/20">
                    <!-- Ambient Decorative Glows -->
                    <div class="pointer-events-none absolute -top-24 -right-24 w-72 h-72 rounded-full bg-sky-400/20 blur-3xl"></div>
                    <div class="pointer-events-none absolute -bottom-24 -left-24 w-80 h-80 rounded-full bg-indigo-950/35 blur-3xl"></div>

                    <!-- Top Headline -->
                    <div class="relative z-10 max-w-md">
                        <h2 class="text-3xl xl:text-[34px] font-bold tracking-tight leading-[1.2]">
                            Cara termudah mengelola aset & perbaikan lab
                        </h2>
                        <p class="text-sm text-blue-100/85 mt-3 leading-relaxed">
                            Catat inventaris dengan stiker QR Code, terima aduan kerusakan secara instan, dan pantau kinerja teknisi dalam satu dashboard terpadu.
                        </p>
                    </div>

                    <!-- Center Dashboard & Floating Modal Mockup -->
                    <div class="relative z-10 my-8 pr-6">
                        <!-- Main White Dashboard Card -->
                        <div class="bg-white rounded-2xl shadow-2xl shadow-blue-950/30 p-5 text-slate-800 border border-white/60">
                            <!-- Top Toolbar -->
                            <div class="flex items-center justify-between pb-3.5 border-b border-slate-100">
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs font-extrabold text-slate-900">Dashboard SiLab</span>
                                    <span class="px-2 py-0.5 rounded-full bg-blue-50 text-[#3B49DF] text-[10px] font-semibold">Semua Lab</span>
                                </div>
                                <div class="flex items-center space-x-1.5">
                                    <div class="flex -space-x-1.5">
                                        <span class="w-5 h-5 rounded-full bg-indigo-600 text-white text-[9px] font-bold flex items-center justify-center ring-2 ring-white">AD</span>
                                        <span class="w-5 h-5 rounded-full bg-amber-500 text-white text-[9px] font-bold flex items-center justify-center ring-2 ring-white">TK</span>
                                        <span class="w-5 h-5 rounded-full bg-emerald-500 text-white text-[9px] font-bold flex items-center justify-center ring-2 ring-white">MB</span>
                                    </div>
                                    <span class="px-2 py-1 rounded-md bg-blue-50 text-[#3B49DF] text-[9px] font-bold">+ Tiket</span>
                                </div>
                            </div>

                            <!-- KPI Mini Row -->
                            <div class="grid grid-cols-2 gap-3 my-4">
                                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                                    <p class="text-[10px] font-medium text-slate-500">Aset Kondisi Baik</p>
                                    <div class="flex items-end justify-between mt-1">
                                        <div>
                                            <p class="text-lg font-extrabold text-slate-900 leading-none">94,2%</p>
                                            <p class="text-[9px] font-semibold text-emerald-600 mt-1">↑ +4,5% bulan ini</p>
                                        </div>
                                        <svg class="w-14 h-7 text-[#3B49DF]" viewBox="0 0 60 28" fill="none">
                                            <path d="M2 22 C12 20, 16 12, 26 14 C36 16, 44 6, 58 4" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" />
                                        </svg>
                                    </div>
                                </div>

                                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                                    <p class="text-[10px] font-medium text-slate-500">Respon Perbaikan</p>
                                    <div class="flex items-end justify-between mt-1">
                                        <div>
                                            <p class="text-lg font-extrabold text-slate-900 leading-none">2,4 Jam</p>
                                            <p class="text-[9px] font-semibold text-blue-600 mt-1">↓ 18% lebih cepat</p>
                                        </div>
                                        <svg class="w-14 h-7 text-amber-500" viewBox="0 0 60 28" fill="none">
                                            <path d="M2 8 C14 10, 22 18, 34 15 C44 12, 50 20, 58 22" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Mini Status Table -->
                            <div class="space-y-2.5 pt-1">
                                <p class="text-[10px] font-bold text-slate-700">Status Kesiapan Laboratorium</p>
                                <div class="space-y-2 text-[10px]">
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="flex items-center space-x-2 w-28">
                                            <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                                            <span class="font-semibold text-slate-700 truncate">Lab RPL</span>
                                        </div>
                                        <span class="px-1.5 py-0.5 rounded bg-emerald-50 text-emerald-700 font-bold text-[8px]">OPTIMAL</span>
                                        <div class="flex-1 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-[#3B49DF] rounded-full w-[92%]"></div>
                                        </div>
                                        <span class="font-mono font-bold text-slate-600">92%</span>
                                    </div>

                                    <div class="flex items-center justify-between gap-2">
                                        <div class="flex items-center space-x-2 w-28">
                                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                            <span class="font-semibold text-slate-700 truncate">Lab TKJ</span>
                                        </div>
                                        <span class="px-1.5 py-0.5 rounded bg-amber-50 text-amber-700 font-bold text-[8px]">PROSES</span>
                                        <div class="flex-1 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-amber-500 rounded-full w-[78%]"></div>
                                        </div>
                                        <span class="font-mono font-bold text-slate-600">78%</span>
                                    </div>

                                    <div class="flex items-center justify-between gap-2">
                                        <div class="flex items-center space-x-2 w-28">
                                            <span class="w-2 h-2 rounded-full bg-sky-500"></span>
                                            <span class="font-semibold text-slate-700 truncate">Lab Multimedia</span>
                                        </div>
                                        <span class="px-1.5 py-0.5 rounded bg-emerald-50 text-emerald-700 font-bold text-[8px]">OPTIMAL</span>
                                        <div class="flex-1 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-sky-500 rounded-full w-[96%]"></div>
                                        </div>
                                        <span class="font-mono font-bold text-slate-600">96%</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Floating Overlapping Modal Card (Inspired by Reference UI) -->
                        <div class="absolute -right-1 top-12 w-56 bg-white rounded-2xl shadow-2xl shadow-blue-950/35 border border-slate-100 p-4 text-slate-800">
                            <div class="flex items-center justify-between mb-2.5">
                                <span class="text-[11px] font-bold text-slate-900">Disposisi Teknisi</span>
                                <span class="w-4 h-4 rounded-full bg-slate-100 text-slate-400 text-[10px] flex items-center justify-center">✓</span>
                            </div>
                            <div class="flex items-center gap-1.5 mb-3">
                                <div class="flex-1 px-2 py-1.5 rounded-lg bg-slate-50 border border-slate-200 font-mono text-[9px] text-slate-600 truncate">
                                    AST-RPL-0001
                                </div>
                                <span class="px-2.5 py-1.5 rounded-lg bg-[#3B49DF] text-white text-[9px] font-bold">Proses</span>
                            </div>
                            <div class="space-y-2 text-[10px]">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <span class="w-5 h-5 rounded-full bg-blue-100 text-[#3B49DF] font-bold text-[8px] flex items-center justify-center">FR</span>
                                        <div>
                                            <p class="font-semibold text-slate-800 leading-none">Teknisi PC</p>
                                            <p class="text-[8px] text-slate-400 mt-0.5">2 tugas aktif</p>
                                        </div>
                                    </div>
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <span class="w-5 h-5 rounded-full bg-amber-100 text-amber-700 font-bold text-[8px] flex items-center justify-center">RH</span>
                                        <div>
                                            <p class="font-semibold text-slate-800 leading-none">Teknisi Jaringan</p>
                                            <p class="text-[8px] text-slate-400 mt-0.5">Siap tugas</p>
                                        </div>
                                    </div>
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Feature Strip -->
                    <div class="relative z-10 flex flex-wrap items-center justify-between gap-3 pt-4 border-t border-white/15 text-[11px] font-semibold text-blue-100/80 tracking-wide">
                        <span>Scan QR Instan</span>
                        <span>•</span>
                        <span>Google Auth</span>
                        <span>•</span>
                        <span>Chart.js Real-time</span>
                        <span>•</span>
                        <span>PDF & Excel</span>
                    </div>
                </div>
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

                    function dismissIntro() {
                        if (dismissed) return;
                        dismissed = true;

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
                        setTimeout(dismissIntro, 2350);
                    }

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
