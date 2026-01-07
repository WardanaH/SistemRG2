<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MInventarisKantor;
use App\Models\Cabang;

class InventarisKantorController extends Controller
{
    // ===============================
    // 📦 Halaman Inventaris per Cabang
    // ===============================

    public function banjarbaru()
    {
        $cabang = Cabang::where('nama', 'Banjarbaru')->first();
        $data = MInventarisKantor::where('id_cabang', optional($cabang)->id)->get(); 

        return view('admin.inventaris.banjarbaru.inventariskantor', compact('data', 'cabang'));
    }

    public function banjarmasin()
    {
        $cabang = Cabang::where('nama', 'Banjarmasin')->first();
        $data = MInventarisKantor::where('id_cabang', optional($cabang)->id)->get(); 

        return view('admin.inventaris.banjarmasin.inventariskantor', compact('data', 'cabang'));
    }

    public function martapura()
    {
        $cabang = Cabang::where('nama', 'Martapura')->first();
        $data = MInventarisKantor::where('id_cabang', optional($cabang)->id)->get(); 

        return view('admin.inventaris.martapura.inventariskantor', compact('data', 'cabang'));
    }

    // public function pelaihari()
    // {
    //     $cabang = Cabang::where('nama', 'Pelaihari')->first();
    //     $data = MInventarisKantor::where('id_cabang', optional($cabang)->id)->get(); // ✅ diperbaiki
    //
    //     return view('admin.inventaris.pelaihari.inventariskantor', compact('data', 'cabang'));
    // }

    // ===============================
    // ➕ Tambah Data Inventaris Kantor
    // ===============================

    public function store(Request $request)
    {
        $request->validate([
            'id_cabang' => 'required|exists:cabangs,id',
            'kode_barang' => 'required|unique:inventaris_kantors,kode_barang',
            'nama_barang' => 'required',
            'jumlah' => 'required|integer|min:1',
            'kondisi' => 'required',
        ]);

        // Tambahkan tanggal input otomatis
        $request->merge([
            'tanggal_input' => now(),
        ]);

        MInventarisKantor::create($request->all());

        return back()->with('success', 'Data inventaris berhasil ditambahkan!');
    }

    // ===============================
    // 🗑️ Hapus Data
    // ===============================

    public function destroy($id)
    {
        $item = MInventarisKantor::findOrFail($id);
        $item->delete();

        return back()->with('success', 'Data inventaris berhasil dihapus!');
    }
}
