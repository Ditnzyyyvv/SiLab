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
                        <div class="flex items-center space-x-3">
                            <!-- In-app Notification Dropdown (FR-28) -->
                            @php
                                $unreadNotifsCount = Auth::user()->unreadNotifications->count();
                                $quickNotifs = Auth::user()->notifications()->take(5)->get();
                            @endphp
                            <div class="relative" x-data="{ notifOpen: false }">
                                <button 
                                    @click="notifOpen = !notifOpen" 
                                    class="relative p-2 text-slate-500 hover:text-slate-700 rounded-lg hover:bg-slate-100 transition-colors focus:outline-none"
                                    aria-label="Notifikasi"
                                >
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                    @if($unreadNotifsCount > 0)
                                        <span class="absolute top-1 right-1 min-w-[18px] h-[18px] px-1 bg-rose-600 text-white text-[10px] font-bold rounded-full flex items-center justify-center ring-2 ring-white">
                                            {{ $unreadNotifsCount > 9 ? '9+' : $unreadNotifsCount }}
                                        </span>
                                    @endif
                                </button>

                                <!-- Dropdown Menu -->
                                <div 
                                    x-show="notifOpen" 
                                    @click.away="notifOpen = false" 
                                    x-transition:enter="transition ease-out duration-150"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-100"
                                    x-transition:leave-start="opacity-100 scale-100"
                                    x-transition:leave-end="opacity-0 scale-95"
                                    class="absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-xl shadow-xl border border-slate-200 z-50 overflow-hidden"
                                    style="display: none;"
                                >
                                    <div class="p-3 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            <span class="text-xs font-bold text-slate-900">Notifikasi</span>
                                            @if($unreadNotifsCount > 0)
                                                <span class="px-1.5 py-0.2 rounded-full text-[10px] font-semibold bg-rose-100 text-rose-700">
                                                    {{ $unreadNotifsCount }} Baru
                                                </span>
                                            @endif
                                        </div>
                                        @if($unreadNotifsCount > 0)
                                            <form action="{{ route('notifikasi.mark-all-read') }}" method="POST">
                                                @csrf
                                                <button type="submit" class="text-[11px] font-semibold text-indigo-600 hover:text-indigo-800">
                                                    Tandai Dibaca
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                    <div class="divide-y divide-slate-100 max-h-80 overflow-y-auto">
                                        @forelse($quickNotifs as $qNotif)
                                            @php
                                                $isUn = is_null($qNotif->read_at);
                                                $qData = $qNotif->data;
                                            @endphp
                                            <a href="{{ route('notifikasi.read', $qNotif->id) }}" class="p-3 block hover:bg-slate-50 transition-colors {{ $isUn ? 'bg-indigo-50/30' : '' }}">
                                                <div class="flex items-start justify-between">
                                                    <span class="text-xs font-bold text-slate-800 line-clamp-1">{{ $qData['judul'] ?? 'Notifikasi' }}</span>
                                                    <span class="text-[10px] text-slate-400 shrink-0 ml-2">{{ $qNotif->created_at->diffForHumans() }}</span>
                                                </div>
                                                <p class="text-[11px] text-slate-600 mt-0.5 line-clamp-2 leading-relaxed">
                                                    {{ $qData['pesan'] ?? '' }}
                                                </p>
                                            </a>
                                        @empty
                                            <div class="p-6 text-center text-slate-400 text-xs">
                                                Tidak ada notifikasi saat ini.
                                            </div>
                                        @endforelse
                                    </div>

                                    <div class="p-2.5 bg-slate-50 border-t border-slate-100 text-center">
                                        <a href="{{ route('notifikasi.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                                            Lihat Semua Notifikasi →
                                        </a>
                                    </div>
                                </div>
                            </div>

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
