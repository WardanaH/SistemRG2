<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cabang;
use App\Models\MAngsurans;
use App\Models\MProduks;
use App\Models\MBahanBakus;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\StokBahanBaku;
use App\Models\MStokBahanBakus;
use App\Models\MRelasiBahanBaku;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\MTransaksiPenjualans;
use Illuminate\Support\Facades\Auth;
use App\Models\MSubTransaksiPenjualans;

class TransaksiPenjualansController extends Controller
{
    public function transaksi()
    {
        //
        $date = date("Y-m-d");
        $produks = MProduks::all();
        $designer = User::role('designer')->get();
        // dd($produks);

        return view('admin.transaksis.transaksi', [
            'date' => $date,
            'produks' => $produks,
            'designers' => $designer
        ]);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            // VALIDASI DASAR
            $request->validate([
                'inputtanggal' => 'required|date',
                'inputtotal' => 'required',
                'inputpembayaran' => 'required|string',
                'items' => 'required|array|min:1',
                'items.*.produk_id' => 'required|integer',
            ]);

            // SIMPAN TRANSAKSI
            $transaksi = new MTransaksiPenjualans();
            $transaksi->nomor_nota = $request->nonota ?? 'TRX-' . now()->timestamp;
            $transaksi->tanggal = $request->inputtanggal;
            $transaksi->nama_pelanggan = $request->inputnamapelanggan;
            $transaksi->hp_pelanggan = $request->inputnomorpelanggan;
            $transaksi->pelanggan_id = $request->inputpelanggan;
            $transaksi->total_harga = $this->parseRupiah($request->inputtotal);
            $transaksi->diskon = $request->inputdiskon ?? 0;
            $transaksi->pajak = $request->inputpajak ?? 0;
            $transaksi->metode_pembayaran = $request->inputpembayaran;
            $transaksi->jumlah_pembayaran = $this->parseRupiah($request->inputbayardp);
            $transaksi->sisa_tagihan = $this->parseRupiah($request->inputsisa);
            $transaksi->user_id = Auth::id();
            $transaksi->cabang_id = Auth::user()->cabang->id ?? null;
            $transaksi->designer_id = $request->inputdesigner;

            // dd($transaksi);

            $transaksi->save();

            // SIMPAN DETAIL ITEM
            foreach ($request->items as $item) {

                $produk = MProduks::find($item['produk_id']);     // <= WAJIB
                $hitungLuas = $produk->hitung_luas;               // <= WAJIB

                $sub = new MSubTransaksiPenjualans();
                $sub->penjualan_id = $transaksi->id;
                $sub->produk_id = $item['produk_id'];
                $sub->harga_satuan = $item['harga'] ?? 0;
                $sub->finishing = $item['finishing'] ?? 'Tanpa Finishing';
                $sub->diskon = $item['diskon'] ?? 0;
                $sub->no_spk = $item['no_spk'] ?? '-';
                $sub->keterangan = $item['keterangan'] ?? '-';
                $sub->satuan = $produk->satuan;
                $sub->user_id = Auth::id();

                // ===== LOGIKA BARU =====
                if ($hitungLuas == 1) {
                    $sub->panjang = $item['panjang'];
                    $sub->lebar   = $item['lebar'];
                    $sub->banyak  = $item['kuantitas'];
                } else {
                    $sub->panjang = 0;
                    $sub->lebar   = 0;
                    $sub->banyak  = $item['kuantitas'];
                }

                $sub->subtotal = $item['subtotal']; // 🔥 AMAN


                // dd($sub);

                $sub->save();

                // ================= UPDATE STOK BAHAN =================
                $relasiBahan = MRelasiBahanBaku::where('produk_id', $item['produk_id'])->get();

                foreach ($relasiBahan as $rel) {

                    $bahan = MBahanBakus::find($rel->bahanbaku_id);

                    $stok = MStokBahanBakus::firstOrNew([
                        'bahanbaku_id' => $rel->bahanbaku_id,
                        'cabang_id' => Auth::user()->cabangs->id,
                    ]);

                    $stok->satuan = $bahan->satuan;
                    $stok->stokhitungluas = $bahan->hitung_luas;

                    // ====== PENGURANGAN STOK BENAR ======
                    if ($hitungLuas == 1) {
                        $luas = $this->hitungLuas(
                            $item['panjang'],
                            $item['lebar'],
                            $item['kuantitas'],
                            $bahan->satuan,
                            'PCS'
                        );
                    } else {
                        $luas = $item['kuantitas']; // cuma QTY, tanpa luas
                    }

                    $stok->banyakstok = ($stok->banyakstok ?? 0) - ($luas * $rel->qtypertrx);
                    $stok->save();
                }
            }

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Transaksi berhasil disimpan!',
                'id' => encrypt($transaksi->id),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal transaksi', ['error' => $e->getMessage()]);
            return back()->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
        }
    }


    private function hitungLuas($panjang, $lebar, $qty, $satuanBahan, $satuanItem)
    {
        if (($satuanItem == "CENTIMETER") || ($satuanItem == "METER")) {
            if ($satuanBahan == "CENTIMETER" && $satuanItem == "METER") {
                return ($panjang * 100) * ($lebar * 100) * $qty;
            } elseif ($satuanBahan == $satuanItem) {
                return $panjang * $lebar * $qty;
            } elseif ($satuanBahan == "METER" && $satuanItem == "CENTIMETER") {
                return ($panjang / 100) * ($lebar / 100) * $qty;
            }
        }

        return $qty; // default jika bukan hitung luas
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        $query = MTransaksiPenjualans::with(['user', 'cabang', 'designer']);

        // 🔹 Jika bukan owner / direktur → hanya cabangnya sendiri
        if (!$user->hasRole(['owner', 'direktur'])) {
            $query->where('cabang_id', $user->cabang_id);
        }

        // 🔹 Filter jika user memilih manual dari dropdown
        if ($request->cabang && $request->cabang !== 'semua') {
            $query->where('cabang_id', $request->cabang);
        }

        // 🔹 Filter lain
        $query->when(
            $request->no,
            fn($q) =>
            $q->where('nomor_nota', 'like', "%{$request->no}%")
        );
        $query->when(
            $request->tanggal,
            fn($q) =>
            $q->whereDate('tanggal', $request->tanggal)
        );

        $datas = $query->orderBy('created_at', 'desc')->paginate(10);

        $cabangs = Cabang::all();

        return view('admin.transaksis.list', compact('datas', 'cabangs'));
    }


    public function indexdeleted(Request $request)
    {
        $user = Auth::user();

        $query = MTransaksiPenjualans::onlyTrashed(['user', 'cabang', 'designer']);

        // 🔹 Jika bukan owner / direktur → hanya cabangnya sendiri
        if (!$user->hasRole(['owner', 'direktur'])) {
            $query->where('cabang_id', $user->cabang_id);
        }

        // 🔹 Filter jika user memilih manual dari dropdown
        if ($request->cabang && $request->cabang !== 'semua') {
            $query->where('cabang_id', $request->cabang);
        }

        // 🔹 Filter lain
        $query->when(
            $request->no,
            fn($q) =>
            $q->where('nomor_nota', 'like', "%{$request->no}%")
        );
        $query->when(
            $request->tanggal,
            fn($q) =>
            $q->whereDate('tanggal', $request->tanggal)
        );

        $datas = $query->orderBy('created_at', 'desc')->paginate(10);

        $cabangs = Cabang::all();

        return view('admin.transaksis.listdeleted', compact('datas', 'cabangs'));
    }

    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $transaksi = MTransaksiPenjualans::findOrFail($id);

            // Simpan alasan penghapusan
            $transaksi->reason_on_delete = $request->reason_on_delete ?? 'Tanpa alasan';
            $transaksi->save();

            // Soft delete semua angsuran yang berhubungan
            foreach ($transaksi->angsuran as $a) {
                $a->delete();
            }

            // Soft delete semua sub transaksi-nya
            foreach ($transaksi->subTransaksi as $sub) {
                $sub->delete();
            }

            // Soft delete transaksi utama
            $transaksi->delete();

            DB::commit();

            return redirect()->route('transaksiindex')
                ->with('success', 'Transaksi & angsuran berhasil dihapus. Alasan: ' . $transaksi->reason_on_delete);
        } catch (\Exception $e) {

            DB::rollBack();
            Log::error('Gagal menghapus transaksi: ' . $e->getMessage());

            return redirect()->route('transaksiindex')
                ->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }

    private function parseRupiah($value)
    {
        if (!$value) return 0;

        // Hapus "Rp", spasi, dan titik ribuan
        $value = str_replace(['Rp', ' ', '.'], '', $value);

        // Ganti koma dengan titik untuk desimal
        $value = str_replace(',', '.', $value);

        // Pastikan jadi float dengan 2 desimal
        return round((float)$value, 2);
    }

    public function showSubTransaksi(Request $request)
    {
        try {
            // jika id terenkripsi di front-end, decrypt dulu
            $id = $request->has('id') ? (is_string($request->id) && Str::startsWith($request->id, 'ey') ? decrypt($request->id) : $request->id) : null;
            // atau kalau kamu selalu mengirim plain id: $id = $request->id;

            $current = \App\Models\MSubTransaksiPenjualans::where('penjualan_id', $id)
                ->with(['produk:id,nama_produk', 'user:id,username', 'cabang:id,nama'])
                ->get();

            $deleted = \App\Models\MSubTransaksiPenjualans::onlyTrashed()
                ->where('penjualan_id', $id)
                ->with(['produk:id,nama_produk', 'user:id,username', 'cabang:id,nama'])
                ->get();

            return response()->json([
                'current' => $current,
                'deleted' => $deleted,
            ]);
        } catch (\Exception $e) {
            Log::error('showSubTransaksi error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function report($id)
    {
        $id = decrypt($id);

        $transaksi = MTransaksiPenjualans::with([
            'user',
            'cabang',
            'pelanggan',
            'designer',
        ])->withTrashed()->findOrFail($id);

        $subtransaksis = $transaksi->subTransaksi()->with('produk')->get();

        $angsurans = MAngsurans::where('transaksi_penjualan_id', '=', $id)->get();

        return view('admin.reports.reportpenjualan', [
            'transaksi' => $transaksi,
            'subtransaksis' => $subtransaksis,
            'angsurans' => $angsurans
        ]);
    }
}
