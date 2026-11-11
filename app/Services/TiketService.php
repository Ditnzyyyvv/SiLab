<?php

namespace App\Services;

use App\Models\LogPenanganan;
use App\Models\Tiket;
use App\Models\TiketFoto;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class TiketService
{
    /**
     * Generate format nomor tiket otomatis: TKT-YYYYMMDD-XXXX (FR-14)
     * Contoh: TKT-20261111-0001
     */
    public function generateNomorTiket(): string
    {
        $today = date('Ymd');
        $prefix = "TKT-{$today}-";

        $countToday = Tiket::where('nomor_tiket', 'like', "{$prefix}%")->count() + 1;
        $urutan = str_pad((string) $countToday, 4, '0', STR_PAD_LEFT);
        $nomorTiket = "{$prefix}{$urutan}";

        while (Tiket::where('nomor_tiket', $nomorTiket)->exists()) {
            $countToday++;
            $urutan = str_pad((string) $countToday, 4, '0', STR_PAD_LEFT);
            $nomorTiket = "{$prefix}{$urutan}";
        }

        return $nomorTiket;
    }

    /**
     * Membuat tiket baru (baik dari Pelapor Tamu via Scan QR maupun Member Login).
     *
     * @param  array  $data
     * @param  array<UploadedFile>  $uploadedFotos
     * @param  User|null  $pelapor
     * @return Tiket
     */
    public function createTiket(array $data, array $uploadedFotos = [], ?User $pelapor = null): Tiket
    {
        return DB::transaction(function () use ($data, $uploadedFotos, $pelapor) {
            $nomorTiket = $this->generateNomorTiket();

            // Atur informasi pelapor
            if ($pelapor) {
                $pelaporId = $pelapor->id;
                $namaPelapor = $pelapor->nama ?? $pelapor->name;
                $kontakPelapor = $data['kontak_pelapor'] ?? $pelapor->no_hp;
            } else {
                $pelaporId = null;
                $namaPelapor = $data['nama_pelapor'] ?? 'Pengguna Lab (Tamu)';
                $kontakPelapor = $data['kontak_pelapor'] ?? null;
            }

            $tiket = Tiket::create([
                'nomor_tiket' => $nomorTiket,
                'aset_id' => $data['aset_id'],
                'pelapor_id' => $pelaporId,
                'nama_pelapor' => $namaPelapor,
                'kontak_pelapor' => $kontakPelapor,
                'judul' => $data['judul'],
                'deskripsi' => $data['deskripsi'],
                'status' => 'Pending',
            ]);

            // Simpan foto bukti kerusakan (maksimal 3 foto, FR-13)
            if (! empty($uploadedFotos)) {
                $fotosToSave = array_slice($uploadedFotos, 0, 3);
                foreach ($fotosToSave as $foto) {
                    if ($foto instanceof UploadedFile) {
                        $path = $foto->store("tikets/{$nomorTiket}", 'public');
                        TiketFoto::create([
                            'tiket_id' => $tiket->id,
                            'path' => $path,
                        ]);
                    }
                }
            }

            // Catat log pertama saat tiket dibuat
            LogPenanganan::create([
                'tiket_id' => $tiket->id,
                'user_id' => $pelapor?->id,
                'status_dari' => null,
                'status_ke' => 'Pending',
                'catatan' => 'Tiket pengaduan kerusakan berhasil dibuat dan masuk antrean verifikasi Admin.',
            ]);

            return $tiket;
        });
    }
}
