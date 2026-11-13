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
        // 1. Seed Pengguna Utama (Admin, 2 Teknisi, 2 Member)
        User::updateOrCreate(
            ['email' => 'admin@silab.sch.id'],
            [
                'nama' => 'Dr. Ir. H. Bambang Hartono, M.T. (Admin)',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'no_hp' => '081234567890',
            ]
        );

        User::updateOrCreate(
            ['email' => 'teknisi@silab.sch.id'],
            [
                'nama' => 'Faisal Rahman, S.Kom. (Teknisi PC & HW)',
                'password' => bcrypt('password'),
                'role' => 'teknisi',
                'no_hp' => '081234567891',
            ]
        );

        User::updateOrCreate(
            ['email' => 'teknisi2@silab.sch.id'],
            [
                'nama' => 'Rian Hidayat, A.Md. (Teknisi Jaringan & Lab)',
                'password' => bcrypt('password'),
                'role' => 'teknisi',
                'no_hp' => '081234567894',
            ]
        );

        User::updateOrCreate(
            ['email' => 'member@silab.sch.id'],
            [
                'nama' => 'Ahmad Fauzi (Siswa RPL)',
                'password' => bcrypt('password'),
                'role' => 'member',
                'no_hp' => '081234567892',
            ]
        );

        User::updateOrCreate(
            ['email' => 'siswa2@silab.sch.id'],
            [
                'nama' => 'Siti Aisyah (Siswa TKJ)',
                'password' => bcrypt('password'),
                'role' => 'member',
                'no_hp' => '081234567893',
            ]
        );

        // 2. Seed Master Data: Labs, Kategoris, Asets (Fase 2)
        $this->call(MasterDataSeeder::class);

        // 3. Seed Tiket Data & Log Penanganan (Fase 3 & Fase 4)
        $this->call(TiketSeeder::class);
    }
}
