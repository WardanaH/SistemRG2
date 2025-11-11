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
        $cabangUtama = Cabang::create([
            'kode' => 'CBG-UTM',
            'nama' => 'Cabang Utama',
            'email' => 'utama@example.com',
            'telepon' => '08123456789',
            'alamat' => 'Jl. Merdeka No. 1, Jakarta',
            'jenis' => 'pusat',
        ]);

        $cabangBjm = Cabang::create([
            'kode' => 'CBG-BJM',
            'nama' => 'Cabang Banjarmasin',
            'email' => 'banjarmasin@example.com',
            'telepon' => '08234567890',
            'alamat' => 'Jl. Veteran No. 2, Banjarmasin',
            'jenis' => 'cabang',
        ]);

        $cabangLgg = Cabang::create([
            'kode' => 'CBG-LGG',
            'nama' => 'Cabang Lianganggang',
            'email' => 'lianganggang@example.com',
            'telepon' => '08234567890',
            'alamat' => 'Jl. A. Yani KM 2, Lianganggang',
            'jenis' => 'cabang',
        ]);

        $cabangPlh = Cabang::create([
            'kode' => 'CBG-PLH',
            'nama' => 'Cabang Pelaihari',
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
            'cabang_id' => $cabangPlh->id,
        ]);
        $designer->assignRole('designer');

        // --- Buat user Operator Indoor ---
        $operatorIndoor = User::create([
            'nama' => 'Operator Cabang',
            'username' => 'operatorIndoor',
            'email' => 'operatorOutdoor@example.com',
            'password' => Hash::make('password'),
            'cabang_id' => $cabangPlh->id,
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
    }
}
