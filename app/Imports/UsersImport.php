<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Skip jika kolom nama kosong (menghindari baris catatan di bawah terbaca)
        if (empty($row['nama'])) {
            return null;
        }

        // 1. Buat User
        $user = new User([
            'nama'      => $row['nama'],
            'username'  => $row['username'],
            'email'     => $row['email'] ?? null,
            'password'  => Hash::make($row['password'] ?? '123456'), // default jika kosong
            'telepon'   => $row['telepon'] ?? null,
            'gaji'      => is_numeric($row['gaji']) ? $row['gaji'] : 0,
            'alamat'    => $row['alamat'] ?? null,
            'cabang_id' => is_numeric($row['cabang_id']) ? $row['cabang_id'] : null,
        ]);

        $user->save();

        // 2. Assign Role (Spatie) - pastikan kolom di excel judulnya 'role'
        if (!empty($row['role'])) {
            // Kita gunakan trim() untuk jaga-jaga ada spasi tak sengaja
            $user->assignRole(trim($row['role']));
        }

        return $user;
    }
}
