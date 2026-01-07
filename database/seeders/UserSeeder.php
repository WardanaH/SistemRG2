<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Cabang;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // --- Buat 2 cabang ---
        $cabangGDGUtama = Cabang::create([
            'kode' => 'GDG-UTM',
            'nama' => 'Gudang Utama',
            'slug' => 'gudang-utama',
            'email' => 'utama@example.com',
            'telepon' => '08123456789',
            'alamat' => 'Jl. Merdeka No. 1, Jakarta',
            'jenis' => 'pusat',
        ]);

        $cabangUtama = Cabang::create([
            'kode' => 'CBG-UTM',
            'nama' => 'Cabang Utama',
            'slug' => 'cabang-pusat',
            'email' => 'utama@example.com',
            'telepon' => '08123456789',
            'alamat' => 'Jl. Merdeka No. 1, Jakarta',
            'jenis' => 'pusat',
        ]);

        $cabangBjm = Cabang::create([
            'kode' => 'CBG-BJM',
            'nama' => 'Cabang Banjarmasin',
            'slug' => 'cabang-banjarmasin',
            'email' => 'banjarmasin@example.com',
            'telepon' => '08234567890',
            'alamat' => 'Jl. Veteran No. 2, Banjarmasin',
            'jenis' => 'cabang',
        ]);

        $cabangLgg = Cabang::create([
            'kode' => 'CBG-LGG',
            'nama' => 'Cabang Lianganggang',
            'slug' => 'cabang-lianganggang',
            'email' => 'lianganggang@example.com',
            'telepon' => '08234567890',
            'alamat' => 'Jl. A. Yani KM 2, Lianganggang',
            'jenis' => 'cabang',
        ]);

        $cabangPlh = Cabang::create([
            'kode' => 'CBG-PLH',
            'nama' => 'Cabang Pelaihari',
            'slug' => 'cabang-pelaihari',
            'email' => 'pelaihari@example.com',
            'telepon' => '08234567890',
            'alamat' => 'Jl. A. Yani KM 12, Pelaihari',
            'jenis' => 'cabang',
        ]);

        // --- Buat user Direktur ---
        $direktur = User::create([
            'nama' => 'Direktur Utama',
            'username' => 'direktur',
            'email' => 'direktur@example.com',
            'password' => Hash::make('password'),
            'cabang_id' => $cabangUtama->id,
        ]);
        $direktur->assignRole('direktur');

        // --- Buat user Owner ---
        $owner = User::create([
            'nama' => 'Owner Utama',
            'username' => 'owner',
            'email' => 'owner@example.com',
            'password' => Hash::make('password'),
            'cabang_id' => $cabangUtama->id,
        ]);
        $owner->assignRole('owner');

        // --- Buat user Admin ---
        $admin = User::create([
            'nama' => 'Admin Cabang',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'cabang_id' => $cabangBjm->id,
        ]);
        $admin->assignRole('admin');

        // --- Buat user Supervisor ---
        $supervisor = User::create([
            'nama' => 'Supervisor Operasional',
            'username' => 'supervisor',
            'email' => 'supervisor@example.com',
            'password' => Hash::make('password'),
            'cabang_id' => $cabangBjm->id,
        ]);
        $supervisor->assignRole('supervisor');

        // --- Buar Manajemen
        $manajemen = User::create([
            'nama' => 'Manajemen Cabang',
            'username' => 'manajemen',
            'email' => 'manajemen@example.com',
            'password' => Hash::make('password'),
            'cabang_id' => $cabangBjm->id,
        ]);
        $manajemen->assignRole('manajemen');

        // --- Buat user admin ---
        $adminLgg = User::create([
            'nama' => 'Admin Cabang',
            'username' => 'adminlgg',
            'email' => 'adminLgg@example.com',
            'password' => Hash::make('password'),
            'cabang_id' => $cabangLgg->id,
        ]);
        $adminLgg->assignRole('admin');

        // --- buat user admin plh ---
        $adminPlh = User::create([
            'nama' => 'Admin Cabang',
            'username' => 'adminplh',
            'email' => 'adminPlh@example.com',
            'password' => Hash::make('password'),
            'cabang_id' => $cabangPlh->id,
        ]);
        $adminPlh->assignRole('admin');

        // --- Buat user Designer ---
        $designer = User::create([
            'nama' => 'Designer Cabang',
            'username' => 'designer',
            'email' => 'designer@example.com',
            'password' => Hash::make('password'),
            'cabang_id' => $cabangBjm->id,
        ]);
        $designer->assignRole('designer');

        // --- Buat user Operator Indoor ---
        $operatorIndoor = User::create([
            'nama' => 'Operator Cabang',
            'username' => 'operatorIndoor',
            'email' => 'operatorOutdoor@example.com',
            'password' => Hash::make('password'),
            'cabang_id' => $cabangBjm->id,
        ]);
        $operatorIndoor->assignRole('operator indoor');

        // --- Buat user Operator Outdoor ---
        $operatorOutdoor = User::create([
            'nama' => 'Operator Cabang',
            'username' => 'operatorOutdoor',
            'email' => 'operatorIndoor@example.com',
            'password' => Hash::make('password'),
            'cabang_id' => $cabangBjm->id,
        ]);
        $operatorOutdoor->assignRole('operator outdoor');

        // --- Buat user Operator Multi ---
        $operatorMulti = User::create([
            'nama' => 'Operator Cabang',
            'username' => 'operatorMulti',
            'email' => 'operatorMulti@example.com',
            'password' => Hash::make('password'),
            'cabang_id' => $cabangBjm->id,
        ]);
        $operatorMulti->assignRole('operator multi');

        // --- Buat user Adversting ---
        $adversting = User::create([
            'nama' => 'Adversting Cabang',
            'username' => 'adversting',
            'email' => 'adversting@example.com',
            'password' => Hash::make('password'),
            'cabang_id' => $cabangBjm->id,
        ]);
        $adversting->assignRole('adversting');

        // --- Buat user Inventory ---
        $inventory = User::create([
            'nama' => 'Inventory Cabang Utama',
            'username' => 'inventoryUtama',
            'email' => 'inventory@example.com',
            'password' => Hash::make('password'),
            'cabang_id' => $cabangGDGUtama->id,
        ]);
        $inventory->assignRole('inventory utama');

        $inventory = User::create([
            'nama' => 'Inventory Cabang Banjamasin',
            'username' => 'inventory cabang bjm',
            'email' => 'inventorycabangbjm@example.com',
            'password' => Hash::make('password'),
            'cabang_id' => $cabangBjm->id,
        ]);
        $inventory->assignRole('inventory utama');

        // --- Buat user Documentation ---
        $documentation = User::create([
            'nama' => 'Documentation Cabang',
            'username' => 'documentation',
            'email' => 'documentation@example.com',
            'password' => Hash::make('password'),
            'cabang_id' => $cabangUtama->id,
        ]);
        $documentation->assignRole('documentation');
    }
}
