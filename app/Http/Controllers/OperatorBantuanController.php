<?php

namespace App\Http\Controllers;

use App\Models\MSubBantuanTransaksiPenjualans;
use App\Models\MTransaksiPenjualans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OperatorBantuanController extends Controller
{
    /**
     * Menampilkan daftar bantuan yang SEDANG DIPROSES
     */
    public function pesanan()
    {
        $user = auth()->user();
        $cabangId = $user->cabang->id;
        $role = $user->roles->first()->name ?? null;

        $kategoriFilter = $this->getKategoriFilter($role);

        $subTransaksiData = MSubBantuanTransaksiPenjualans::with(['produk.kategori', 'transaksiUtama'])
            // Filter hanya ambil data bantuan yang ditujukan ke cabang user ini
            ->whereHas('transaksiUtama', function ($q) use ($cabangId) {
                $q->where('bantuan_cabang_id', $cabangId)
                    ->where('status_persetujuan_bantuan_transaksi', 'acc'); // Wajib sudah di-ACC Admin
            })
            // Filter kategori berdasarkan role operator (Indoor/Outdoor)
            ->when($kategoriFilter, function ($query) use ($kategoriFilter) {
                $query->whereHas('produk.kategori', fn($q) => $q->where('nama_kategori', $kategoriFilter));
            })
            // Hanya tampilkan yang BELUM selesai
            ->where('status_sub_transaksi', '!=', 'selesai')
            ->get();

        return view('operator.status_pesanan_bantuan', compact('subTransaksiData'));
    }

    /**
     * Menampilkan RIWAYAT bantuan yang sudah selesai
     */
    public function riwayat()
    {
        $user = auth()->user();
        $cabangId = $user->cabang->id;
        $role = $user->roles->first()->name ?? null;

        $kategoriFilter = $this->getKategoriFilter($role);

        $subTransaksiData = MSubBantuanTransaksiPenjualans::with(['produk.kategori', 'transaksiUtama'])
            ->whereHas('transaksiUtama', function ($q) use ($cabangId) {
                $q->where('bantuan_cabang_id', $cabangId);
            })
            ->when($kategoriFilter, function ($query) use ($kategoriFilter) {
                $query->whereHas('produk.kategori', fn($q) => $q->where('nama_kategori', $kategoriFilter));
            })
            ->where('status_sub_transaksi', 'selesai')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('operator.riwayat_pesanan_bantuan', compact('subTransaksiData'));
    }

    /**
     * Proses Update Status oleh Operator
     */
    public function updateStatus(Request $request, $id)
    {
        DB::transaction(function () use ($request, $id) {

            // 1. Ambil sub bantuan beserta transaksi utamanya
            $sub = MSubBantuanTransaksiPenjualans::with('transaksiUtama')->findOrFail($id);

            $statusLama = $sub->status_sub_transaksi;
            $statusBaru = $request->status_sub_transaksi;

            // 2. Update status per item sub-transaksi
            $sub->update([
                'status_sub_transaksi' => $statusBaru
            ]);

            // 3. Cek apakah ini penyelesaian item terakhir?
            if ($statusLama !== 'selesai' && $statusBaru === 'selesai') {

                $transaksiBantuan = $sub->transaksiUtama;

                if ($transaksiBantuan) {
                    // Cek sisa item di tabel bantuan
                    $masihAda = MSubBantuanTransaksiPenjualans::where('bantuan_penjualan_id', $transaksiBantuan->id)
                        ->where('status_sub_transaksi', '!=', 'selesai')
                        ->exists();

                    // Jika SEMUA item bantuan sudah selesai
                    if (!$masihAda) {

                        // A. Update Status di Tabel Bantuan (Sisi Cabang B)
                        $transaksiBantuan->update([
                            'status_bantuan_transaksi' => 'selesai',
                            'status_transaksi'         => 'selesai'
                        ]);

                        // B. UPDATE STATUS DI TABEL TRANSAKSI ASLI (Sisi Cabang A)
                        // Kita cari transaksi di Cabang A yang datanya COCOK
                        $transaksiAsli = MTransaksiPenjualans::where('cabang_id', $transaksiBantuan->cabang_id) // Cabang Peminta
                            ->where('pelanggan_id', $transaksiBantuan->pelanggan_id)
                            ->whereDate('tanggal', $transaksiBantuan->tanggal)
                            ->where('total_harga', $transaksiBantuan->total_harga)
                            ->where('status_transaksi', '!=', 'selesai') // Cari yang belum selesai
                            ->first();

                        if ($transaksiAsli) {
                            $transaksiAsli->update([
                                'status_transaksi' => 'selesai'
                            ]);

                            // Opsional: Log tambahan bahwa transaksi pusat sudah diupdate
                            $this->log("Sistem otomatis menyelesaikan Transaksi Asli " . $transaksiAsli->nomor_nota, "System Auto-Update");
                        }
                    }
                }
            }

            // 4. Logging Operator
            $isi = "Operator " . auth()->user()->username . " menyelesaikan bantuan produksi " . $sub->no_spk . " (" . $sub->produk->nama_produk . ")";
            $this->log($isi, "Perbaruan Bantuan");
        });

        return redirect()
            ->route('operator.pesanan.bantuan')
            ->with('success', 'Status bantuan berhasil diperbarui & sinkronasi ke Cabang A selesai.');
    }

    // Helper Filter Role
    private function getKategoriFilter($role)
    {
        return match ($role) {
            'operator indoor' => 'Indoor',
            'operator outdoor' => 'Outdoor',
            'operator multi' => null, // Operator sakti bisa lihat semua
            default => null,
        };
    }
}
