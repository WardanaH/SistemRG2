<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {

        $data = [
            [
                'Nama_Kategori' => 'Outdoor',
                'Keterangan' => 'Keterangan untuk kategori outdoor',
                'user_id' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'Nama_Kategori' => 'Indoor',
                'Keterangan' => 'Keterangan untuk kategori indoor',
                'user_id' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'Nama_Kategori' => 'Multi',
                'Keterangan' => 'Keterangan untuk kategori multi',
                'user_id' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('kategories')->insert($data);
    }
}
