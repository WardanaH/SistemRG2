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
        $user = auth()->user();
        $cabangId = $user->cabang->id;
        $role = $user->roles->first()->name ?? null;
        // dd($role);

        $kategoriFilter = $this->getKategoriFilter($role);

        // Query dasar
        $query = MSubTransaksiPenjualans::with('produk.kategori')
            ->whereHas('penjualan', fn($q) => $q->where('cabang_id', $cabangId));

        // Filter kategori jika bukan operator multi
        if ($kategoriFilter) {
            $query->whereHas(
                'produk.kategori',
                fn($q) =>
                $q->where('nama_kategori', $kategoriFilter)
            );
        }

        $selesai = (clone $query)->where('status_sub_transaksi', 'selesai')->count();
        $belum_selesai = (clone $query)->where('status_sub_transaksi', '!=', 'selesai')->count();

        return view('operator.dashboard', compact('selesai', 'belum_selesai'));
    }

    public function profile()
    {
        return view('operator.dashboard');
    }

    public function pesanan()
    {
        $user = auth()->user();
        $cabangId = $user->cabang->id;
        $role = $user->roles->first()->name ?? null;

        $kategoriFilter = $this->getKategoriFilter($role);

        $subTransaksiData = MSubTransaksiPenjualans::with('produk.kategori')
            ->whereHas('penjualan', fn($q) => $q->where('cabang_id', $cabangId))
            ->when($kategoriFilter, function ($query) use ($kategoriFilter) {
                $query->whereHas(
                    'produk.kategori',
                    fn($q) =>
                    $q->where('nama_kategori', $kategoriFilter)
                );
            })
            ->where('status_sub_transaksi', '!=', 'selesai')
            ->get();

        return view('operator.status_pesanan', compact('subTransaksiData'));
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
        $user = auth()->user();
        $cabangId = $user->cabang->id;
        $role = $user->roles->first()->name ?? null;

        $kategoriFilter = $this->getKategoriFilter($role);

        $subTransaksiData = MSubTransaksiPenjualans::with('produk.kategori')
            ->whereHas('penjualan', fn($q) => $q->where('cabang_id', $cabangId))
            ->when($kategoriFilter, function ($query) use ($kategoriFilter) {
                $query->whereHas(
                    'produk.kategori',
                    fn($q) =>
                    $q->where('nama_kategori', $kategoriFilter)
                );
            })
            ->where('status_sub_transaksi', 'selesai')
            ->get();

        return view('operator.riwayat_pesanan', compact('subTransaksiData'));
    }

    private function getKategoriFilter($role)
    {
        return match ($role) {
            'operator indoor' => 'Indoor',
            'operator outdoor' => 'Outdoor',
            'operator multi' => null,
            default => null,
        };
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
