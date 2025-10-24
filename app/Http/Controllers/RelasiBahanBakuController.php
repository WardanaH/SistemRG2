<?php

namespace App\Http\Controllers;

use App\Models\MRelasiBahanBaku;
use App\Models\MProduks;
use App\Models\MBahanBakus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RelasiBahanBakuController extends Controller
{
    public function index()
    {
        $produks = MProduks::all();
        $bahanbakus = MBahanBakus::all();

        return view('admin.relasibahanbaku.index', compact('produks', 'bahanbakus'));
    }

    public function loadrelasibahanbaku()
    {
        $data = MRelasiBahanBaku::with(['produk', 'bahanbaku'])->get();

        // tambahkan field terenkripsi ke setiap item
        $data->transform(function ($item) {
        $item->encrypted_id = encrypt($item->id);
        return $item;
    });

    return response()->json(['data' => $data]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tambah_r_produk' => 'required',
            'tambah_r_bahan_baku' => 'required',
            'tambah_qty_p_trans' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        try {
            MRelasiBahanBaku::create([
                'produk_id' => decrypt($request->tambah_r_produk),
                'bahanbaku_id' => decrypt($request->tambah_r_bahan_baku),
                'qtypertrx' => $request->tambah_qty_p_trans ?? 0,
            ]);
            return response()->json("Success");
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'edit_r_produk' => 'required',
            'edit_r_bahan_baku' => 'required',
            'edit_qty_p_trans' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        try {
            $relasi = MRelasiBahanBaku::find(decrypt($request->relasi_id));
            $relasi->update([
                'produk_id' => $request->edit_r_produk,
                'bahanbaku_id' => $request->edit_r_bahan_baku,
                'qtypertrx' => $request->edit_qty_p_trans,
            ]);
            return response()->json("Success");
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $relasi = MRelasiBahanBaku::find(decrypt($request->hapus_relasi_id));
            $relasi->delete();
            return response()->json("Success");
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
