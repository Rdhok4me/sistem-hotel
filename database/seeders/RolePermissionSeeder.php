<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache permission
        app()[\Spatie\Permission\PermissionRegistrar::class]
            ->forgetCachedPermissions();

        // ── Buat semua permission ─────────────────────────────
        $permissions = [
            // Reservasi
            'reservasi.view',
            'reservasi.create',
            'reservasi.edit',
            'reservasi.delete',

            // Kamar
            'kamar.view',
            'kamar.create',
            'kamar.edit',
            'kamar.delete',

            // Tamu
            'tamu.view',
            'tamu.create',
            'tamu.edit',

            // Transaksi
            'checkin.create',
            'checkout.create',
            'pembayaran.view',
            'pembayaran.create',
            'invoice.print',

            // Laporan (admin only)
            'laporan.view',
            'laporan.export',

            // User management (admin only)
            'user.view',
            'user.create',
            'user.edit',
            'user.delete',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // ── Role: Resepsionis ─────────────────────────────────
        $resepsionis = Role::firstOrCreate(['name' => 'resepsionis', 'guard_name' => 'web']);
        $resepsionis->syncPermissions([
            'reservasi.view',
            'reservasi.create',
            'reservasi.edit',
            'reservasi.delete',
            'kamar.view',
            'kamar.edit',
            'tamu.view',
            'tamu.create',
            'tamu.edit',
            'checkin.create',
            'checkout.create',
            'pembayaran.view',
            'pembayaran.create',
            'invoice.print',
        ]);

        // ── Role: Admin (akses penuh) ─────────────────────────
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions(Permission::all());

        // ── User: Admin default ───────────────────────────────
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@hotel.com'],
            [
                'name'     => 'Admin Hotel',
                'password' => bcrypt('password'),
            ]
        );
        $adminUser->assignRole('admin');

        // ── User: Resepsionis default ─────────────────────────
        $resepsionisUser = User::firstOrCreate(
            ['email' => 'resepsionis@hotel.com'],
            [
                'name'     => 'Siti Resepsionis',
                'password' => bcrypt('password'),
            ]
        );
        $resepsionisUser->assignRole('resepsionis');

        $this->command->info('✅ Role, permission, dan user default berhasil dibuat.');
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['Admin',       'admin@hotel.com',       'password'],
                ['Resepsionis', 'resepsionis@hotel.com', 'password'],
            ]
        );
    }
}