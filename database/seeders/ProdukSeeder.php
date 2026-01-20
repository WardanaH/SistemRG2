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
        $dataBahan  = [];

        $satuanList = ['KG', 'PCS', 'PAKET', 'METER', 'CENTIMETER'];

        for ($i = 1; $i <= 10; $i++) {

            $satuanProduk = $satuanList[array_rand($satuanList)];
            $satuanBahan  = $satuanList[array_rand($satuanList)];

            // Tentukan hitung_luas berdasarkan satuan
            $hitungLuasProduk = in_array($satuanProduk, ['METER', 'CENTIMETER']) ? 1 : 0;
            $hitungLuasBahan  = in_array($satuanBahan,  ['METER', 'CENTIMETER']) ? 1 : 0;

            // Harga bulat kelipatan 1000 antara 10.000 - 20.000
            $hargaBeli = rand(10, 20) * 1000;
            $hargaJual = rand(10, 20) * 1000;
            $hargaBahan = rand(10, 20) * 1000;

            $dataProduk[] = [
                'kategori_id' => rand(1, 3),
                'nama_produk' => 'Produk ' . $i,
                'satuan' => $satuanProduk,
                'harga_beli' => $hargaBeli,
                'harga_jual' => $hargaJual,
                'hitung_luas' => $hitungLuasProduk,
                'keterangan' => 'Keterangan produk ' . $i,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $dataBahan[] = [
                'kategori_id' => rand(1, 3),
                'nama_bahan' => 'Bahan Baku ' . $i,
                'satuan' => $satuanBahan,
                'harga' => $hargaBahan,
                'batas_stok' => rand(5, 50),
                'hitung_luas' => $hitungLuasBahan,
                'keterangan' => 'Keterangan bahan baku ' . $i,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('produks')->insert($dataProduk);
        DB::table('bahanbakus')->insert($dataBahan);
    }
}
