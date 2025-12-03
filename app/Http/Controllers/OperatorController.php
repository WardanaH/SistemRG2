<?php

namespace App\Http\Controllers;

use App\Models\MSubTransaksiPenjualans;
use App\Models\MTransaksiPenjualans;
use App\Models\User;
use Illuminate\Http\Request;

class OperatorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua user yang punya role 'operator'
        $operators = User::role(['operator outdoor', 'operator indoor', 'operator multi'])->with('cabang')->get();
        // dd($operators);

        return view('admin.users.operator.index', compact('operators'));
    }

    public function dashboard()
    {
        $selesai = MSubTransaksiPenjualans::where('status_sub_transaksi', 'selesai')->count();
        $belum_selesai = MSubTransaksiPenjualans::where('status_sub_transaksi', '<>', 'selesai')->count();


        return view(
            'operator.dashboard',
            compact('selesai', 'belum_selesai')
        );
    }
    public function profile()
    {
        return view('operator.dashboard');
    }

    public function pesanan()
    {
        $cabangId = auth()->user()->cabang->id;

        $subTransaksiData = MSubTransaksiPenjualans::with('produk')
            ->whereHas('penjualan', function ($query) use ($cabangId) {
                $query->where('cabang_id', $cabangId);
            })
            ->where('status_sub_transaksi', '!=', 'selesai')
            ->get();

        // dd($subTransaksiData);

        return view(
            'operator.status_pesanan',
            compact('subTransaksiData')
        );
    }

    public function updateStatus(Request $request, $id)
    {
        $sub = MSubTransaksiPenjualans::findOrFail($id);
        // dd($sub);

        $sub->update([
            'status_sub_transaksi' => $request->status_sub_transaksi
        ]);

        return redirect()->route('operator.pesanan')->with('success', 'Status berhasil diperbarui');
    }

    public function riwayat()
    {
        $cabangId = auth()->user()->cabang->id;

        $subTransaksiData = MSubTransaksiPenjualans::with('produk')
            ->whereHas('penjualan', function ($query) use ($cabangId) {
                $query->where('cabang_id', $cabangId);
            })
            ->where('status_sub_transaksi', 'selesai')
            ->get();

        return view(
            'operator.riwayat_pesanan',
            compact('subTransaksiData')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
