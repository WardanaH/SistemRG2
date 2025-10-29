<?php

namespace App\Http\Controllers;

use App\Models\MBahanBakus;
use App\Models\MStokBahanBakus;
use App\Models\MTransaksiBahanBakus;
use App\Models\Cabang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class TransaksiBahanBakusController extends Controller
{
    /**
     * 📋 Tampilkan daftar transaksi bahan baku.
     */
    public function index(Request $request)
    {
        $cabangs = Cabang::where('id', '!=', Auth::user()->cabangs->id ?? Auth::user()->cabang_id)->get();
        $bahanbakus = MBahanBakus::all();

        $data = MTransaksiBahanBakus::query()
            ->leftJoin('bahanbakus', 'transaksi_bahan_bakus.bahanbaku_id', '=', 'bahanbakus.id')
            ->leftJoin('cabangs as cabangdari', 'transaksi_bahan_bakus.cabangdari_id', '=', 'cabangdari.id')
            ->leftJoin('cabangs as cabangtujuan', 'transaksi_bahan_bakus.cabangtujuan_id', '=', 'cabangtujuan.id')
            ->leftJoin('users', 'transaksi_bahan_bakus.user_id', '=', 'users.id')
            ->select(
                'transaksi_bahan_bakus.*',
                'bahanbakus.nama_bahan',
                'cabangdari.nama as cabangdari',
                'cabangtujuan.nama as cabangtujuan',
                'users.username'
            );

        // Filter
        if ($request->filled('no')) {
            $data->where('transaksi_bahan_bakus.id', 'like', '%' . $request->no . '%');
        }

        if ($request->filled('namabahanbaku') && $request->namabahanbaku !== 'semua') {
            $data->where('bahanbakus.id', decrypt($request->namabahanbaku));
        }

        if ($request->filled('tanggal')) {
            $data->whereDate('transaksi_bahan_bakus.tanggal', '=', date('Y-m-d', strtotime($request->tanggal)));
        }

        if ($request->filled('cabangtujuan') && $request->cabangtujuan !== 'semua') {
            $data->where('transaksi_bahan_bakus.cabangtujuan_id', decrypt($request->cabangtujuan));
        }

        $datas = $data->orderByDesc('transaksi_bahan_bakus.tanggal')->paginate(30);

        return view('transaksibahanbaku.list', [
            'datas' => $datas,
            'bahanbakus' => $bahanbakus,
            'cabangs' => $cabangs,
            'no' => $request->no,
            'namabahanbaku' => $request->namabahanbaku,
            'date' => $request->tanggal,
            'cabangtujuan' => $request->cabangtujuan,
        ]);
    }

    /**
     * 🆕 Form tambah transaksi bahan baku.
     */
    public function create()
    {
        $bahanbakus = MBahanBakus::all();
        $cabangs = Cabang::where('id', '!=', Auth::user()->cabangs->id ?? Auth::user()->cabang_id)->get();
        // dd($bahanbakus);

        return view('transaksibahanbaku.add', compact('bahanbakus', 'cabangs'));
    }

    public function loadBahanBaku(Request $request)
    {
        try {
            $id = decrypt($request->id); // decrypt ID dari dropdown
            $bahan = \App\Models\MBahanBakus::findOrFail($id);

            return response()->json([
                'success' => true,
                'satuan' => $bahan->satuan,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    /**
     * 💾 Simpan transaksi bahan baku baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bahanbaku_transaksibahanbaku' => 'required',
            'cabangtujuan_transaksibahanbaku' => 'required',
            'banyak_transaksibahanbaku' => 'required|numeric',
        ]);

        DB::beginTransaction();
        try {
            $bahanbakuId = decrypt($request->bahanbaku_transaksibahanbaku);
            $cabangDari = Auth::user()->cabangs->id ?? Auth::user()->cabang_id;
            $cabangTujuan = decrypt($request->cabangtujuan_transaksibahanbaku);

            $bahan = MBahanBakus::findOrFail($bahanbakuId);

            // Cek stok di cabang asal
            $stok = MStokBahanBakus::where('bahanbaku_id', $bahanbakuId)
                ->where('cabang_id', $cabangDari)
                ->first();

            if (!$stok) {
                // Jika belum ada stok, buat baru
                $stok = MStokBahanBakus::create([
                    'banyak_stok' => 0 - $request->banyak_transaksibahanbaku,
                    'stok_hitung_luas' => $bahan->hitung_luas,
                    'satuan' => $bahan->satuan,
                    'bahanbaku_id' => $bahanbakuId,
                    'cabang_id' => $cabangDari,
                ]);
            } else {
                // Kurangi stok dari cabang asal
                $stok->banyak_stok -= $request->banyak_transaksibahanbaku;
                $stok->save();
            }

            // Simpan transaksi
            $transaksi = MTransaksiBahanBakus::create([
                'bahanbaku_id' => $bahanbakuId,
                'cabangdari_id' => $cabangDari,
                'cabangtujuan_id' => $cabangTujuan,
                'banyak' => $request->banyak_transaksibahanbaku,
                'tanggal' => now()->format('Y-m-d'),
                'satuan' => $bahan->satuan,
                'keterangan' => $request->keterangan_transaksibahanbaku,
                'user_id' => Auth::id(),
            ]);

            DB::commit();
            return redirect()->back()->with('success', '✅ Transaksi bahan baku berhasil disimpan!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', '❌ Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    /**
     * ✏️ Tampilkan form edit.
     */
    public function show($id)
    {
        $data = MTransaksiBahanBakus::with(['bahanbaku', 'cabangDari', 'cabangTujuan', 'user'])
            ->findOrFail(decrypt($id));

        $bahanbakus = MBahanBakus::all();
        $cabangs = Cabang::where('id', '!=', Auth::user()->cabangs->id ?? Auth::user()->cabang_id)->get();

        return view('transaksibahanbaku.edit', compact('data', 'bahanbakus', 'cabangs'));
    }

    /**
     * 🛠️ Update transaksi bahan baku.
     */
    public function update($id, Request $request)
    {
        $validated = $request->validate([
            'bahanbaku_transaksibahanbaku' => 'required',
            'cabangtujuan_transaksibahanbaku' => 'required',
            'banyak_transaksibahanbaku' => 'required|numeric',
        ]);

        DB::beginTransaction();
        try {
            $transaksi = MTransaksiBahanBakus::findOrFail(decrypt($id));
            $bahanbakuId = decrypt($request->bahanbaku_transaksibahanbaku);
            $cabangId = Auth::user()->cabangs->id ?? Auth::user()->cabang_id;

            $bahan = MBahanBakus::findOrFail($bahanbakuId);
            $stok = MStokBahanBakus::firstOrCreate(
                ['bahanbaku_id' => $bahanbakuId, 'cabang_id' => $cabangId],
                ['satuan' => $bahan->satuan, 'stok_hitung_luas' => $bahan->hitung_luas, 'banyak_stok' => 0]
            );

            // Hitung ulang stok berdasarkan perubahan
            $stok->banyak_stok += $transaksi->banyak - $request->banyak_transaksibahanbaku;
            $stok->save();

            $transaksi->update([
                'bahanbaku_id' => $bahanbakuId,
                'cabangtujuan_id' => decrypt($request->cabangtujuan_transaksibahanbaku),
                'banyak' => $request->banyak_transaksibahanbaku,
                'satuan' => $bahan->satuan,
                'keterangan' => $request->keterangan_transaksibahanbaku,
                'user_id' => Auth::id(),
            ]);

            DB::commit();
            return redirect('/transaksi/bahan')->with('success', '✅ Transaksi bahan baku berhasil diubah!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', '❌ Gagal mengubah transaksi: ' . $th->getMessage());
        }
    }

    /**
     * 🗑️ Hapus transaksi bahan baku.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $transaksi = MTransaksiBahanBakus::findOrFail(decrypt($id));
            $cabangId = Auth::user()->cabangs->id ?? Auth::user()->cabang_id;

            $stok = MStokBahanBakus::where('bahanbaku_id', $transaksi->bahanbaku_id)
                ->where('cabang_id', $cabangId)
                ->first();

            if ($stok) {
                $stok->banyak_stok += $transaksi->banyak;
                $stok->save();
            }

            $transaksi->delete();

            DB::commit();
            return redirect('/transaksi/bahan')->with('success', '🗑️ Transaksi bahan baku berhasil dihapus!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', '❌ Gagal menghapus transaksi: ' . $th->getMessage());
        }
    }

    /**
     * ♻️ Tampilkan data yang sudah dihapus (soft deleted).
     */
    public function indexDeleted(Request $request)
    {
        $cabangs = Cabang::where('id', '!=', Auth::user()->cabangs->id ?? Auth::user()->cabang_id)->get();
        $bahanbakus = MBahanBakus::all();

        $data = MTransaksiBahanBakus::onlyTrashed()
            ->leftJoin('bahanbakus', 'transaksi_bahan_bakus.bahanbaku_id', '=', 'bahanbakus.id')
            ->leftJoin('cabangs as cabangdari', 'transaksi_bahan_bakus.cabangdari_id', '=', 'cabangdari.id')
            ->leftJoin('cabangs as cabangtujuan', 'transaksi_bahan_bakus.cabangtujuan_id', '=', 'cabangtujuan.id')
            ->leftJoin('users', 'transaksi_bahan_bakus.user_id', '=', 'users.id')
            ->select(
                'transaksi_bahan_bakus.*',
                'bahanbakus.nama_bahan',
                'cabangdari.nama as cabangdari',
                'cabangtujuan.nama as cabangtujuan',
                'users.username'
            )
            ->orderByDesc('transaksi_bahan_bakus.deleted_at')
            ->paginate(30);

        return view('transaksibahanbaku.deleted', compact('data', 'bahanbakus', 'cabangs'));
    }
}
