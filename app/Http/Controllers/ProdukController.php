<?php

namespace App\Http\Controllers;

use App\Models\MProduks;
use App\Models\MKategories;
use App\Models\MPelanggans;
use App\Models\MRangePricePelanggan;
use Illuminate\Http\Request;
use App\Models\MSpecialPrices;
use App\Models\MSpecialPricesGroup;
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

        $isi = auth()->user()->username . " telah menambahkan produk " . $produk->nama_produk . ".";
        $this->log($isi, "Penambahan");

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

        $isi = auth()->user()->username . " telah mengubah produk " . $produk->nama_produk . ".";
        $this->log($isi, "Pengubahan");

        return response()->json("Success");
    }

    public function destroy(Request $request)
    {
        $produk = MProduks::findOrFail($request->hapus_produk_id);
        $produk->delete();

        $isi = auth()->user()->username . " telah menghapus produk " . $produk->nama_produk . ".";
        $this->log($isi, "Penghapusan");

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

        // 1. Ambil data Produk (Default)
        $produk = MProduks::findOrFail($produkId);

        // Inisialisasi nilai default dari tabel Produk
        $hargaFinal  = $produk->harga_jual;
        $rangePrices = [];
        $userId      = $produk->user_id;

        // 2. Jika ada Pelanggan, jalankan logika Hierarki
        if ($pelangganId) {
            $pelanggan = MPelanggans::find($pelangganId);

            if ($pelanggan) {
                // PRIORITAS 1: Cek Harga Khusus Individu
                $specialIndividu = MSpecialPrices::where('pelanggan_id', $pelangganId)
                    ->where('produk_id', $produkId)
                    ->first();

                if ($specialIndividu) {
                    $hargaFinal  = $specialIndividu->harga_khusus;
                    $userId      = $specialIndividu->user_id;
                    // Ambil range price khusus pelanggan
                    $rangePrices = MRangePricePelanggan::where('specialprice_id', $specialIndividu->id)->get();
                } else {
                    // PRIORITAS 2: Cek Harga Khusus Grup (berdasarkan jenispelanggan_id)
                    $specialGroup = MSpecialPricesGroup::where('jenispelanggan_id', $pelanggan->jenispelanggan_id)
                        ->where('produk_id', $produkId)
                        ->first();

                    if ($specialGroup) {
                        $hargaFinal  = $specialGroup->harga_khusus;
                        $userId      = $specialGroup->user_id;
                        // Ambil range price khusus grup
                        $rangePrices = MSpecialPricesGroup::where('id', $specialGroup->id)->get();
                    }
                }
            }
        }

        // 3. Kembalikan Response
        return response()->json([
            'user_id'      => $userId,
            'produk_id'    => $produk->id,
            'harga_jual'   => $hargaFinal,
            'hitung_luas'  => $produk->hitung_luas,
            'satuan'       => $produk->satuan,
            'range_prices' => $rangePrices
        ]);
    }
}
