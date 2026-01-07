<?php

namespace App\Http\Controllers;

use App\Models\MSpecialPrices;
use App\Models\MPelanggans;
use App\Models\MProduks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Validator;

class SpecialPriceController extends Controller
{
    public function index()
    {
        return view('admin.specialprice.specialprice', [
            'pelanggans' => MPelanggans::all(),
            'produks'    => MProduks::all(),
        ]);
    }

    public function loadspecialprice()
    {
        return DataTables::of(
            MSpecialPrices::with(['pelanggan', 'produk'])
        )
        ->addColumn('nama_perusahaan', fn($d) => $d->pelanggan->nama_perusahaan)
        ->addColumn('nama_produk', fn($d) => $d->produk->nama_produk)
        ->addColumn('harga_khusus', fn($d) => 'Rp '.number_format($d->harga_khusus,0,',','.'))
        ->addColumn('action', function ($d) {
            return '
            <button class="btn btn-sm btn-warning modal_edit"
                data-id="'.encrypt($d->id).'"
                data-id_pelanggan="'.$d->pelanggan_id.'"
                data-id_produk="'.$d->produk_id.'"
                data-harga_khusus="'.$d->harga_khusus.'">
                Edit
            </button>

            <button class="btn btn-sm btn-danger modal_hapus"
                data-id="'.encrypt($d->id).'"
                data-nama_perusahaan="'.$d->pelanggan->nama_perusahaan.'"
                data-nama_produk="'.$d->produk->nama_produk.'"
                data-harga_khusus="'.$d->harga_khusus.'">
                Hapus
            </button>';
        })
                ->rawColumns(['action'])
        ->make(true);
    }

    public function store(Request $request)
    {
        $rules = [
            'pilih_pelanggan' => 'required',
            'pilih_produk' => 'required',
            'tambah_harga_khusus' => 'required|numeric'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()]);
        }

        $exists = MSpecialPrices::where([
            'pelanggan_id' => decrypt($request->pilih_pelanggan),
            'produk_id' => decrypt($request->pilih_produk),
        ])->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Data sudah ada'
            ], 409);
        }

        MSpecialPrices::create([
            'pelanggan_id' => decrypt($request->pilih_pelanggan),
            'produk_id' => decrypt($request->pilih_produk),
            'harga_khusus' => $request->tambah_harga_khusus,
            'user_id' => Auth::id(),
        ]);

        return response()->json('Success');
    }

    public function update(Request $request)
    {
        $data = MSpecialPrices::findOrFail(decrypt($request->spcprice_id));
        $data->update([
            'pelanggan_id' => $request->pilih_edit_pelanggan,
            'produk_id' => $request->pilih_edit_produk,
            'harga_khusus' => $request->edit_harga_khusus,
            'user_id' => Auth::id(),
        ]);

        return response()->json('Success');
    }

    public function destroy(Request $request)
    {
        MSpecialPrices::findOrFail(decrypt($request->hapus_spcprice_id))->delete();
        return response()->json('Success');
    }
}
