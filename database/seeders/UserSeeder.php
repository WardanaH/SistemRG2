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
        $cabang = Cabang::create([
            'kode' => 'CBG-UTM',
            'nama' => 'Cabang Utama',
            'email' => 'utama@example.com',
            'telepon' => '08123456789',
            'alamat' => 'Jl. Raya No 1',
            'jenis' => 'pusat',
        ]);

        $user = User::create([
            'nama' => 'Admin Utama',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'cabang_id' => $cabang->id,
        ]);

        $user->assignRole('direktur');
    }
}
