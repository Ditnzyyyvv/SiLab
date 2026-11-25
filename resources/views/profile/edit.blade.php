<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Pengaturan Profil Akun</h1>
            <p class="text-xs text-slate-500 mt-0.5">Kelola informasi pribadi, nomor kontak, dan keamanan kata sandi akun Anda</p>
        </div>
    </x-slot>

    <div class="space-y-6 max-w-4xl">
        @if(session('error'))
            <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-sm text-rose-800 flex items-center space-x-2">
                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="p-5 sm:p-7 bg-white shadow-xs border border-slate-200 rounded-2xl">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="p-5 sm:p-7 bg-white shadow-xs border border-slate-200 rounded-2xl">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="p-5 sm:p-7 bg-white shadow-xs border border-slate-200 rounded-2xl">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
