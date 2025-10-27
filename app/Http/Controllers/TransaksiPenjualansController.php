<?php

namespace App\Http\Controllers;

use App\Models\MBahanBakus;
use Illuminate\Http\Request;
use App\Models\StokBahanBaku;
use App\Models\MStokBahanBakus;
use App\Models\MRelasiBahanBaku;
use Illuminate\Support\Facades\DB;
use App\Models\MTransaksiPenjualans;
use Illuminate\Support\Facades\Auth;
use App\Models\MSubTransaksiPenjualans;

class TransaksiPenjualanController extends Controller
{
    public function index()
    {
        return view('transaksi.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'inputnomorpelanggan' => 'nullable|string|max:13',
            'inputnamapelanggan' => 'nullable|string|max:100',
            'inputpelanggan' => 'nullable',
            'inputtanggal' => 'required|date',
            'inputtotal' => 'required|numeric|min:0',
            'inputdiskon' => 'nullable|numeric|min:0',
            'inputpembayaran' => 'required|string',
            'inputbayardp' => 'nullable|numeric|min:0',
            'inputpajak' => 'nullable|numeric|min:0',
            'jsonprodukid' => 'required|array',
        ]);

        try {
            DB::beginTransaction();

            // 🔹 Simpan transaksi utama
            $transaksi = MTransaksiPenjualans::create([
                'nomor_nota' => 'TRX-' . now()->format('YmdHis'),
                'hp_pelanggan' => $request->input('inputnomorpelanggan'),
                'nama_pelanggan' => $request->input('inputnamapelanggan'),
                'pelanggan_id' => $request->input('inputpelanggan'),
                'tanggal' => $request->input('inputtanggal'),
                'total_harga' => $request->input('inputtotal'),
                'diskon' => $request->input('inputdiskon', 0),
                'metode_pembayaran' => $request->input('inputpembayaran'),
                'jumlah_pembayaran' => $request->input('inputbayardp', 0),
                'pajak' => $request->input('inputpajak', 0),
                'user_id' => Auth::id(),
                'cabang_id' => Auth::user()->cabang_id,
                'sisa_tagihan' => $request->input('inputtotal') - $request->input('inputbayardp', 0),
            ]);

            // 🔹 Simpan detail produk (sub transaksi)
            $produks = $request->input('jsonprodukid');

            foreach ($produks as $index => $produk) {
                MSubTransaksiPenjualans::create([
                    'penjualan_id' => $transaksi->id,
                    'produk_id' => $produk['value'],
                    'harga_satuan' => $request->json("jsonharga.$index.value"),
                    'panjang' => $request->json("jsonpanjang.$index.value"),
                    'lebar' => $request->json("jsonlebar.$index.value"),
                    'banyak' => $request->json("jsonkuantitas.$index.value"),
                    'keterangan' => $request->json("jsonketerangan.$index.value"),
                    'subtotal' => $request->json("jsonsubtotal.$index.value"),
                    'finishing' => $request->json("jsonfinishing.$index.value"),
                    'satuan' => $request->json("jsonsatuan.$index.value"),
                    'diskon' => $request->json("jsondiskon.$index.value"),
                    'user_id' => Auth::id(),
                ]);

                // 🔹 Kurangi stok bahan baku sesuai relasi produk
                $relasi = MRelasiBahanBaku::where('produk_id', $produk['value'])->get();
                foreach ($relasi as $r) {
                    $stok = MStokBahanBakus::firstOrNew([
                        'bahanbaku_id' => $r->bahanbaku_id,
                        'cabang_id' => Auth::user()->cabang_id,
                    ]);

                    $bahan = MBahanBakus::find($r->bahanbaku_id);
                    $kuantitas = $request->json("jsonkuantitas.$index.value");
                    $panjang = $request->json("jsonpanjang.$index.value");
                    $lebar = $request->json("jsonlebar.$index.value");
                    $satuan = $request->json("jsonsatuan.$index.value");

                    // Hitung luas bahan
                    $luas = ($satuan === "METER")
                        ? ($panjang * $lebar * $kuantitas)
                        : (($panjang / 100) * ($lebar / 100) * $kuantitas);

                    $stok->banyakstok = ($stok->banyakstok ?? 0) - ($luas * $r->qtypertrx);
                    $stok->save();
                }
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Transaksi berhasil disimpan',
                'id' => encrypt($transaksi->id),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
