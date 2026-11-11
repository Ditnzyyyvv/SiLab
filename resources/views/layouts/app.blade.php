<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SiLab') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased h-full text-slate-800" x-data="{ sidebarOpen: false }">
        <div class="min-h-screen flex bg-slate-50">
            <!-- Sidebar -->
            @include('layouts.sidebar')

            <!-- Main Content Container -->
            <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
                <!-- Topbar Navigation -->
                <header class="bg-white border-b border-slate-200/80 sticky top-0 z-30 shadow-sm">
                    <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                        <div class="flex items-center space-x-3">
                            <button 
                                @click="sidebarOpen = true" 
                                class="p-2 rounded-lg text-slate-500 hover:text-slate-700 hover:bg-slate-100 lg:hidden focus:outline-none"
                                aria-label="Buka Menu"
                            >
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>

                            @isset($header)
                                <div>{{ $header }}</div>
                            @endisset
                        </div>

                        <!-- Right Topbar Controls -->
                        <div class="flex items-center space-x-4">
                            <!-- In-app Notification Icon Placeholder (FR-28) -->
                            <button class="relative p-2 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-indigo-500 rounded-full ring-2 ring-white"></span>
                            </button>

                            <!-- User Info -->
                            <div class="hidden sm:flex sm:items-center sm:space-x-3 border-l border-slate-200 pl-4">
                                <span class="text-sm font-medium text-slate-700">{{ Auth::user()->nama ?? Auth::user()->name }}</span>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content Body -->
                <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
