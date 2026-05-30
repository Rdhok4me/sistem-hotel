<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KamarSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil ID tipe kamar
        $standard = DB::table('tipe_kamar')->where('nama', 'Standard')->first()->id;
        $deluxe   = DB::table('tipe_kamar')->where('nama', 'Deluxe')->first()->id;
        $suite    = DB::table('tipe_kamar')->where('nama', 'Suite')->first()->id;

        $kamar = [];

        // Lantai 1 — nomor 101-110 (10 kamar Standard)
        for ($i = 1; $i <= 10; $i++) {
            $kamar[] = [
                'tipe_kamar_id' => $standard,
                'nomor_kamar'   => '1' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'lantai'        => 1,
                'status'        => 'tersedia',
                'keterangan'    => null,
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
        }

        // Lantai 2 — nomor 201-215 (15 kamar Deluxe)
        for ($i = 1; $i <= 15; $i++) {
            $kamar[] = [
                'tipe_kamar_id' => $deluxe,
                'nomor_kamar'   => '2' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'lantai'        => 2,
                'status'        => 'tersedia',
                'keterangan'    => null,
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
        }

        // Lantai 3 — nomor 301-305 (5 kamar Suite)
        for ($i = 1; $i <= 5; $i++) {
            $kamar[] = [
                'tipe_kamar_id' => $suite,
                'nomor_kamar'   => '3' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'lantai'        => 3,
                'status'        => 'tersedia',
                'keterangan'    => null,
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
        }

        DB::table('kamar')->insert($kamar);
        $this->command->info('✅ 30 kamar berhasil dibuat: 10 Standard (Lt.1), 15 Deluxe (Lt.2), 5 Suite (Lt.3).');
    }
}
