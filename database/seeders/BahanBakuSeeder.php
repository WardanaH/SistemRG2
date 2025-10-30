<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BahanBakuSeeder extends Seeder
{
    public function run(): void
    {
        $data = [];
        for ($i = 1; $i <= 10; $i++) {
            $data[] = [
                'kategori_id' => rand(1, 10),
                'nama_bahan' => 'Bahan Baku ' . $i,
                'satuan' => 'kg',
                'harga' => rand(10000, 100000),
                'batas_stok' => rand(5, 50),
                'hitung_luas' => (bool)rand(0, 1),
                'keterangan' => 'Keterangan bahan baku ' . $i,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        DB::table('bahanbakus')->insert($data);
    }
}
