<?php

namespace App\Http\Controllers;

use App\Models\MProduks;
use App\Models\MBahanBakus;
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
        // dd($produks);

        return view('admin.transaksis.transaksi', ['date' => $date, 'produks' => $produks]);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // ================== VALIDASI DASAR ==================
            $request->validate([
                'inputtanggal' => 'required|date',
                'inputtotal' => 'required',
                'inputpembayaran' => 'required|string',
                'items' => 'required|array|min:1',
                'items.*.produk_id' => 'required|integer',
            ]);
            // dd($request->all());

            // ================== SIMPAN DATA TRANSAKSI ==================
            $transaksi = new MTransaksiPenjualans();
            $transaksi->nomor_nota = $request->nonota ?? 'TRX-' . now()->timestamp;
            $transaksi->tanggal = $request->inputtanggal;
            $transaksi->nama_pelanggan = $request->inputnamapelanggan;
            $transaksi->hp_pelanggan = $request->inputnomorpelanggan;
            $transaksi->pelanggan_id = $request->inputpelanggan;
            $transaksi->total_harga = preg_replace('/[^0-9]/', '', $request->inputtotal);
            $transaksi->diskon = $request->inputdiskon ?? 0;
            $transaksi->pajak = $request->inputpajak ?? 0;
            $transaksi->metode_pembayaran = $request->inputpembayaran;
            $transaksi->jumlah_pembayaran = preg_replace('/[^0-9]/', '', $request->inputbayardp);
            $transaksi->sisa_tagihan = preg_replace('/[^0-9]/', '', $request->inputsisa);
            $transaksi->user_id = Auth::id();
            $transaksi->cabang_id = Auth::user()->cabang->id ?? null;
            $transaksi->save();
            // dd($transaksi);

            // ================== SIMPAN DETAIL ITEM ==================
            foreach ($request->items as $item) {
                $sub = new MSubTransaksiPenjualans();
                $sub->penjualan_id = $transaksi->id;
                $sub->produk_id = $item['produk_id'];
                $sub->harga_satuan = $item['harga'] ?? 0;
                $sub->panjang = $item['panjang'] ?? 0;
                $sub->lebar = $item['lebar'] ?? 0;
                $sub->banyak = $item['kuantitas'] ?? 1;
                $sub->finishing = $item['finishing'] ?? 'Tanpa Finishing';
                $sub->diskon = $item['diskon'] ?? 0;
                $sub->subtotal = $item['subtotal'] ?? 0;
                $sub->keterangan = $item['keterangan'] ?? '-';
                $sub->satuan = 'PCS'; // default, bisa diubah sesuai kebutuhan
                $sub->user_id = Auth::id();
                $sub->save();

                // ================== UPDATE STOK BAHAN BAKU ==================
                $relasiBahan = MRelasiBahanBaku::where('produk_id', $item['produk_id'])->get();

                foreach ($relasiBahan as $rel) {
                    $stok = MStokBahanBakus::firstOrNew([
                        'bahanbaku_id' => $rel->bahanbaku_id,
                        'cabang_id' => Auth::user()->cabangs->id,
                    ]);

                    $bahan = MBahanBakus::find($rel->bahanbaku_id);
                    $stok->satuan = $bahan->satuan;
                    $stok->stokhitungluas = $bahan->hitung_luas;

                    // hitung pengurangan stok
                    $luas = $this->hitungLuas(
                        $item['panjang'],
                        $item['lebar'],
                        $item['kuantitas'],
                        $bahan->satuan ?? 'PCS',
                        'PCS' // bisa ubah sesuai satuan item
                    );

                    $stok->banyakstok = ($stok->banyakstok ?? 0) - ($luas * $rel->qtypertrx);
                    $stok->save();
                }
            }

            // ================== LOG AKTIVITAS ==================
            // $this->createlog(
            //     Auth::user()->username . " menambah transaksi penjualan #{$transaksi->no_nota} di cabang " . Auth::user()->cabangs->Nama_Cabang,
            //     "add"
            // );

            Log::info('Sebelum commit', ['transaksi_id' => $transaksi->id]);
            DB::commit();
            return redirect()->route('transaksipenjualan')
                ->with('success', 'Transaksi berhasil disimpan!');
        } catch (\Exception $e) {
            Log::error('Gagal transaksi', ['error' => $e->getMessage()]);
            DB::rollBack();
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
}
