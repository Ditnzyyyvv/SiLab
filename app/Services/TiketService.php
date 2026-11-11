<?php

namespace App\Services;

use App\Models\Aset;
use App\Models\LogPenanganan;
use App\Models\Tiket;
use App\Models\TiketFoto;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class TiketService
{
    /**
     * Matriks aturan transisi status tiket yang diizinkan (PRD Bagian 9).
     */
    protected array $allowedTransitions = [
        'Pending' => ['Diproses', 'Ditolak'],
        'Diproses' => ['Menunggu Komponen/Sparepart', 'Selesai', 'Rusak Total'],
        'Menunggu Komponen/Sparepart' => ['Diproses'],
        'Selesai' => ['Diproses'], // Dibuka kembali oleh Member
        'Rusak Total' => [],      // Status akhir
        'Ditolak' => [],          // Status akhir
    ];

    /**
     * Memeriksa apakah transisi status dari $from ke $to diizinkan.
     */
    public function canTransition(string $from, string $to): bool
    {
        return in_array($to, $this->allowedTransitions[$from] ?? [], true);
    }

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

    /**
     * Validasi dan disposisi tiket ke Teknisi oleh Admin (FR-17, FR-18).
     * Juga dapat digunakan untuk memindahkan disposisi ke teknisi lain.
     */
    public function disposisi(Tiket $tiket, int $teknisiId, string $prioritas, ?string $catatan, User $admin): Tiket
    {
        return DB::transaction(function () use ($tiket, $teknisiId, $prioritas, $catatan, $admin) {
            $teknisi = User::where('id', $teknisiId)->where('role', 'teknisi')->firstOrFail();
            $statusAsal = $tiket->status;

            $isReassign = in_array($statusAsal, ['Diproses', 'Menunggu Komponen/Sparepart'], true);

            if (! $isReassign && ! $this->canTransition($statusAsal, 'Diproses')) {
                throw new InvalidArgumentException("Transisi status dari {$statusAsal} ke Diproses tidak diizinkan.");
            }

            $catatanLog = $catatan;
            if (empty($catatanLog)) {
                if ($isReassign) {
                    $catatanLog = "Disposisi dialihkan ke teknisi {$teknisi->nama} dengan prioritas {$prioritas}.";
                } else {
                    $catatanLog = "Tiket divalidasi oleh Admin dan ditugaskan ke teknisi {$teknisi->nama} dengan prioritas {$prioritas}.";
                }
            }

            $tiket->update([
                'status' => 'Diproses',
                'prioritas' => $prioritas,
                'teknisi_id' => $teknisi->id,
                'tanggal_disposisi' => $tiket->tanggal_disposisi ?? now(),
            ]);

            // Update kondisi aset jika masih 'Baik' menjadi 'Dalam Perbaikan'
            if ($tiket->aset && in_array($tiket->aset->kondisi, ['Baik', 'Rusak Ringan', 'Rusak Berat'], true)) {
                $tiket->aset->update(['kondisi' => 'Dalam Perbaikan']);
            }

            LogPenanganan::create([
                'tiket_id' => $tiket->id,
                'user_id' => $admin->id,
                'status_dari' => $statusAsal,
                'status_ke' => 'Diproses',
                'catatan' => $catatanLog,
            ]);

            return $tiket->fresh();
        });
    }

    /**
     * Menolak tiket dengan alasan wajib oleh Admin (FR-17).
     */
    public function tolak(Tiket $tiket, string $alasan, User $admin): Tiket
    {
        return DB::transaction(function () use ($tiket, $alasan, $admin) {
            if (! $this->canTransition($tiket->status, 'Ditolak')) {
                throw new InvalidArgumentException("Tiket dengan status {$tiket->status} tidak dapat ditolak.");
            }

            $statusAsal = $tiket->status;

            $tiket->update([
                'status' => 'Ditolak',
                'alasan_penolakan' => $alasan,
            ]);

            LogPenanganan::create([
                'tiket_id' => $tiket->id,
                'user_id' => $admin->id,
                'status_dari' => $statusAsal,
                'status_ke' => 'Ditolak',
                'catatan' => "Tiket ditolak oleh Admin. Alasan: {$alasan}",
            ]);

            return $tiket->fresh();
        });
    }

    /**
     * Teknisi memperbarui status tiket dan mencatat log penanganan (FR-21, FR-22, FR-23, FR-24).
     */
    public function updateStatusTeknisi(
        Tiket $tiket,
        string $statusKe,
        string $catatan,
        ?string $komponenDibutuhkan = null,
        ?UploadedFile $fotoBukti = null,
        User $teknisi
    ): Tiket {
        return DB::transaction(function () use ($tiket, $statusKe, $catatan, $komponenDibutuhkan, $fotoBukti, $teknisi) {
            if ($tiket->teknisi_id !== $teknisi->id) {
                throw new InvalidArgumentException("Anda tidak berhak menangani tiket ini karena tidak ditugaskan kepada Anda.");
            }

            if (! $this->canTransition($tiket->status, $statusKe)) {
                throw new InvalidArgumentException("Transisi status dari {$tiket->status} ke {$statusKe} tidak diizinkan.");
            }

            if ($statusKe === 'Menunggu Komponen/Sparepart' && empty($komponenDibutuhkan)) {
                throw new InvalidArgumentException("Nama komponen yang dibutuhkan wajib diisi saat status Menunggu Komponen/Sparepart.");
            }

            $statusAsal = $tiket->status;
            $updates = ['status' => $statusKe];

            // FR-24: Otomatis sinkronkan kondisi aset
            if ($statusKe === 'Selesai') {
                $updates['tanggal_selesai'] = now();
                $tiket->aset?->update(['kondisi' => 'Baik']);
            } elseif ($statusKe === 'Rusak Total') {
                $tiket->aset?->update(['kondisi' => 'Tidak Layak Pakai']);
            } elseif ($statusKe === 'Diproses') {
                $tiket->aset?->update(['kondisi' => 'Dalam Perbaikan']);
            }

            $tiket->update($updates);

            // Simpan foto bukti perbaikan jika ada
            $fotoPath = null;
            if ($fotoBukti instanceof UploadedFile) {
                $fotoPath = $fotoBukti->store("tikets/{$tiket->nomor_tiket}/logs", 'public');
            }

            LogPenanganan::create([
                'tiket_id' => $tiket->id,
                'user_id' => $teknisi->id,
                'status_dari' => $statusAsal,
                'status_ke' => $statusKe,
                'catatan' => $catatan,
                'komponen_dibutuhkan' => $statusKe === 'Menunggu Komponen/Sparepart' ? $komponenDibutuhkan : null,
                'foto_bukti' => $fotoPath,
            ]);

            return $tiket->fresh();
        });
    }

    /**
     * Konfirmasi penyelesaian tiket oleh Member pelapor (FR-19).
     */
    public function konfirmasiSelesaiMember(Tiket $tiket, User $member): Tiket
    {
        return DB::transaction(function () use ($tiket, $member) {
            if ($tiket->pelapor_id !== $member->id) {
                throw new InvalidArgumentException("Anda hanya dapat mengonfirmasi tiket milik Anda sendiri.");
            }

            if ($tiket->status !== 'Selesai') {
                throw new InvalidArgumentException("Tiket belum berstatus Selesai.");
            }

            $tiket->update([
                'dikonfirmasi_at' => now(),
            ]);

            LogPenanganan::create([
                'tiket_id' => $tiket->id,
                'user_id' => $member->id,
                'status_dari' => 'Selesai',
                'status_ke' => 'Selesai',
                'catatan' => "Hasil perbaikan diperiksa dan disetujui (dikonfirmasi) oleh pelapor {$member->nama}.",
            ]);

            return $tiket->fresh();
        });
    }

    /**
     * Membuka kembali tiket oleh Member jika perbaikan belum tuntas (FR-19, Bagian 9).
     */
    public function bukaKembaliMember(Tiket $tiket, string $alasan, User $member): Tiket
    {
        return DB::transaction(function () use ($tiket, $alasan, $member) {
            if ($tiket->pelapor_id !== $member->id) {
                throw new InvalidArgumentException("Anda hanya dapat membuka kembali tiket milik Anda sendiri.");
            }

            if ($tiket->status !== 'Selesai') {
                throw new InvalidArgumentException("Hanya tiket berstatus Selesai yang dapat dibuka kembali.");
            }

            if (empty(trim($alasan))) {
                throw new InvalidArgumentException("Alasan buka kembali wajib diisi.");
            }

            $tiket->update([
                'status' => 'Diproses',
                'tanggal_selesai' => null,
                'dikonfirmasi_at' => null,
            ]);

            // Kembalikan kondisi aset ke 'Dalam Perbaikan'
            $tiket->aset?->update(['kondisi' => 'Dalam Perbaikan']);

            LogPenanganan::create([
                'tiket_id' => $tiket->id,
                'user_id' => $member->id,
                'status_dari' => 'Selesai',
                'status_ke' => 'Diproses',
                'catatan' => "Tiket dibuka kembali oleh pelapor {$member->nama}. Alasan: {$alasan}",
            ]);

            return $tiket->fresh();
        });
    }

    /**
     * Konfirmasi penutupan tiket tamu (tanpa login) oleh Admin Lab (FR-19).
     */
    public function konfirmasiSelesaiAdmin(Tiket $tiket, User $admin): Tiket
    {
        return DB::transaction(function () use ($tiket, $admin) {
            if (! $tiket->isGuest()) {
                throw new InvalidArgumentException("Tiket ini milik member terdaftar dan harus dikonfirmasi langsung oleh member tersebut.");
            }

            if ($tiket->status !== 'Selesai') {
                throw new InvalidArgumentException("Tiket belum berstatus Selesai.");
            }

            $tiket->update([
                'dikonfirmasi_at' => now(),
            ]);

            LogPenanganan::create([
                'tiket_id' => $tiket->id,
                'user_id' => $admin->id,
                'status_dari' => 'Selesai',
                'status_ke' => 'Selesai',
                'catatan' => "Verifikasi akhir dan penutupan tiket tamu dikonfirmasi oleh Admin Lab ({$admin->nama}).",
            ]);

            return $tiket->fresh();
        });
    }
}
