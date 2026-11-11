<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed 1 Admin, 1 Teknisi, 1 Member (Fase 1)
        User::updateOrCreate(
            ['email' => 'admin@silab.sch.id'],
            [
                'nama' => 'Administrator Lab',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'no_hp' => '081234567890',
            ]
        );

        User::updateOrCreate(
            ['email' => 'teknisi@silab.sch.id'],
            [
                'nama' => 'Teknisi Komputer',
                'password' => bcrypt('password'),
                'role' => 'teknisi',
                'no_hp' => '081234567891',
            ]
        );

        User::updateOrCreate(
            ['email' => 'member@silab.sch.id'],
            [
                'nama' => 'Siswa Member',
                'password' => bcrypt('password'),
                'role' => 'member',
                'no_hp' => '081234567892',
            ]
        );

        // Seed Master Data: Labs, Kategoris, Asets (Fase 2)
        $this->call(MasterDataSeeder::class);
    }
}
