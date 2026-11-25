<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between w-full gap-3">
            <div>
                <h1 class="text-xl font-bold text-slate-900">Manajemen Pengguna</h1>
                <p class="text-xs text-slate-500 mt-0.5">Kelola akun Administrator, Teknisi Laboratorium, dan Member terdaftar</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-sm transition-colors">
                <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Pengguna
            </a>
        </div>
    </x-slot>

    <div class="space-y-5">
        <!-- Flash Alerts -->
        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-sm text-emerald-800 flex items-center space-x-2">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-sm text-rose-800 flex items-center space-x-2">
                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Summary KPI Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-xs">
                <div class="text-[11px] font-semibold text-slate-500 uppercase">Total Pengguna</div>
                <div class="text-2xl font-bold text-slate-900 mt-1">{{ $statCounts['total'] }}</div>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-xs">
                <div class="text-[11px] font-semibold text-indigo-600 uppercase">Administrator</div>
                <div class="text-2xl font-bold text-indigo-700 mt-1">{{ $statCounts['admin'] }}</div>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-xs">
                <div class="text-[11px] font-semibold text-amber-600 uppercase">Teknisi Lab</div>
                <div class="text-2xl font-bold text-amber-700 mt-1">{{ $statCounts['teknisi'] }}</div>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-xs">
                <div class="text-[11px] font-semibold text-emerald-600 uppercase">Member / Siswa</div>
                <div class="text-2xl font-bold text-emerald-700 mt-1">{{ $statCounts['member'] }}</div>
            </div>
        </div>

        <!-- Search & Role Filter -->
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
            <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                <div class="sm:col-span-2 relative">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari nama, email, atau nomor HP..."
                        class="w-full pl-9 pr-4 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    />
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <div>
                    <select name="role" onchange="this.form.submit()" class="w-full text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Semua Peran</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Administrator</option>
                        <option value="teknisi" {{ request('role') === 'teknisi' ? 'selected' : '' }}>Teknisi</option>
                        <option value="member" {{ request('role') === 'member' ? 'selected' : '' }}>Member</option>
                    </select>
                </div>

                <div class="flex space-x-2">
                    <button type="submit" class="flex-1 py-2 px-3 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition-colors">
                        Cari
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-medium rounded-lg transition-colors flex items-center">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="px-5 py-3">Pengguna</th>
                            <th class="px-4 py-3">Peran</th>
                            <th class="px-4 py-3">No. HP / Kontak</th>
                            <th class="px-4 py-3 text-center">Aktivitas</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($users as $item)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-9 h-9 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-700 shrink-0">
                                            {{ strtoupper(substr($item->nama, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-slate-900">
                                                {{ $item->nama }}
                                                @if($item->id === Auth::id())
                                                    <span class="ml-1 px-1.5 py-0.5 text-[10px] rounded bg-indigo-50 text-indigo-700 border border-indigo-200 font-bold">Anda</span>
                                                @endif
                                            </div>
                                            <div class="text-[11px] text-slate-500">{{ $item->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3.5">
                                    @php
                                        $roleBadge = match($item->role) {
                                            'admin' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                            'teknisi' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            default => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        };
                                        $roleLabel = match($item->role) {
                                            'admin' => 'Administrator',
                                            'teknisi' => 'Teknisi Lab',
                                            default => 'Member',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold border {{ $roleBadge }}">
                                        {{ $roleLabel }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 font-mono text-slate-700">
                                    {{ $item->no_hp ?? '-' }}
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    @if($item->role === 'teknisi')
                                        <span class="text-[11px] font-medium text-amber-700">{{ $item->tugas_aktif_count }} Tugas Aktif</span>
                                    @elseif($item->role === 'member')
                                        <span class="text-[11px] font-medium text-slate-600">{{ $item->laporan_count }} Laporan</span>
                                    @else
                                        <span class="text-[11px] text-slate-400">Pengelola Sistem</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center space-x-2">
                                        <a href="{{ route('admin.users.edit', $item) }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg transition-colors">
                                            Edit
                                        </a>
                                        @if($item->id !== Auth::id())
                                            <form action="{{ route('admin.users.destroy', $item) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ addslashes($item->nama) }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold rounded-lg transition-colors">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center text-slate-400">
                                    Tidak ada akun pengguna yang sesuai dengan filter pencarian.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
