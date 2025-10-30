<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\MBahanBakus;
use Illuminate\Http\Request;
use App\Models\MStokBahanBakus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class StokBahanBakusController extends Controller
{
    public function index(Request $request)
    {
        $bahanbakus = MBahanBakus::all();
        $cabangs = Cabang::where('nama', '!=', 'Admin')->get();

        $user = Auth::user();
        $role = $user->roles->first()->name ?? '';
        $cabangId = $user->cabang_id ?? ($user->cabangs->id ?? null);

        // 🔧 Base query dengan LEFT JOIN dinamis
        $query = MBahanBakus::query()
            ->leftJoin('stok_bahan_bakus', function ($join) use ($role, $request, $cabangId) {
                $join->on('bahanbakus.id', '=', 'stok_bahan_bakus.bahanbaku_id');

                // Owner pilih cabang tertentu
                if ($role === 'owner' && $request->filled('cabang_id')) {
                    $join->where('stok_bahan_bakus.cabang_id', '=', decrypt($request->cabang_id));
                }
                // User cabang biasa
                elseif ($role !== 'owner') {
                    $join->where('stok_bahan_bakus.cabang_id', '=', $cabangId);
                }
            })
            ->leftJoin('cabangs', 'stok_bahan_bakus.cabang_id', '=', 'cabangs.id')
            ->select(
                'bahanbakus.id',
                'bahanbakus.nama_bahan',
                'bahanbakus.satuan',
                'bahanbakus.batas_stok'
            );

        // === Jika owner tanpa filter cabang → tampilkan total seluruh cabang ===
        if ($role === 'owner' && !$request->filled('cabang_id')) {
            $query->addSelect([
                DB::raw('COALESCE(SUM(stok_bahan_bakus.banyak_stok), 0) as banyak_stok'),
                DB::raw("GROUP_CONCAT(DISTINCT cabangs.kode ORDER BY cabangs.kode SEPARATOR ', ') as nama_cabang"),
                DB::raw('MAX(stok_bahan_bakus.id) as stok_id')
            ])
                ->groupBy('bahanbakus.id', 'bahanbakus.nama_bahan', 'bahanbakus.satuan', 'bahanbakus.batas_stok');
        } else {
            // === Selain itu (owner pilih cabang atau user biasa)
            $query->addSelect([
                'stok_bahan_bakus.id as stok_id',
                DB::raw('COALESCE(stok_bahan_bakus.banyak_stok, 0) as banyak_stok'),
                'stok_bahan_bakus.stok_hitung_luas',
                'cabangs.nama as nama_cabang',
                'cabangs.kode as kode_cabang',
            ]);
        }

        // 🔍 Filter bahan baku
        if ($request->filled('bahanbaku_id')) {
            $query->where('bahanbakus.id', decrypt($request->bahanbaku_id));
        }

        // 🔽 Urutkan dan ambil hasil
        $data = $query->orderBy('bahanbakus.nama_bahan', 'asc')->paginate(30);

        return view('stokbahanbaku.list', [
            'datas' => $data,
            'bahanbakus' => $bahanbakus,
            'cabangs' => $cabangs,
            'bahanbaku_id' => $request->bahanbaku_id ?? '',
            'cabang_id' => $request->cabang_id ?? '',
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $cabangId = Auth::user()->cabangs->id ?? Auth::user()->cabang_id;
            if (!$cabangId) {
                return redirect()->back()->with('error', 'Cabang pengguna tidak ditemukan!');
            }

            // 🧩 Ambil stok_id dan bahanbaku_id
            $stokId = $request->has('stok_id') && !empty($request->stok_id)
                ? Crypt::decrypt($request->stok_id)
                : null;

            $bahanbakuId = Crypt::decrypt($request->bahanbaku_id ?? $id);

            // 🔎 Cek stok berdasarkan stok_id (lebih akurat)
            $stok = $stokId
                ? MStokBahanBakus::find($stokId)
                : MStokBahanBakus::where('bahanbaku_id', $bahanbakuId)
                ->where('cabang_id', $cabangId)
                ->first();

            if ($stok) {
                // 📝 Update stok lama
                $stok->banyak_stok = $request->banyak_stok ?? 0;
                $stok->save();
            } else {
                // 🆕 Buat stok baru
                $bahan = MBahanBakus::find($bahanbakuId);
                MStokBahanBakus::create([
                    'bahanbaku_id' => $bahanbakuId,
                    'cabang_id' => $cabangId,
                    'banyak_stok' => $request->banyak_stok ?? 0,
                    'satuan' => $bahan->satuan ?? '',
                    'stok_hitung_luas' => $bahan->hitung_luas ?? 0,
                ]);
            }

            return redirect()->route('stokbahanbaku.index')->with('success', '✅ Stok bahan baku berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        try {
            $stok = MStokBahanBakus::withTrashed()->findOrFail(decrypt($request->delid));
            $stok->forceDelete(); // ⬅️ ini hapus permanen!

            return redirect()->back()->with('success', 'Stok bahan baku dihapus permanen!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus stok bahan baku.');
        }
    }
}
