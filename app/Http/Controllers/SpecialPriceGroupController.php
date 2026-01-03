<?php

namespace App\Http\Controllers;

use App\Models\MSpecialPricesGroup;
use App\Models\MJenisPelanggan;
use App\Models\MProduks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Validator;
use Carbon\Carbon;

class SpecialPriceGroupController extends Controller
{
    public function index()
    {
        return view('admin.specialpricegroup.index', [
            'jenispelanggans' => MJenisPelanggan::all(),
            'produks'         => MProduks::all(),
        ]);
    }

    public function load()
    {
        return DataTables::of(
            MSpecialPricesGroup::with(['jenispelanggan', 'produk', 'user'])
        )
        ->addColumn('jenis_pelanggan', fn ($d) => $d->jenispelanggan->jenis_pelanggan)
        ->addColumn('nama_produk', fn ($d) => $d->produk->nama_produk)
        ->addColumn('harga_khusus', fn ($d) => 'Rp ' . number_format($d->harga_khusus, 0, ',', '.'))
        ->addColumn('username', function ($d) {return optional($d->user)->username ?? '-';})
        ->addColumn('tanggal', function ($d) {
            return Carbon::parse($d->updated_at)->format('d-m-Y');
        })
        ->addColumn('action', function ($d) {
            return '
                <button class="btn btn-sm btn-warning modal_edit"
                    data-id="'.encrypt($d->id).'"
                    data-jenispelanggan_id="'.$d->jenispelanggan_id.'"
                    data-produk_id="'.$d->produk_id.'"
                    data-harga="'.$d->harga_khusus.'">
                    Edit
                </button>

                <button class="btn btn-sm btn-danger btn_hapus"
                    data-id="'.encrypt($d->id).'"
                    data-jenis="'.$d->jenispelanggan->jenis_pelanggan.'"
                    data-produk="'.$d->produk->nama_produk.'"
                    data-harga="'.$d->harga_khusus.'">
                    Hapus
                </button>
            ';
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function store(Request $request)
    {
        $rules = [
            'jenispelanggan_id' => 'required',
            'produk_id'         => 'required',
            'harga_khusus'      => 'required|numeric',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $exists = MSpecialPricesGroup::where([
            'jenispelanggan_id' => $request->jenispelanggan_id,
            'produk_id'         => $request->produk_id,
        ])->exists();

        if ($exists) {
            return response()->json('Duplicated', 409);
        }

        MSpecialPricesGroup::create([
            'jenispelanggan_id' => decrypt($request->jenispelanggan_id),
            'produk_id'         => $request->produk_id,
            'harga_khusus'      => $request->harga_khusus,
            'user_id'           => Auth::id(),
        ]);

        return response()->json('Success');
    }

    public function update(Request $request)
    {
        $data = MSpecialPricesGroup::findOrFail(
            decrypt($request->id_spg)
        );

        $data->update([
            'jenispelanggan_id' => $request->jenispelanggan_id,
            'produk_id'         => $request->produk_id,
            'harga_khusus'      => $request->harga_khusus,
            'user_id'           => Auth::id(),
        ]);

        return response()->json('Success');
    }


    public function destroy(Request $request)
    {
        MSpecialPricesGroup::findOrFail(
            decrypt($request->hapus_spg_id)
        )->delete();

        return response()->json('Success');
    }

}
