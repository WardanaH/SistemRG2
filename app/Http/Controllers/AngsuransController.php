<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\MAngsurans;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;
use App\Models\MTransaksiPenjualans;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class AngsuransController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = MTransaksiPenjualans::with(['pelanggan', 'cabang', 'user'])
            ->where('sisa_tagihan', '>', 0);

        // 🔹 Jika bukan owner/direktur → batasi cabang otomatis
        if (!$user->hasRole(['owner', 'direktur'])) {
            $query->where('cabang_id', $user->cabang_id);
        }

        // 🔹 Filter manual berdasarkan cabang (untuk owner / direktur)
        if ($request->cabang && $request->cabang !== 'semua') {
            $query->where('cabang_id', $request->cabang);
        }

        // 🔹 Filter No Nota
        if ($request->no) {
            $query->where('nomor_nota', 'like', "%{$request->no}%");
        }

        // 🔹 Filter Tanggal
        if ($request->tanggal) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        // 🔹 Filter Pembayaran
        if ($request->pembayaran && $request->pembayaran !== 'semua') {
            $query->where('metode_pembayaran', $request->pembayaran);
        }

        $datas = $query->orderBy('created_at', 'desc')->paginate(10);
        $cabangs = Cabang::all();

        return view('admin.transaksis.angsuran.index', compact('datas', 'cabangs'));
    }

    public function indexDeleted(Request $request)
    {
        $user = Auth::user();

        $query = MAngsurans::onlyTrashed()
            ->with([
                'transaksiPenjualan.pelanggan',
                'transaksiPenjualan.user',
                'transaksiPenjualan.cabang'
            ]);

        if (!$user->hasRole(['owner', 'direktur'])) {
            $query->whereHas('transaksiPenjualan', function ($q) use ($user) {
                $q->where('cabang_id', $user->cabang_id);
            });
        }

        if ($request->cabang && $request->cabang !== 'semua') {
            $query->whereHas('transaksiPenjualan', function ($q) use ($request) {
                $q->where('cabang_id', $request->cabang);
            });
        }

        if ($request->no) {
            $query->whereHas('transaksiPenjualan', function ($q) use ($request) {
                $q->where('nomor_nota', 'like', "%{$request->no}%");
            });
        }

        if ($request->nama) {
            $query->whereHas('transaksiPenjualan', function ($q) use ($request) {
                $q->where('nama_pelanggan', 'like', "%{$request->nama}%");
            });
        }

        if ($request->pembayaran && $request->pembayaran !== 'semua') {
            $query->whereHas('transaksiPenjualan', function ($q) use ($request) {
                $q->where('metode_pembayaran', $request->pembayaran);
            });
        }

        $datas = $query
            ->orderBy('deleted_at', 'desc')
            ->paginate(50)
            ->withQueryString();

        $cabangs = Cabang::all();

        return view('admin.transaksis.angsuran.indexdeleted', compact('datas', 'cabangs'));
    }

    public function data(Request $request)
    {
        $query = MTransaksiPenjualans::with(['pelanggan', 'cabang', 'user'])
            ->latest();
        // ->where('sisa_tagihan', '>', 0);

        // 🔹 Batasi cabang jika bukan owner/direktur
        if (!Auth::user()->hasRole(['owner', 'direktur'])) {
            $query->where('cabang_id', Auth::user()->cabang_id);
        }

        // 🔹 Filter cabang (owner/direktur)
        if ($request->cabang && $request->cabang !== 'semua') {
            $query->where('cabang_id', $request->cabang);
        }

        if ($request->nonota) {
            $query->where('nomor_nota', 'like', "%{$request->nonota}%");
        }

        if ($request->nama) {
            $query->where('nama_pelanggan', 'like', "%{$request->nama}%");
        }

        if ($request->pembayaran && $request->pembayaran !== 'semua') {
            $query->where('metode_pembayaran', $request->pembayaran);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('aksi', function ($t) {
                return '
                    <button class="btn btn-info btn-sm btn-detail"
                        data-id="' . $t->id . '">
                        Detail
                    </button>
                    <button class="btn btn-success btn-sm bayarBtn"
                        data-id="' . $t->id . '"
                        data-sisa="' . $t->sisa_tagihan . '">
                        Bayar
                    </button>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    public function dataDeleted(Request $request)
    {
        $user = Auth::user();

        $query = MAngsurans::onlyTrashed()
            ->with([
                'transaksiPenjualan.pelanggan',
                'transaksiPenjualan.cabang',
                'transaksiPenjualan.user'
            ]);

        // 🔒 Batasi cabang jika bukan owner/direktur
        if (!$user->hasRole(['owner', 'direktur'])) {
            $query->whereHas('transaksiPenjualan', function ($q) use ($user) {
                $q->where('cabang_id', $user->cabang_id);
            });
        }

        // 🔎 Filter No Nota
        if ($request->nonota) {
            $query->whereHas('transaksiPenjualan', function ($q) use ($request) {
                $q->where('nomor_nota', 'like', "%{$request->nonota}%");
            });
        }

        // 🔎 Filter Nama Pelanggan
        if ($request->nama) {
            $query->whereHas('transaksiPenjualan', function ($q) use ($request) {
                $q->where('nama_pelanggan', 'like', "%{$request->nama}%");
            });
        }

        // 🔎 Filter Pembayaran
        if ($request->pembayaran && $request->pembayaran !== 'semua') {
            $query->whereHas('transaksiPenjualan', function ($q) use ($request) {
                $q->where('metode_pembayaran', $request->pembayaran);
            });
        }

        // 🔎 Filter Cabang (owner/direktur)
        if ($request->cabang && $request->cabang !== 'semua') {
            $query->whereHas('transaksiPenjualan', function ($q) use ($request) {
                $q->where('cabang_id', $request->cabang);
            });
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn(
                'nomor_nota',
                fn($a) =>
                $a->transaksiPenjualan->nomor_nota ?? '-'
            )
            ->addColumn(
                'nama_pelanggan',
                fn($a) =>
                $a->transaksiPenjualan->nama_pelanggan ?? '-'
            )
            ->addColumn(
                'metode_pembayaran',
                fn($a) =>
                $a->transaksiPenjualan->metode_pembayaran ?? '-'
            )
            ->addColumn(
                'cabang',
                fn($a) =>
                $a->transaksiPenjualan->cabang->nama ?? '-'
            )
            ->addColumn(
                'dibuat_oleh',
                fn($a) =>
                $a->transaksiPenjualan->user->username ?? '-'
            )
            ->editColumn(
                'deleted_at',
                fn($a) =>
                $a->deleted_at->format('d-m-Y H:i')
            )
            ->make(true);
    }

    public function detail($id)
    {
        $data = MTransaksiPenjualans::with([
            'subTransaksi.produk',
            'user:id,username',
            'cabang:id,nama'
        ])->findOrFail($id);
        // dd($data);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function showDetailAngsuran(Request $request)
    {
        try {

            Log::info("[DETAIL] Request masuk ke showDetailAngsuran", [
                'req_id' => $request->id
            ]);

            $id = $request->id;

            if (!$id) {
                Log::warning("[DETAIL] ID kosong dalam request!");
                return response()->json(['error' => 'ID tidak ditemukan'], 400);
            }

            Log::info("[DETAIL] Mencari transaksi penjualan dengan ID", ['id' => $id]);

            // Cari transaksi
            $transaksi = MTransaksiPenjualans::with([
                'subTransaksi.produk:id,nama_produk',
                'user:id,username',
                'cabang:id,nama'
            ])->find($id);

            if (!$transaksi) {
                Log::warning("[DETAIL] Transaksi tidak ditemukan", [
                    'id' => $id
                ]);

                return response()->json(['error' => 'Transaksi tidak ditemukan'], 404);
            }

            Log::info("[DETAIL] Transaksi ditemukan", [
                'id' => $transaksi->id,
                'nomor_nota' => $transaksi->nomor_nota ?? null,
                'sisa_tagihan' => $transaksi->sisa_tagihan
            ]);

            // Riwayat angsuran
            $angsuran = MAngsurans::with('user:id,username')
                ->where('transaksi_penjualan_id', $id)
                ->orderBy('created_at')
                ->get();

            Log::info("[DETAIL] Jumlah riwayat angsuran", [
                'count' => $angsuran->count()
            ]);

            return response()->json([
                'success' => true,
                'detail' => $transaksi,
                'angsuran' => $angsuran
            ]);
        } catch (\Exception $e) {

            Log::error("[DETAIL] ERROR showDetailAngsuran", [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
                'trace'   => $e->getTraceAsString()
            ]);

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function showDetailAngsuranTransaksi(Request $request)
    {
        try {

            Log::info("[DETAIL] Request masuk ke showDetailAngsuran", [
                'req_id' => $request->id
            ]);

            // decrypt ID
            $id = Crypt::decrypt($request->id);

            Log::info("[DETAIL] ID setelah decrypt", ['id' => $id]);

            // cari transaksi
            $transaksi = MTransaksiPenjualans::with([
                'subTransaksi.produk:id,nama_produk',
                'user:id,username',
                'cabang:id,nama'
            ])->find($id);

            if (!$transaksi) {
                Log::warning("[DETAIL] Transaksi tidak ditemukan", ['id' => $id]);
                return response()->json(['error' => 'Transaksi tidak ditemukan'], 404);
            }

            // ambil list angsuran
            $angsuran = MAngsurans::where('transaksi_penjualan_id', $id)
                ->orderBy('created_at')
                ->get();

            return response()->json([
                'success' => true,
                'detail' => $transaksi,
                'angsuran' => $angsuran
            ]);
        } catch (\Exception $e) {

            Log::error("[DETAIL] ERROR showDetailAngsuran", [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile()
            ]);

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function bayar(Request $request, $id)
    {
        $request->validate([
            'nominal' => 'required|numeric|min:1',
            'metode' => 'required|string',
            'nomor_nota' => 'required|string'
        ]);

        // Ambil transaksi by ID
        $transaksi = MTransaksiPenjualans::findOrFail($id);

        $nominal = (float)$request->nominal;
        $date = date('Y-m-d');

        // Hitung sisa & total pembayaran baru
        $sisa = $transaksi->sisa_tagihan - $nominal;
        $totalPembayaran = $transaksi->jumlah_pembayaran + $nominal;

        // Buat record angsuran baru
        $angsuran = MAngsurans::create([
            'tanggal_angsuran' => $date,
            'nominal_angsuran' => $nominal,
            'sisa_angsuran' => $sisa,
            'nomor_nota' => $request->nomor_nota,
            'metode_pembayaran' => $request->metode,
            'user_id' => auth()->id(),
            'cabang_id' => auth()->user()->cabang_id ?? auth()->user()->cabangs->id,
            'transaksi_penjualan_id' => $transaksi->id,
        ]);

        // Update transaksi
        $transaksi->update([
            'sisa_tagihan' => $sisa,
            'jumlah_pembayaran' => $totalPembayaran,
        ]);

        // (Optional) Buat log seperti sistem lama
        // $this->createlog(...)

        return response()->json(['msg' => 'success']);
    }

    public function hapus(Request $request, $id)
    {
        $request->validate([
            'alasan' => 'required'
        ]);

        $angsuran = MAngsurans::findOrFail($id);

        // Simpan alasan penghapusan
        $angsuran->reason_on_delete = $request->alasan;
        $angsuran->save();

        $transaksi = MTransaksiPenjualans::findOrFail($angsuran->transaksi_penjualan_id);

        // Kembalikan sisa tagihan
        $transaksi->sisa_tagihan += $angsuran->nominal_angsuran;
        $transaksi->save();

        // Hapus angsuran
        $angsuran->delete();

        return response()->json(['success' => true]);
    }
}
