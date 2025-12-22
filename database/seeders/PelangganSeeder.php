<?php

namespace Database\Seeders;

use App\Models\MJenisPelanggan;
use App\Models\MPelanggans;
use Illuminate\Database\Seeder;

class PelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat jenis pelanggan
        $jenis = [
            ['jenis_pelanggan' => 'Umum'],
            ['jenis_pelanggan' => 'Perusahaan'],
        ];
        $jenisIds = [];
        foreach ($jenis as $dataJenis) {
            $jenisPelanggan = MJenisPelanggan::create($dataJenis);
            $jenisIds[] = $jenisPelanggan->id;
        }

        // Buat data pelanggan dengan relasi ke jenis pelanggan
        $pelangganData = [
            [
                'nama_perusahaan'      => 'PT Nusantara',
                'nama_pemilik'         => 'Andi Wijaya',
                'telpon_pelanggan'     => '0811111111',
                'hp_pelanggan'         => '0811111111',
                'email_pelanggan'      => 'andi@example.com',
                'alamat_pelanggan'     => 'Jl. Melati No.7',
                'tempo_pelanggan'      => 30,
                'limit_pelanggan'      => 5000000,
                'norek_pelanggan'      => '1234567890',
                'keterangan_pelanggan' => 'Customer loyal',
                'ktp'                  => '1234567890123456',
                'status_pelanggan'     => 1,
            ],
            [
                'nama_perusahaan'      => 'CV Nusa Jaya',
                'nama_pemilik'         => 'Rina Lestari',
                'telpon_pelanggan'     => '0822222222',
                'hp_pelanggan'         => '0822222222',
                'email_pelanggan'      => 'rina@nusajaya.com',
                'alamat_pelanggan'     => 'Jl. Kenanga No.9',
                'tempo_pelanggan'      => 14,
                'limit_pelanggan'      => 3000000,
                'norek_pelanggan'      => '0987654321',
                'keterangan_pelanggan' => 'Pelanggan baru',
                'ktp'                  => '6543210987654321',
                'status_pelanggan'     => 1,
            ],
        ];

        // Generate pelanggan dan menunjuk jenis pelanggan secara acak
        foreach ($pelangganData as $data) {
            $data['jenispelanggan_id'] = $jenisIds[array_rand($jenisIds)];
            MPelanggans::create($data);
        }
    }
}
