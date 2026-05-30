<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipeKamarSeeder extends Seeder
{
    public function run(): void
    {
        $tipeKamar = [
            [
                'nama'             => 'Standard',
                'harga_per_malam'  => 350000,
                'kapasitas'        => 2,
                'fasilitas'        => 'AC, TV 32 inch, Kamar mandi dalam, Wi-Fi gratis, Air panas, Tempat tidur double',
                'foto'             => null,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'nama'             => 'Deluxe',
                'harga_per_malam'  => 550000,
                'kapasitas'        => 2,
                'fasilitas'        => 'AC, TV 43 inch Smart TV, Kamar mandi dalam dengan bathtub, Wi-Fi gratis, Air panas, Minibar, Tempat tidur queen size, Sofa, Meja kerja',
                'foto'             => null,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
            [
                'nama'             => 'Suite',
                'harga_per_malam'  => 900000,
                'kapasitas'        => 4,
                'fasilitas'        => 'AC, TV 55 inch Smart TV, Kamar mandi dalam dengan bathtub & shower, Wi-Fi gratis, Air panas, Minibar lengkap, Tempat tidur king size, Ruang tamu terpisah, Balkon, Layanan kamar 24 jam, Sarapan gratis untuk 2 orang',
                'foto'             => null,
                'created_at'       => now(),
                'updated_at'       => now(),
            ],
        ];

        DB::table('tipe_kamar')->insert($tipeKamar);
        $this->command->info('✅ Tipe kamar berhasil dibuat: Standard, Deluxe, Suite.');
    }
}
