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
    <body class="font-sans text-slate-800 antialiased bg-slate-50">
        <div class="min-h-screen flex flex-col sm:justify-center items-center py-8 px-4">
            <div class="text-center">
                <a href="{{ url('/') }}" class="inline-flex flex-col items-center group">
                    <div class="w-16 h-16 rounded-2xl bg-white border border-slate-200 p-2 shadow-sm flex items-center justify-center group-hover:border-indigo-300 transition-colors">
                        <x-application-logo class="w-full h-full" />
                    </div>
                    <span class="mt-2.5 text-xl font-extrabold text-slate-900 tracking-tight">SiLab</span>
                    <span class="text-xs text-slate-500 font-medium">Sistem Informasi Aset Laboratorium Sekolah</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-7 bg-white shadow-sm border border-slate-200/80 overflow-hidden rounded-2xl">
                {{ $slot }}
            </div>

            <div class="mt-6 text-center">
                <a href="{{ url('/') }}" class="text-xs font-medium text-slate-500 hover:text-indigo-600 transition-colors">
                    ← Kembali ke Beranda & Form Lapor Cepat
                </a>
            </div>
        </div>
    </body>
</html>
