<?php

namespace App\Http\Controllers;

use App\Models\MAngsurans;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\MTransaksiPenjualans;
use Illuminate\Support\Facades\Auth;

class AngsuransController extends Controller
{
    public function index()
    {
        return view('admin.transaksis.angsuran.index');
    }

    public function data(Request $request)
    {
        $query = MTransaksiPenjualans::with(['pelanggan', 'cabang', 'user'])
            ->where('sisa_tagihan', '>', 0);

        // Jika user BUKAN owner / direktur → BATASI CABANG
        if (!Auth::user()->hasRole(['owner', 'direktur'])) {
            $query->where('cabang_id', Auth::user()->cabang_id);
        }

        // Filter No Nota
        if ($request->nonota) {
            $query->where('id', 'like', "%{$request->nonota}%");
        }

        // Filter Nama Pelanggan
        if ($request->nama) {
            $query->where('nama_pelanggan', 'like', "%{$request->nama}%");
        }

        // Filter Metode Pembayaran
        if ($request->pembayaran && $request->pembayaran !== 'semua') {
            $query->where('metode_pembayaran', $request->pembayaran);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('aksi', function ($t) {
                return '
                <button class="btn btn-info btn-sm detailBtn" data-id="' . $t->id . '">Detail</button>
                <button class="btn btn-success btn-sm bayarBtn" data-id="' . $t->id . '">Bayar</button>
                <button class="btn btn-warning btn-sm deleteBtn" data-id="' . $t->id . '">Hapus</button>
            ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }


    public function detail($id)
    {
        $data = MTransaksiPenjualans::with(['subTransaksi.produk'])
            ->where('id', $id)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function bayar(Request $request, $id)
    {
        $request->validate([
            'nominal' => 'required|numeric|min:1',
            'metode' => 'required',
        ]);

        $transaksi = MTransaksiPenjualans::findOrFail($id);

        if ($request->nominal > $transaksi->sisa_tagihan) {
            return response()->json([
                'success' => false,
                'message' => 'Nominal melebihi sisa tagihan!'
            ], 422);
        }

        // Simpan ke tabel angsuran
        MAngsurans::create([
            'transaksi_id' => $id,
            'nominal' => $request->nominal,
            'metode' => $request->metode,
            'user_id' => auth()->id(),
        ]);

        // Update sisa
        $transaksi->sisa_tagihan -= $request->nominal;
        $transaksi->save();

        return response()->json(['success' => true]);
    }


    public function hapus(Request $request, $id)
    {
        $request->validate([
            'alasan' => 'required'
        ]);

        $angsuran = MAngsurans::findOrFail($id);

        // Kembalikan sisa tagihan
        $transaksi = $angsuran->transaksi;
        $transaksi->sisa_tagihan += $angsuran->nominal;
        $transaksi->save();

        // Hapus angsuran
        $angsuran->delete();

        return response()->json(['success' => true]);
    }
}
