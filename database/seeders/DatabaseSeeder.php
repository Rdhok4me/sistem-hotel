<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,   // role & permission dulu
            TipeKamarSeeder::class,        // tipe kamar
            KamarSeeder::class,            // data kamar
            TamuSeeder::class,             // data contoh tamu
        ]);
    }
}
