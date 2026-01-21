<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\MBahanBakus;
use App\Models\MProduks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\MBantuanTransaksiPenjualans;
use App\Models\MRelasiBahanBaku;
use App\Models\MStokBahanBakus;
use App\Models\MSubBantuanTransaksiPenjualans;
use App\Models\MSubTransaksiPenjualans;
use App\Models\MTransaksiPenjualans;
use App\Models\User;

use function Illuminate\Log\log;

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

    // Tambahkan di BantuanTransaksiController

    public function index(Request $request)
    {
        $user = Auth::user();

        // Query ke tabel Bantuan
        $query = MBantuanTransaksiPenjualans::with([
            'user',
            'cabangAsal',
            'cabangBantuan',
            'designer',
            'subBantuan'
        ]);

        // 🔹 LOGIKA AKSES:
        // Jika role owner/direktur -> Bisa lihat semua
        // Jika admin cabang -> Bisa lihat bantuan KELUAR (dari dia) atau MASUK (untuk dia)
        if (!$user->hasRole(['owner', 'direktur'])) {
            $query->where(function ($q) use ($user) {
                $q->where('cabang_id', $user->cabang_id)        // Bantuan yang saya kirim
                    ->orWhere('bantuan_cabang_id', $user->cabang_id); // Bantuan yang saya terima
            });
        }

        // 🔹 Filter Cabang Tujuan (Bantuan Ke Mana?)
        if ($request->cabang && $request->cabang !== 'semua') {
            $query->where('bantuan_cabang_id', $request->cabang);
        }

        // 🔹 Filter No Nota
        $query->when($request->no, fn($q) => $q->where('nomor_nota', 'like', "%{$request->no}%"));

        // 🔹 Filter Tanggal
        $query->when($request->tanggal, fn($q) => $q->whereDate('tanggal', $request->tanggal));

        $datas = $query->orderBy('created_at', 'desc')->paginate(10);
        $cabangs = Cabang::all();

        return view('admin.bantuan.list', compact('datas', 'cabangs'));
    }

    /**
     * Method AJAX untuk Detail Bantuan (Sama seperti showsubtransaksi tapi beda tabel)
     */
    public function showDetailBantuan(Request $request)
    {
        $id = decrypt($request->id);

        // Ambil sub bantuan yang aktif
        $current = MSubBantuanTransaksiPenjualans::with('produk')
            ->where('bantuan_penjualan_id', $id)
            ->get();

        // Ambil sub bantuan yang dihapus (jika ada soft delete)
        $deleted = MSubBantuanTransaksiPenjualans::onlyTrashed()
            ->with('produk')
            ->where('bantuan_penjualan_id', $id)
            ->get();

        return response()->json([
            'current' => $current,
            'deleted' => $deleted
        ]);
    }

    // 1. Menampilkan permintaan bantuan yang MASUK (Dilihat oleh Admin Cabang B)
    public function indexPermintaanMasuk()
    {
        $user = Auth::user();

        // Mengambil data yang ditujukan ke cabang user login dan statusnya masih pending
        $permintaan = MBantuanTransaksiPenjualans::with(['cabangAsal', 'subBantuan.produk'])
            ->where('bantuan_cabang_id', $user->cabang_id)
            ->where('status_persetujuan_bantuan_transaksi', 'pending')
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('admin.bantuan.index', compact('permintaan'));
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

    public function create()
    {
        $data = [
            'date' => date('Y-m-d'),
            // Ambil semua cabang KECUALI cabang sendiri
            'cabangs' => Cabang::where('id', '!=', Auth::user()->cabang_id)->get(),
            'produks' => MProduks::all(),
            'designers' => User::role('designer')->get(), // Sesuaikan dengan role designer di app mu
        ];

        return view('admin.bantuan.bantuan_transaksi', $data);
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

            // =================================================================================
            // PROSES 1: SIMPAN KE TRANSAKSI PENJUALAN (Laporan Keuangan Cabang A)
            // =================================================================================
            $transaksiJual = new MTransaksiPenjualans(); // Pastikan Model ini di-use di atas
            // Nota TRX untuk Customer
            $transaksiJual->nomor_nota = $request->nonota ?? 'TRX-' . now()->timestamp;
            $transaksiJual->tanggal = $request->inputtanggal;
            $transaksiJual->nama_pelanggan = $request->inputnamapelanggan;
            $transaksiJual->hp_pelanggan = $request->inputnomorpelanggan;
            $transaksiJual->pelanggan_id = $request->inputpelanggan;

            $transaksiJual->total_harga = $this->parseRupiah($request->inputtotal);
            $transaksiJual->diskon = $request->inputdiskon ?? 0;
            $transaksiJual->pajak = $request->inputpajak ?? 0;
            $transaksiJual->metode_pembayaran = $request->inputpembayaran;
            $transaksiJual->jumlah_pembayaran = $this->parseRupiah($request->inputbayardp);
            $transaksiJual->sisa_tagihan = $this->parseRupiah($request->inputsisa);

            $transaksiJual->user_id = Auth::id();
            $transaksiJual->cabang_id = Auth::user()->cabang->id; // Cabang A
            $transaksiJual->designer_id = $request->inputdesigner;

            // Status di set 'proses', tapi karena ini bantuan,
            // secara teknis barangnya belum ada di Cabang A.
            $transaksiJual->status_transaksi = 'proses';

            $transaksiJual->save();


            // =================================================================================
            // PROSES 2: SIMPAN KE BANTUAN TRANSAKSI (Request Produksi ke Cabang B)
            // =================================================================================
            $transaksiBantuan = new MBantuanTransaksiPenjualans();
            // Nota BTX untuk Internal antar cabang (Bisa disamakan atau dibedakan)
            $transaksiBantuan->nomor_nota = 'BTX-' . now()->timestamp;
            $transaksiBantuan->nomor_nota_transaksi = $request->nonota ?? 'TRX-' . now()->timestamp;
            $transaksiBantuan->tanggal = $request->inputtanggal;
            $transaksiBantuan->nama_pelanggan = $request->inputnamapelanggan;
            $transaksiBantuan->hp_pelanggan = $request->inputnomorpelanggan;
            $transaksiBantuan->pelanggan_id = $request->inputpelanggan;

            $transaksiBantuan->total_harga = $this->parseRupiah($request->inputtotal);
            $transaksiBantuan->diskon = $request->inputdiskon ?? 0;
            $transaksiBantuan->pajak = $request->inputpajak ?? 0;
            $transaksiBantuan->metode_pembayaran = $request->inputpembayaran;
            $transaksiBantuan->jumlah_pembayaran = $this->parseRupiah($request->inputbayardp);
            $transaksiBantuan->sisa_tagihan = $this->parseRupiah($request->inputsisa);

            $transaksiBantuan->user_id = Auth::id(); // Admin Cabang A
            $transaksiBantuan->cabang_id = Auth::user()->cabang->id; // Cabang Asal A
            $transaksiBantuan->bantuan_cabang_id = $request->inputcabangbantuan; // Cabang Tujuan B
            $transaksiBantuan->designer_id = $request->inputdesigner;

            // Status Flow Bantuan
            $transaksiBantuan->status_transaksi = 'proses';
            $transaksiBantuan->status_bantuan_transaksi = 'proses';
            $transaksiBantuan->status_persetujuan_bantuan_transaksi = 'pending';

            $transaksiBantuan->save();

            // ===============================
            // NOTIFIKASI PERMINTAAN BANTUAN
            // ===============================
            $cabangAsal   = Auth::user()->cabang->nama;
            $cabangTujuan = Cabang::find($request->inputcabangbantuan)->nama;

            $this->createNotification(
                'spk_bantuan',
                'Permintaan Bantuan SPK',
                "Cabang {$cabangAsal} meminta bantuan produksi ke cabang {$cabangTujuan}",
                'inventory_pusat',
                $transaksiBantuan->id
            );

            // =================================================================================
            // PROSES 3: SIMPAN DETAIL ITEM (LOOPING UNTUK KEDUA TABEL)
            // =================================================================================
            foreach ($request->items as $item) {
                $produk = MProduks::find($item['produk_id']);
                $hitungLuas = $produk->hitung_luas;

                // --- A. Simpan ke Sub Transaksi Penjualan (Untuk Laporan A) ---
                $subJual = new MSubTransaksiPenjualans();
                $subJual->penjualan_id = $transaksiJual->id;
                $subJual->produk_id = $item['produk_id'];
                $subJual->harga_satuan = $item['harga'] ?? 0;
                $subJual->finishing = $item['finishing'] ?? 'Tanpa Finishing';
                $subJual->diskon = $item['diskon'] ?? 0;
                $subJual->no_spk = null; // Kosongkan/Strip karena diproduksi cabang lain
                $subJual->keterangan = $item['keterangan'] ?? 'BANTUAN KE CABANG LAIN'; // Info tambahan
                $subJual->satuan = $produk->satuan;
                $subJual->user_id = Auth::id();
                $subJual->subtotal = $item['subtotal'];
                $subJual->status_sub_transaksi = 'proses';

                // Logika Dimensi
                if ($hitungLuas == 1) {
                    $subJual->panjang = $item['panjang'];
                    $subJual->lebar   = $item['lebar'];
                    $subJual->banyak  = $item['kuantitas'];
                } else {
                    $subJual->panjang = 0;
                    $subJual->lebar   = 0;
                    $subJual->banyak  = $item['kuantitas'];
                }
                $subJual->save();
                // CATATAN: Pastikan MSubTransaksiPenjualans TIDAK otomatis memotong stok
                // jika menggunakan logic Observer. Jika ya, harus di-bypass.


                // --- B. Simpan ke Sub Bantuan (Untuk Produksi B) ---
                $subBantuan = new MSubBantuanTransaksiPenjualans();
                $subBantuan->bantuan_penjualan_id = $transaksiBantuan->id;
                $subBantuan->produk_id = $item['produk_id'];
                $subBantuan->harga_satuan = $item['harga'] ?? 0;
                $subBantuan->finishing = $item['finishing'] ?? 'Tanpa Finishing';
                $subBantuan->diskon = $item['diskon'] ?? 0;
                $subBantuan->no_spk = null;
                $subBantuan->keterangan = $item['keterangan'] ?? '-';
                $subBantuan->satuan = $produk->satuan;
                $subBantuan->user_id = Auth::id();
                $subBantuan->subtotal = $item['subtotal'];
                $subBantuan->status_sub_transaksi = 'proses';

                // Logika Dimensi (Sama)
                if ($hitungLuas == 1) {
                    $subBantuan->panjang = $item['panjang'];
                    $subBantuan->lebar   = $item['lebar'];
                    $subBantuan->banyak  = $item['kuantitas'];
                } else {
                    $subBantuan->panjang = 0;
                    $subBantuan->lebar   = 0;
                    $subBantuan->banyak  = $item['kuantitas'];
                }
                $subBantuan->save();
            }

            // 4. LOGGING
            $cabangTujuan = Cabang::find($request->inputcabangbantuan)->nama;
            $isi = Auth::user()->username . " membuat transaksi penjualan & meminta bantuan produksi ke " . $cabangTujuan;
            $this->log($isi, "Permintaan Bantuan");

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Transaksi Penjualan dicatat & Permintaan Bantuan dikirim!',
                'id' => encrypt($transaksiBantuan->id), // Redirect ke report bantuan
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal Simpan Double Transaksi: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    public function accBantuan(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $transaksi = MBantuanTransaksiPenjualans::with('subBantuan.produk')->findOrFail($id);

            // 1. Update Status Utama
            $transaksi->update([
                'status_persetujuan_bantuan_transaksi' => 'acc'
            ]);

            // 2. Update Sub Transaksi & Potong Stok di Cabang B
            foreach ($transaksi->subBantuan as $sub) {

                // Update No SPK (Admin B yang menentukan nomor sesuai urutan cabangnya)
                $sub->update([
                    'no_spk' => $request->no_spk[$sub->id], // Diambil dari input form array
                ]);

                // Ambil Relasi Bahan untuk Produk ini
                $relasiBahan = MRelasiBahanBaku::where('produk_id', $sub->produk_id)->get();

                foreach ($relasiBahan as $rel) {
                    $bahan = MBahanBakus::find($rel->bahanbaku_id);

                    // Cari Stok di Cabang B (Cabang yang sedang login)
                    $stok = MStokBahanBakus::firstOrNew([
                        'bahanbaku_id' => $rel->bahanbaku_id,
                        'cabang_id'    => Auth::user()->cabang_id,
                    ]);

                    // Hitung pengurangan stok
                    if ($sub->produk->hitung_luas == 1) {
                        $penggunaan = $this->hitungLuas(
                            $sub->panjang,
                            $sub->lebar,
                            $sub->banyak,
                            $bahan->satuan,
                            'PCS'
                        );
                    } else {
                        $penggunaan = $sub->banyak;
                    }

                    // Potong stok Cabang B
                    $stok->banyakstok = ($stok->banyakstok ?? 0) - ($penggunaan * $rel->qtypertrx);
                    $stok->save();
                }
            }

            $isi = Auth::user()->username . " menyetujui bantuan produksi dari cabang " . $transaksi->cabangAsal->nama;
            $this->log($isi, "Konfirmasi Bantuan");

            DB::commit();
            return back()->with('success', 'Bantuan dikonfirmasi. Stok Cabang B telah terpotong.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal konfirmasi: ' . $e->getMessage());
        }
    }

    public function updateStatusSelesai($sub_id)
    {
        $sub = MSubBantuanTransaksiPenjualans::findOrFail($sub_id);

        $sub->update([
            'status_sub_transaksi' => 'selesai'
        ]);

        // Cek apakah SEMUA sub-transaksi di nota ini sudah selesai
        $cekSemuaSelesai = MSubBantuanTransaksiPenjualans::where('bantuan_penjualan_id', $sub->bantuan_penjualan_id)
            ->where('status_sub_transaksi', '!=', 'selesai')
            ->count();

        // Jika semua item selesai, tandai status transaksi utamanya juga selesai
        if ($cekSemuaSelesai == 0) {
            $sub->transaksiUtama->update([
                'status_bantuan_transaksi' => 'selesai'
            ]);
        }

        return back()->with('success', 'Barang ditandai Selesai!');
    }

    public function monitorKeluar()
    {
        $user = Auth::user();

        // Ambil data dimana SAYA sebagai pengirim (cabang_id)
        $data = MBantuanTransaksiPenjualans::with(['cabangBantuan', 'subBantuan'])
            ->where('cabang_id', $user->cabang_id) // Filter cabang saya
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.bantuan.monitor_keluar', compact('data'));
    }

    public function riwayatMasuk()
    {
        $user = Auth::user();

        // Ambil data dimana SAYA sebagai penerima bantuan (bantuan_cabang_id)
        // Dan statusnya sudah ACC atau Selesai (bukan pending/tolak)
        $data = MBantuanTransaksiPenjualans::with(['cabangAsal', 'subBantuan'])
            ->where('bantuan_cabang_id', $user->cabang_id)
            ->whereIn('status_persetujuan_bantuan_transaksi', ['acc'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.bantuan.riwayat_masuk', compact('data'));
    }

    public function cetakNotaInternal($id)
    {
        $transaksi = MBantuanTransaksiPenjualans::with(['cabangAsal', 'cabangBantuan', 'subBantuan.produk'])
            ->findOrFail($id);

        return view('admin.bantuan.cetak_nota_internal', compact('transaksi'));
    }
}
