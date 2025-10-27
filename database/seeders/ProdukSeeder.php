<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        $data = [];
        for ($i = 1; $i <= 10; $i++) {
            $data[] = [
                'kategori_id' => rand(1, 10),
                'nama_produk' => 'Produk ' . $i,
                'satuan' => 'pcs',
                'harga_beli' => rand(5000, 50000),
                'harga_jual' => rand(6000, 70000),
                'hitung_luas' => (bool)rand(0, 1),
                'keterangan' => 'Keterangan produk ' . $i,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        DB::table('produks')->insert($data);
    }
}
