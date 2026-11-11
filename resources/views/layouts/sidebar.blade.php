@php
    $user = Auth::user();
    $role = $user->role ?? 'member';
@endphp

<!-- Sidebar Backdrop for Mobile -->
<div 
    x-show="sidebarOpen" 
    x-transition:enter="transition-opacity ease-linear duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @click="sidebarOpen = false" 
    class="fixed inset-0 z-40 bg-gray-900/50 backdrop-blur-sm lg:hidden"
    style="display: none;"
></div>

<!-- Sidebar Component -->
<aside 
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
    class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-200 transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 flex flex-col justify-between shadow-xl"
>
    <div>
        <!-- Brand Header -->
        <div class="flex items-center justify-between h-16 px-6 bg-slate-950/60 border-b border-slate-800">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                <div class="h-9 w-9 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-bold text-lg shadow-md shadow-indigo-600/30">
                    SL
                </div>
                <div class="flex flex-col">
                    <span class="text-base font-bold tracking-wide text-white">SiLab</span>
                    <span class="text-[10px] text-slate-400 font-medium">Aset Lab Sekolah</span>
                </div>
            </a>
            <button @click="sidebarOpen = false" class="p-1 rounded-md text-slate-400 hover:text-white hover:bg-slate-800 lg:hidden">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- User Role Badge -->
        <div class="px-6 py-4 border-b border-slate-800/80 bg-slate-950/20">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-200 font-semibold text-sm border border-slate-700">
                    {{ strtoupper(substr($user->nama ?? $user->name, 0, 2)) }}
                </div>
                <div class="flex flex-col overflow-hidden">
                    <span class="text-sm font-semibold text-slate-100 truncate">{{ $user->nama ?? $user->name }}</span>
                    <div class="mt-0.5">
                        @if($role === 'admin')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                                Administrator
                            </span>
                        @elseif($role === 'teknisi')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                Teknisi
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                Member
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="px-3 py-4 space-y-1.5 overflow-y-auto">
            @if($role === 'admin')
                <!-- ADMIN MENU -->
                <div class="px-3 pt-2 pb-1 text-[11px] font-bold uppercase tracking-wider text-slate-400">Utama</div>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>

                <div class="px-3 pt-4 pb-1 text-[11px] font-bold uppercase tracking-wider text-slate-400">Master Data</div>
                <div class="space-y-1 text-slate-300">
                    <a href="#" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium text-slate-400 hover:text-slate-200 hover:bg-slate-800/60 transition-colors">
                        <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Data Laboratorium
                        <span class="ml-auto text-[10px] bg-slate-800 px-1.5 py-0.5 rounded text-slate-400">Fase 2</span>
                    </a>
                    <a href="#" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium text-slate-400 hover:text-slate-200 hover:bg-slate-800/60 transition-colors">
                        <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        Kategori Aset
                        <span class="ml-auto text-[10px] bg-slate-800 px-1.5 py-0.5 rounded text-slate-400">Fase 2</span>
                    </a>
                    <a href="#" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium text-slate-400 hover:text-slate-200 hover:bg-slate-800/60 transition-colors">
                        <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                        </svg>
                        Aset & QR Code
                        <span class="ml-auto text-[10px] bg-slate-800 px-1.5 py-0.5 rounded text-slate-400">Fase 2</span>
                    </a>
                </div>

                <div class="px-3 pt-4 pb-1 text-[11px] font-bold uppercase tracking-wider text-slate-400">Pengaduan & Tiket</div>
                <a href="#" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium text-slate-400 hover:text-slate-200 hover:bg-slate-800/60 transition-colors">
                    <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Semua Tiket
                    <span class="ml-auto text-[10px] bg-slate-800 px-1.5 py-0.5 rounded text-slate-400">Fase 3</span>
                </a>

                <div class="px-3 pt-4 pb-1 text-[11px] font-bold uppercase tracking-wider text-slate-400">Laporan</div>
                <a href="#" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium text-slate-400 hover:text-slate-200 hover:bg-slate-800/60 transition-colors">
                    <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Ekspor Laporan
                    <span class="ml-auto text-[10px] bg-slate-800 px-1.5 py-0.5 rounded text-slate-400">Fase 6</span>
                </a>

            @elseif($role === 'teknisi')
                <!-- TEKNISI MENU -->
                <div class="px-3 pt-2 pb-1 text-[11px] font-bold uppercase tracking-wider text-slate-400">Utama</div>
                <a href="{{ route('teknisi.dashboard') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('teknisi.dashboard') ? 'bg-amber-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard Teknisi
                </a>

                <div class="px-3 pt-4 pb-1 text-[11px] font-bold uppercase tracking-wider text-slate-400">Perbaikan</div>
                <a href="#" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium text-slate-400 hover:text-slate-200 hover:bg-slate-800/60 transition-colors">
                    <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Tugas Saya
                    <span class="ml-auto text-[10px] bg-slate-800 px-1.5 py-0.5 rounded text-slate-400">Fase 4</span>
                </a>

            @else
                <!-- MEMBER MENU -->
                <div class="px-3 pt-2 pb-1 text-[11px] font-bold uppercase tracking-wider text-slate-400">Utama</div>
                <a href="{{ route('member.dashboard') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('member.dashboard') ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard Member
                </a>

                <div class="px-3 pt-4 pb-1 text-[11px] font-bold uppercase tracking-wider text-slate-400">Pengaduan Kerusakan</div>
                <a href="#" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium text-slate-400 hover:text-slate-200 hover:bg-slate-800/60 transition-colors">
                    <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Buat Tiket Baru
                    <span class="ml-auto text-[10px] bg-slate-800 px-1.5 py-0.5 rounded text-slate-400">Fase 3</span>
                </a>
                <a href="#" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium text-slate-400 hover:text-slate-200 hover:bg-slate-800/60 transition-colors">
                    <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Tiket Saya (Riwayat)
                    <span class="ml-auto text-[10px] bg-slate-800 px-1.5 py-0.5 rounded text-slate-400">Fase 3</span>
                </a>
            @endif
        </nav>
    </div>

    <!-- Bottom Footer Profile / Logout -->
    <div class="p-4 border-t border-slate-800 bg-slate-950/40">
        <div class="flex items-center justify-between">
            <a href="{{ route('profile.edit') }}" class="flex items-center space-x-2 text-sm text-slate-300 hover:text-white transition-colors">
                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span>Edit Profil</span>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" title="Keluar" class="p-1.5 rounded-lg text-rose-400 hover:text-rose-300 hover:bg-rose-500/10 transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>
