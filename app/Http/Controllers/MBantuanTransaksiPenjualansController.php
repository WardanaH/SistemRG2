<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\MProduks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\MBantuanTransaksiPenjualans;
use App\Models\MSubBantuanTransaksiPenjualans;

class MBantuanTransaksiPenjualansController extends Controller
{
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
    
    // 1. Menampilkan permintaan bantuan yang MASUK (Dilihat oleh Admin Cabang B)
    public function indexPermintaanMasuk()
    {
        $user = Auth::user();

        // Hanya mengambil data dimana cabang bantuan adalah cabang user yang login
        // dan status persetujuannya masih pending
        $permintaan = MBantuanTransaksiPenjualans::with(['cabangAsal', 'adminPembuat'])
            ->where('bantuan_cabang_id', $user->cabang_id)
            ->where('status_persetujuan_bantuan_transaksi', 'pending')
            ->get();

        return view('admin_b.permintaan_bantuan.index', compact('permintaan'));
    }

    // 2. Fungsi untuk Admin Cabang B melakukan ACC atau TOLAK
    public function konfirmasiBantuan(Request $request, $id)
    {
        $request->validate([
            'tindakan' => 'required|in:acc,tolak'
        ]);

        $transaksi = MBantuanTransaksiPenjualans::findOrFail($id);

        $transaksi->update([
            'status_persetujuan_bantuan_transaksi' => $request->tindakan
        ]);

        $pesan = $request->tindakan == 'acc' ? 'Bantuan diterima, data diteruskan ke Operator.' : 'Bantuan ditolak.';

        return back()->with('success', $pesan);
    }

    public function indexProduksiOperator()
    {
        $user = Auth::user();

        // Mengambil sub-transaksi yang ditujukan ke cabang user login
        // DAN sudah di-ACC oleh Admin Cabang B
        $daftarKerja = MSubBantuanTransaksiPenjualans::whereHas('transaksiUtama', function ($query) use ($user) {
            $query->where('bantuan_cabang_id', $user->cabang_id)
                ->where('status_persetujuan_bantuan_transaksi', 'acc');
        })
            ->whereIn('status_sub_transaksi', ['proses']) // Hanya yang masih proses
            ->with(['transaksiUtama', 'produk'])
            ->get();

        return view('operator_b.produksi.index', compact('daftarKerja'));
    }

    public function updateStatusProduksi(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:proses,selesai,cancel'
        ]);

        $subTransaksi = MSubBantuanTransaksiPenjualans::findOrFail($id);
        $subTransaksi->update([
            'status_sub_transaksi' => $request->status
        ]);

        return back()->with('success', 'Status produksi berhasil diperbarui.');
    }

    public function storeBantuan(Request $request)
    {
        DB::beginTransaction();
        try {
            // 1. VALIDASI DASAR
            $request->validate([
                'inputtanggal' => 'required|date',
                'inputtotal' => 'required',
                'inputpembayaran' => 'required|string',
                'inputcabangbantuan' => 'required|integer', // ID Cabang B
                'items' => 'required|array|min:1',
                'items.*.produk_id' => 'required|integer',
            ]);

            // 2. SIMPAN TRANSAKSI BANTUAN (Header)
            $transaksi = new MBantuanTransaksiPenjualans();
            // Nomor nota dibedakan (misal: BTX) agar tidak tertukar dengan transaksi internal
            $transaksi->nomor_nota = $request->nonota ?? 'BTX-' . now()->timestamp;
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

            $transaksi->user_id = Auth::id(); // Admin Cabang A
            $transaksi->cabang_id = Auth::user()->cabang->id; // Cabang Asal A
            $transaksi->bantuan_cabang_id = $request->inputcabangbantuan; // Cabang Tujuan B
            $transaksi->designer_id = $request->inputdesigner;

            // Status awal bantuan adalah pending menunggu ACC Admin Cabang B
            $transaksi->status_persetujuan_bantuan_transaksi = 'pending';

            $transaksi->save();

            // 3. SIMPAN DETAIL ITEM (Sub Bantuan)
            foreach ($request->items as $item) {
                $produk = MProduks::find($item['produk_id']);
                $hitungLuas = $produk->hitung_luas;

                $sub = new MSubBantuanTransaksiPenjualans();
                $sub->bantuan_penjualan_id = $transaksi->id; // Foreign Key ke tabel bantuan
                $sub->produk_id = $item['produk_id'];
                $sub->harga_satuan = $item['harga'] ?? 0;
                $sub->finishing = $item['finishing'] ?? 'Tanpa Finishing';
                $sub->diskon = $item['diskon'] ?? 0;
                $sub->no_spk = null; // SPK dikosongkan dulu, biar Admin B yang isi pas ACC
                $sub->keterangan = $item['keterangan'] ?? '-';
                $sub->satuan = $produk->satuan;
                $sub->user_id = Auth::id();

                if ($hitungLuas == 1) {
                    $sub->panjang = $item['panjang'];
                    $sub->lebar   = $item['lebar'];
                    $sub->banyak  = $item['kuantitas'];
                } else {
                    $sub->panjang = 0;
                    $sub->lebar   = 0;
                    $sub->banyak  = $item['kuantitas'];
                }

                $sub->subtotal = $item['subtotal'];
                $sub->status_sub_transaksi = 'proses';

                $sub->save();
            }

            // 4. LOGGING
            $cabangTujuan = Cabang::find($request->inputcabangbantuan)->nama;
            $isi = Auth::user()->username . " meminta bantuan produksi ke cabang " . $cabangTujuan . " dengan nota " . $transaksi->nomor_nota;
            $this->log($isi, "Permintaan Bantuan");

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Permintaan bantuan berhasil dikirim! Menunggu konfirmasi Cabang Tujuan.',
                'id' => encrypt($transaksi->id),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal Simpan Bantuan Transaksi: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal: ' . $e->getMessage()
            ], 500);
        }
    }
}
