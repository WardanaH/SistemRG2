<?php

namespace App\Http\Controllers;

use App\Models\MBahanBakus;
use App\Models\MKategories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BahanBakuController extends Controller
{
    public function index()
    {
        $kategories = MKategories::select('id', 'Nama_Kategori')->get();
        return view('admin.produk.bahanbaku', compact('kategories'));
    }

    public function loadbahanbaku()
    {
        $data = MBahanBakus::with('kategori')->whereNull('deleted_at')->latest()->get();
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function store(Request $request)
    {
        $rules = [
            'tambah_kategori_bb' => 'required',
            'tambah_nama_bahan' => 'required',
            'tambah_satuan' => 'required',
            'tambah_harga' => 'required|numeric',
            'tambah_batas_stok' => 'required|numeric',
            'tambah_keterangan' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()]);
        }

        $bb = MBahanBakus::create([
            'kategori_id' => $request->tambah_kategori_bb,
            'nama_bahan' => $request->tambah_nama_bahan,
            'satuan' => strtoupper($request->tambah_satuan),
            'harga' => $request->tambah_harga,
            'batas_stok' => $request->tambah_batas_stok,
            'hitung_luas' => in_array($request->tambah_satuan, ['CENTIMETER', 'METER']),
            'keterangan' => $request->tambah_keterangan,
        ]);

        return response()->json($bb ? "Success" : "Failed");
    }

    public function update(Request $request)
    {
        $rules = [
            'edit_kategori_bb' => 'required',
            'edit_nama_bahan' => 'required',
            'edit_satuan' => 'required',
            'edit_harga' => 'required|numeric',
            'edit_batas_stok' => 'required|numeric',
            'edit_keterangan' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()]);
        }

        $bb = MBahanBakus::findOrFail($request->produk_id);
        $bb->update([
            'kategori_id' => $request->edit_kategori_bb,
            'nama_bahan' => $request->edit_nama_bahan,
            'satuan' => strtoupper($request->edit_satuan),
            'harga' => $request->edit_harga,
            'batas_stok' => $request->edit_batas_stok,
            'hitung_luas' => in_array($request->edit_satuan, ['CENTIMETER', 'METER']),
            'keterangan' => $request->edit_keterangan,
        ]);

        return response()->json("Success");
    }

    public function destroy(Request $request)
    {
        $bb = MBahanBakus::findOrFail($request->hapus_bahan_baku_id);
        $bb->delete();
        return response()->json("Success");
    }
}
