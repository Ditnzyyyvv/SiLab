<?php

namespace Database\Factories;

use App\Models\Aset;
use App\Models\Kategori;
use App\Models\Lab;
use Illuminate\Database\Eloquent\Factories\Factory;

class AsetFactory extends Factory
{
    protected $model = Aset::class;

    public function definition(): array
    {
        return [
            'kode_aset' => 'AST-EXP-' . str_pad((string) fake()->unique()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT),
            'nama' => 'Perangkat ' . fake()->words(2, true),
            'kategori_id' => Kategori::factory(),
            'lab_id' => Lab::factory(),
            'merek' => fake()->randomElement(['Dell', 'HP', 'Lenovo', 'Cisco', 'MikroTik', 'Epson']),
            'spesifikasi' => fake()->sentence(),
            'tanggal_perolehan' => fake()->date(),
            'kondisi' => 'Baik',
            'foto' => null,
        ];
    }
}
