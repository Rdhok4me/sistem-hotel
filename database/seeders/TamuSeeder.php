<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TamuSeeder extends Seeder
{
    public function run(): void
    {
        $tamu = [
            ['nama_lengkap' => 'Budi Santoso',     'nik' => '3578012501850001', 'jenis_kelamin' => 'L', 'no_telepon' => '08123456789', 'email' => 'budi@email.com',     'alamat' => 'Jl. Mawar No. 1, Surabaya',   'kota_asal' => 'Surabaya'],
            ['nama_lengkap' => 'Siti Rahayu',      'nik' => '3578016603920002', 'jenis_kelamin' => 'P', 'no_telepon' => '08234567890', 'email' => 'siti@email.com',     'alamat' => 'Jl. Melati No. 5, Jakarta',   'kota_asal' => 'Jakarta'],
            ['nama_lengkap' => 'Ahmad Fauzi',      'nik' => '3578011202880003', 'jenis_kelamin' => 'L', 'no_telepon' => '08345678901', 'email' => 'ahmad@email.com',    'alamat' => 'Jl. Kenanga No. 12, Bandung', 'kota_asal' => 'Bandung'],
            ['nama_lengkap' => 'Dewi Permata',     'nik' => '3578015508950004', 'jenis_kelamin' => 'P', 'no_telepon' => '08456789012', 'email' => 'dewi@email.com',     'alamat' => 'Jl. Anggrek No. 3, Malang',   'kota_asal' => 'Malang'],
            ['nama_lengkap' => 'Rudi Hartono',     'nik' => '3578010709900005', 'jenis_kelamin' => 'L', 'no_telepon' => '08567890123', 'email' => 'rudi@email.com',     'alamat' => 'Jl. Dahlia No. 8, Semarang',  'kota_asal' => 'Semarang'],
            ['nama_lengkap' => 'Lina Wulandari',   'nik' => '3578012208870006', 'jenis_kelamin' => 'P', 'no_telepon' => '08678901234', 'email' => 'lina@email.com',     'alamat' => 'Jl. Tulip No. 6, Yogyakarta', 'kota_asal' => 'Yogyakarta'],
            ['nama_lengkap' => 'Hendra Kusuma',    'nik' => '3578011503910007', 'jenis_kelamin' => 'L', 'no_telepon' => '08789012345', 'email' => 'hendra@email.com',   'alamat' => 'Jl. Cempaka No. 9, Jember',   'kota_asal' => 'Jember'],
            ['nama_lengkap' => 'Rina Andriani',    'nik' => '3578014411930008', 'jenis_kelamin' => 'P', 'no_telepon' => '08890123456', 'email' => 'rina@email.com',     'alamat' => 'Jl. Flamboyan No. 2, Bali',   'kota_asal' => 'Denpasar'],
            ['nama_lengkap' => 'Doni Prasetyo',    'nik' => '3578012806860009', 'jenis_kelamin' => 'L', 'no_telepon' => '08901234567', 'email' => 'doni@email.com',     'alamat' => 'Jl. Sakura No. 14, Medan',    'kota_asal' => 'Medan'],
            ['nama_lengkap' => 'Fitri Handayani',  'nik' => '3578011909920010', 'jenis_kelamin' => 'P', 'no_telepon' => '08112345678', 'email' => 'fitri@email.com',    'alamat' => 'Jl. Bougenville No. 7, Solo', 'kota_asal' => 'Solo'],
        ];

        foreach ($tamu as &$t) {
            $t['jenis_identitas'] = 'ktp';
            $t['created_at']      = now();
            $t['updated_at']      = now();
        }

        DB::table('tamu')->insert($tamu);
        $this->command->info('✅ 10 data tamu contoh berhasil dibuat.');
    }
}
