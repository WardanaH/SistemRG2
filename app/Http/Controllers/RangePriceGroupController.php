<?php

namespace App\Http\Controllers;

use App\Models\MRangePriceGroup;
use App\Models\MJenisPelanggan;
use App\Models\MProduks;
use App\Models\MSpecialPricesGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class RangePriceGroupController extends Controller
{
    public function index()
    {
        $group = MSpecialPricesGroup::first();

        if (!$group) {
            return view('admin.rangepricegroup.index', [
                'specialPriceGroup' => null,
                'jenispelanggans'   => collect(),
                'produks'           => collect(),
            ]);
        }

        return redirect()->route(
            'rangepricegroup.page',
            encrypt($group->id)
        );
    }

    public function page($id)
    {
        $group = MSpecialPricesGroup::findOrFail(decrypt($id));

        return view('admin.rangepricegroup.index', [
            'specialPriceGroup' => $group,
            'jenispelanggans'   => MJenisPelanggan::whereNull('deleted_at')->get(),
            'produks'           => MProduks::whereNull('deleted_at')->get(),
        ]);
    }

    public function data($id)
    {
        $ranges = MRangePriceGroup::where(
            'special_price_group_id',
            decrypt($id)
        )->with('produk')->get();

        foreach ($ranges as $range) {
            $range->uniq_id = encrypt($range->id);
        }

        return Response::json($ranges);
    }

    public function store(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'produk_id'    => 'required',
            'nilai_awal'   => 'required|numeric|min:1',
            'nilai_akhir'  => 'required|numeric|min:1',
            'harga_khusus' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return Response::json(['errors' => $validator->errors()], 422);
        }

        $range = MRangePriceGroup::create([
            'special_price_group_id' => decrypt($id),
            'produk_id'              => $request->produk_id,
            'nilai_awal'             => $request->nilai_awal,
            'nilai_akhir'            => $request->nilai_akhir,
            'harga_khusus'           => $request->harga_khusus,
            'user_id'                => Auth::id(),
        ]);

        $range->load('produk');

        $range->uniq_id = encrypt($range->id);

        return Response::json($range);
    }

    public function destroy($id, $range_id)
    {
        $range = MRangePriceGroup::find(decrypt($range_id));

        if (!$range) {
            return Response::json('Not Found', 404);
        }

        $range->delete();

        return Response::json(true);
    }
}
