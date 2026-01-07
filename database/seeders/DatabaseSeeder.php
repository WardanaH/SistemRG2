<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder as SeedersRolePermissionSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Contracts\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Jalankan seeders sesuai urutan yang benar
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            KategoriSeeder::class,
            ProdukSeeder::class,
            PelangganSeeder::class,
            RolePermissionSeeder::class
            // BahanBakuSeeder::class
        ]);
    }
}
