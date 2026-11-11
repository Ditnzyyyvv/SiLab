<?php

namespace Database\Factories;

use App\Models\Lab;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LabFactory extends Factory
{
    protected $model = Lab::class;

    public function definition(): array
    {
        $code = strtoupper(fake()->unique()->lexify('???'));
        return [
            'kode' => $code,
            'nama' => 'Laboratorium ' . fake()->words(2, true),
            'lokasi' => 'Gedung ' . fake()->randomElement(['A', 'B', 'C']) . ' Lantai ' . fake()->numberBetween(1, 3),
            'penanggung_jawab_id' => User::factory(),
        ];
    }
}

