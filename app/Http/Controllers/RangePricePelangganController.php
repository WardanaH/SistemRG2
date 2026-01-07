<?php

namespace App\Http\Controllers;

use App\Models\MRangePricePelanggan;
use App\Models\MProduks;
use App\Models\MPelanggans;
use App\Models\MSpecialPrices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Validator;

class RangePricePelangganController extends Controller
{
    /**
     * =====================
     * PAGE UTAMA
     * (ENTRY DARI SIDEBAR)
     * =====================
     */
    public function page()
    {
        $produks = MProduks::orderBy('nama_produk')->get();
        $pelanggans = MPelanggans::where('status_pelanggan', 1)
        ->orderBy('nama_perusahaan')
        ->get();

        return view(
            'admin.rangepricepelanggan.index',
            compact('produks', 'pelanggans')
        );
    }

    public function loadByProduk($produk_id)
    {
        return MSpecialPrices::with('pelanggan')
            ->where('produk_id', decrypt($produk_id))
            ->get();
    }

    public function loadSpecialPriceByProduk($produk_id)
    {
        return MSpecialPrices::with('pelanggan')
            ->where('produk_id', decrypt($produk_id))
            ->get();
    }

    /**
     * =====================
     * LOAD RANGE (AJAX)
     * =====================
     */
    public function index($id)
    {
        $ranges = MRangePricePelanggan::where(
            'specialprice_id',
            decrypt($id)
        )
        ->orderBy('nilai_awal')
        ->get();

        // biar konsisten kayak sistem lama
        foreach ($ranges as $r) {
            $r->uniq_id = encrypt($r->id);
            $r->uniq_specialprice_id = encrypt($r->specialprice_id);
        }

        return Response::json($ranges);
    }

    /**
     * =====================
     * CREATE RANGE
     * =====================
     */
    public function store($id, Request $request)
    {
        $rules = [
            'nilai_awal'   => 'required|numeric|min:1',
            'nilai_akhir'  => 'required|numeric|min:1|gte:nilai_awal',
            'harga_khusus' => 'required|numeric|min:1',
        ];

        $validator = Validator::make(
            $request->all(),
            $rules
        );

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->errors()
            ], 422);
        }

        $range = MRangePricePelanggan::create([
            'specialprice_id' => decrypt($id),
            'nilai_awal'      => $request->nilai_awal,
            'nilai_akhir'     => $request->nilai_akhir,
            'harga_khusus'    => $request->harga_khusus,
            'user_id'         => Auth::id(),
        ]);

        $range->uniq_id = encrypt($range->id);

        return Response::json($range);
    }

    public function storeSpecialPrice(Request $request)
    {
        MSpecialPrices::updateOrCreate(
            [
                'produk_id'    => decrypt($request->produk_id),
                'pelanggan_id' => decrypt($request->pelanggan_id),
            ],
            [
                'harga_khusus' => $request->harga_khusus,
                'user_id'      => Auth::id(),
            ]
        );

        return response()->json(['status' => 'success']);
    }

    /**
     * =====================
     * DELETE RANGE
     * =====================
     */
    public function destroy($specialprice_id, $range_id)
    {
        MRangePricePelanggan::where(
            'specialprice_id',
            decrypt($specialprice_id)
        )
        ->where(
            'id',
            decrypt($range_id)
        )
        ->delete();

        return response()->json('Success');
    }
}
