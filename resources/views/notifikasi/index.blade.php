<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-slate-900">Notifikasi Dalam Aplikasi</h1>
                <p class="text-xs text-slate-500 mt-0.5">Pemberitahuan perubahan status tiket, disposisi perbaikan, dan aktivitas lab</p>
            </div>
            @if($unreadCount > 0)
                <form action="{{ route('notifikasi.mark-all-read') }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-3.5 py-1.5 text-xs font-semibold rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 transition-colors">
                        ✓ Tandai Semua Dibaca ({{ $unreadCount }})
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Filter Bar -->
        <div class="flex items-center space-x-2 border-b border-slate-200 pb-3">
            <a href="{{ route('notifikasi.index') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ request('filter') !== 'unread' ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                Semua Notifikasi
            </a>
            <a href="{{ route('notifikasi.index', ['filter' => 'unread']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors flex items-center space-x-1.5 {{ request('filter') === 'unread' ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                <span>Belum Dibaca</span>
                @if($unreadCount > 0)
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ request('filter') === 'unread' ? 'bg-indigo-500 text-white' : 'bg-indigo-100 text-indigo-800' }}">
                        {{ $unreadCount }}
                    </span>
                @endif
            </a>
        </div>

        <!-- List Notifikasi -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-xs divide-y divide-slate-100 overflow-hidden">
            @forelse($notifikasis as $notif)
                @php
                    $isUnread = is_null($notif->read_at);
                    $data = $notif->data;
                    $tipe = $data['tipe'] ?? 'umum';

                    $iconColor = match($tipe) {
                        'tiket_baru' => 'bg-indigo-100 text-indigo-600',
                        'disposisi' => 'bg-amber-100 text-amber-700',
                        'status_updated' => 'bg-blue-100 text-blue-600',
                        'tiket_reopened' => 'bg-rose-100 text-rose-600',
                        default => 'bg-slate-100 text-slate-600'
                    };
                @endphp
                <div class="p-4 sm:p-5 flex items-start space-x-4 transition-colors {{ $isUnread ? 'bg-indigo-50/20 hover:bg-indigo-50/40' : 'hover:bg-slate-50' }}">
                    <!-- Ikon Notifikasi -->
                    <div class="w-10 h-10 rounded-xl {{ $iconColor }} flex items-center justify-center shrink-0">
                        @if($tipe === 'tiket_baru')
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        @elseif($tipe === 'disposisi')
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        @elseif($tipe === 'tiket_reopened')
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        @else
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        @endif
                    </div>

                    <!-- Konten Notifikasi -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <a href="{{ route('notifikasi.read', $notif->id) }}" class="text-sm font-bold text-slate-900 hover:text-indigo-600 transition-colors flex items-center space-x-2">
                                <span>{{ $data['judul'] ?? 'Pemberitahuan Sistem' }}</span>
                                @if($isUnread)
                                    <span class="w-2 h-2 rounded-full bg-indigo-600 shrink-0"></span>
                                @endif
                            </a>
                            <span class="text-[11px] text-slate-400 whitespace-nowrap ml-2">
                                {{ $notif->created_at->diffForHumans() }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                            {{ $data['pesan'] ?? '' }}
                        </p>
                        <div class="mt-2.5 flex items-center space-x-3">
                            <a href="{{ route('notifikasi.read', $notif->id) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">
                                Lihat Rincian Tiket →
                            </a>
                            @if(isset($data['nomor_tiket']))
                                <span class="font-mono text-[10px] text-slate-400">
                                    {{ $data['nomor_tiket'] }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center text-slate-400">
                    <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <p class="mt-2 text-sm font-medium text-slate-600">Tidak ada notifikasi saat ini</p>
                    <p class="text-xs text-slate-400 mt-0.5">Pemberitahuan baru akan muncul di sini secara otomatis.</p>
                </div>
            @endforelse
        </div>

        @if($notifikasis->hasPages())
            <div>
                {{ $notifikasis->links() }}
            </div>
        @endif
    </div>
</x-app-layout>

