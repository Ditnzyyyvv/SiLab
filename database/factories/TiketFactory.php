<?php

namespace Database\Factories;

use App\Models\Aset;
use App\Models\Tiket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TiketFactory extends Factory
{
    protected $model = Tiket::class;

    public function definition(): array
    {
        return [
            'nomor_tiket' => 'TKT-' . now()->format('Ymd') . '-' . strtoupper(fake()->unique()->bothify('##??')),
            'aset_id' => Aset::factory(),
            'pelapor_id' => null,
            'nama_pelapor' => fake()->name(),
            'kontak_pelapor' => '08' . fake()->numerify('##########'),
            'judul' => fake()->sentence(4),
            'deskripsi' => fake()->paragraph(),
            'prioritas' => null,
            'status' => 'Pending',
            'teknisi_id' => null,
            'alasan_penolakan' => null,
            'tanggal_disposisi' => null,
            'tanggal_selesai' => null,
            'dikonfirmasi_at' => null,
        ];
    }

    /**
     * Tiket yang dibuat oleh member terdaftar.
     */
    public function member(?User $user = null): static
    {
        return $this->state(function (array $attributes) use ($user) {
            $member = $user ?? User::factory()->create([
                'role' => 'member',
            ]);

            return [
                'pelapor_id' => $member->id,
                'nama_pelapor' => $member->nama,
                'kontak_pelapor' => $member->no_hp,
            ];
        });
    }

    /**
     * Tiket yang dilaporkan oleh tamu (guest) via QR Code tanpa login.
     */
    public function guest(): static
    {
        return $this->state(fn (array $attributes) => [
            'pelapor_id' => null,
        ]);
    }

    /**
     * Tiket dalam status Diproses oleh teknisi.
     */
    public function diproses(?User $teknisi = null, string $prioritas = 'Sedang'): static
    {
        return $this->state(function (array $attributes) use ($teknisi, $prioritas) {
            $teknisiUser = $teknisi ?? User::factory()->create(['role' => 'teknisi']);

            return [
                'status' => 'Diproses',
                'prioritas' => $prioritas,
                'teknisi_id' => $teknisiUser->id,
                'tanggal_disposisi' => now(),
            ];
        });
    }

    /**
     * Tiket dalam status Selesai.
     */
    public function selesai(?User $teknisi = null): static
    {
        return $this->diproses($teknisi)->state(fn (array $attributes) => [
            'status' => 'Selesai',
            'tanggal_selesai' => now(),
        ]);
    }

    /**
     * Tiket ditolak.
     */
    public function ditolak(string $alasan = 'Bukan kerusakan perangkat lab.'): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Ditolak',
            'alasan_penolakan' => $alasan,
        ]);
    }
}
