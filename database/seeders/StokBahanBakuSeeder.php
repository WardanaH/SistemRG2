<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StokBahanBakuSeeder extends Seeder
{
    /**
     * Jalankan database seeds.
     */
    public function run(): void
    {
        $dataStok = [];

        // 1. Ambil semua ID bahan baku yang sudah di-seed oleh ProdukSeeder
        $bahanBakus = DB::table('bahanbakus')->get();

        // 2. Ambil semua ID cabang (Pastikan Anda sudah memiliki CabangSeeder)
        $cabangs = DB::table('cabangs')->get();

        // Jika tabel cabang kosong, kita tidak bisa mengisi stok
        if ($cabangs->isEmpty()) {
            $this->command->warn("Data cabang kosong. Silakan buat CabangSeeder terlebih dahulu!");
            return;
        }

        foreach ($bahanBakus as $bahan) {
            foreach ($cabangs as $cabang) {
                // Kita isi stok untuk setiap bahan baku di setiap cabang
                $dataStok[] = [
                    'banyak_stok'      => rand(50, 200), // Stok acak antara 50 - 200
                    'satuan'           => $bahan->satuan, // Mengikuti satuan dari tabel bahanbakus
                    'stok_hitung_luas' => $bahan->hitung_luas, // Mengikuti setting hitung_luas bahan
                    'bahanbaku_id'     => $bahan->id,
                    'cabang_id'        => $cabang->id,
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ];
            }
        }

        // Insert dalam jumlah banyak sekaligus (batch insert) agar lebih cepat
        DB::table('stok_bahan_bakus')->insert($dataStok);
    }
}
