<section class="space-y-4">
    <header>
        <h2 class="text-base font-bold text-rose-700">
            Hapus Akun
        </h2>

        <p class="mt-1 text-xs text-slate-500 leading-relaxed">
            Setelah akun dihapus, akses login Anda akan dinonaktifkan secara permanen. Akun Administrator terakhir tidak dapat dihapus demi menjaga keberlangsungan sistem.
        </p>
    </header>

    <button
        type="button"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold rounded-lg shadow-xs transition-colors"
    >
        Hapus Akun Saya
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-base font-bold text-slate-900">
                Apakah Anda yakin ingin menghapus akun ini?
            </h2>

            <p class="mt-1 text-xs text-slate-500 leading-relaxed">
                Tindakan ini tidak dapat dibatalkan. Masukkan kata sandi Anda saat ini untuk mengonfirmasi penghapusan akun secara permanen.
            </p>

            <div class="mt-4">
                <x-input-label for="password" value="Kata Sandi" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full sm:w-3/4 text-sm"
                    placeholder="Masukkan kata sandi Anda"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1.5" />
            </div>

            <div class="mt-6 flex justify-end space-x-2">
                <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition-colors">
                    Batal
                </button>

                <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold rounded-lg shadow-xs transition-colors">
                    Ya, Hapus Permanen
                </button>
            </div>
        </form>
    </x-modal>
</section>
