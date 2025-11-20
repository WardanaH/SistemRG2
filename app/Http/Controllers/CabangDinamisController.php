<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cabang;
use App\Models\MCabangBarang;
use App\Models\MInventarisKantor;

class CabangDinamisController extends Controller
{
    /**
     * 🔹 Daftar Barang per Cabang
     */
    public function barang($slug)
    {
        $cabang = Cabang::where('slug', $slug)->firstOrFail();

        // Ambil data barang berdasarkan id_cabang
        $barangs = MCabangBarang::where('id_cabang', $cabang->id)->get();

        return view('admin.inventaris.templateinventaris.barang', [
            'title'   => 'Daftar Barang - ' . $cabang->nama,
            'cabang'  => $cabang,
            'barangs' => $barangs
        ]);
    }

    /**
     * 🔹 Stok Barang per Cabang
     */
    public function stok($slug)
    {
        $cabang = Cabang::where('slug', $slug)->firstOrFail();

        // Ambil daftar barang cabang (untuk dropdown)
        $barangs = MCabangBarang::where('id_cabang', $cabang->id)->get();

        // Ambil stok — nanti bisa diganti tabel stok asli
        $stoks = MCabangBarang::where('id_cabang', $cabang->id)->get();

        return view("admin.inventaris.templateinventaris.stok", [
            'title'   => 'Stok Barang - ' . $cabang->nama,
            'cabang'  => $cabang,
            'stoks'   => $stoks,
            'barangs' => $barangs
        ]);
    }


    /**
     * 🔹 Inventaris Kantor
     */
    public function inventaris($slug)
    {
        $cabang = Cabang::where('slug', $slug)->firstOrFail();
        $data = MInventarisKantor::where('id_cabang', $cabang->id)->get();

        return view("admin.inventaris.templateinventaris.inventariskantor", [
            'title'  => 'Inventaris Kantor - ' . $cabang->nama,
            'data'   => $data,
            'cabang' => $cabang
        ]);
    }

    /**
     * 🔹 Riwayat Pengiriman
     */
    public function riwayat($slug)
    {
        $cabang = Cabang::where('slug', $slug)->firstOrFail();

        // sementara kosong dulu sampai kamu kasih tahu tabel riwayat yang benar
        $riwayat = collect();  

        return view("admin.inventaris.templateinventaris.riwayat", [
            'title'   => 'Riwayat Pengiriman - ' . $cabang->nama,
            'cabang'  => $cabang,
            'riwayat' => $riwayat
        ]);
    }

}
