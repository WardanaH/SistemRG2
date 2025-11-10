<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        $dataProduk = [];
        $dataBahan = [];
        $satuanList = ['KG', 'PCS', 'PAKET', 'METER', 'CENTIMETER'];
        for ($i = 1; $i <= 10; $i++) {
            $dataProduk[] = [
                'kategori_id' => rand(1, 3),
                'nama_produk' => 'Produk ' . $i,
                'satuan' => $satuanList[array_rand($satuanList)],
                'harga_beli' => rand(5000, 50000),
                'harga_jual' => rand(6000, 70000),
                'hitung_luas' => (bool)rand(0, 1),
                'keterangan' => 'Keterangan produk ' . $i,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            $dataBahan[] = [
                'kategori_id' => rand(1, 3),
                'nama_bahan' => 'Bahan Baku ' . $i,
                'satuan' => $satuanList[array_rand($satuanList)],
                'harga' => rand(10000, 100000),
                'batas_stok' => rand(5, 50),
                'hitung_luas' => (bool)rand(0, 1),
                'keterangan' => 'Keterangan bahan baku ' . $i,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        DB::table('produks')->insert($dataProduk);
        DB::table('bahanbakus')->insert($dataBahan);
    }
}
