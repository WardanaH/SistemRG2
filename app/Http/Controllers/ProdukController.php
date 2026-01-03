<?php

namespace App\Http\Controllers;

use App\Models\MProduks;
use App\Models\MKategories;
use Illuminate\Http\Request;
use App\Models\MSpecialPrices;
use Illuminate\Support\Facades\Validator;

class ProdukController extends Controller
{
    public function index()
    {
        $kategories = MKategories::select('id', 'Nama_Kategori')->get();
        return view('admin.produk.index', compact('kategories'));
    }

    public function loadproduk()
    {
        $produks = MProduks::with('kategori')->whereNull('deleted_at')->latest()->get();
        // dd($produk);
        return response()->json(['data' => $produks]);
    }

    public function store(Request $request)
    {
        $rules = [
            'kategori' => 'required',
            'nama_produk' => 'required',
            'satuan' => 'required',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()]);
        }

        $produk = MProduks::create([
            'kategori_id' => $request->kategori,
            'nama_produk' => $request->nama_produk,
            'satuan' => $request->satuan,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'hitung_luas' => in_array($request->satuan, ['CENTIMETER', 'METER']),
            'keterangan' => $request->keterangan,
        ]);

        return response()->json($produk ? "Success" : "Failed");
    }

    public function update(Request $request)
    {
        $rules = [
            'edit_kategori' => 'required',
            'edit_nama_produk' => 'required',
            'edit_satuan' => 'required',
            'edit_harga_beli' => 'required|numeric',
            'edit_harga_jual' => 'required|numeric',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()]);
        }

        $produk = MProduks::findOrFail($request->produk_id);
        $produk->update([
            'kategori_id' => $request->edit_kategori,
            'nama_produk' => $request->edit_nama_produk,
            'satuan' => $request->edit_satuan,
            'harga_beli' => $request->edit_harga_beli,
            'harga_jual' => $request->edit_harga_jual,
            'hitung_luas' => in_array($request->edit_satuan, ['CENTIMETER', 'METER']),
            'keterangan' => $request->edit_keterangan,
        ]);

        return response()->json("Success");
    }

    public function destroy(Request $request)
    {
        $produk = MProduks::findOrFail($request->hapus_produk_id);
        $produk->delete();
        return response()->json("Success");
    }

    public function produkHarga(Request $request)
    {
        $produk = MProduks::findOrFail($request->produk_id);
        return $produk;
    }

    public function priceprodukkhusus(Request $request)
    {
        $produkId    = $request->produkid;
        $pelangganId = $request->pelanggan;

        // ambil produk (WAJIB)
        $produk = MProduks::findOrFail($produkId);

        // default harga normal
        $harga = $produk->harga_jual;

        // cek special price pelanggan
        if ($pelangganId) {
            $special = MSpecialPrices::where('pelanggan_id', $pelangganId)
                ->where('produk_id', $produkId)
                ->first();

            if ($special) {
                $harga = $special->harga_khusus;
            }
        }

        // range price (jika ada)
        $rangePrices = [];
        if (!empty($produk->range_prices)) {
            $rangePrices = json_decode($produk->range_prices, true);
        }

        return response()->json([
            'produk_id'    => $produk->id,
            'harga_jual'   => $harga,
            'hitung_luas'  => $produk->hitung_luas,
            'satuan'       => $produk->satuan, // ⬅️ INI KUNCI MASALAH ANDA
            'range_prices' => $rangePrices
        ]);
    }
}
