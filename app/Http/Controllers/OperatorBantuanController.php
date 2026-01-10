<?php

namespace App\Http\Controllers;

use App\Models\MSubBantuanTransaksiPenjualans;
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

                $transaksiUtama = $sub->transaksiUtama;

                if ($transaksiUtama) {
                    // Cek apakah masih ada sub-transaksi LAIN dalam nota ini yang BELUM selesai
                    $masihAda = MSubBantuanTransaksiPenjualans::where('bantuan_penjualan_id', $transaksiUtama->id)
                        ->where('status_sub_transaksi', '!=', 'selesai')
                        ->exists();

                    // Jika TIDAK ADA yang tersisa (artinya semua item sudah selesai)
                    if (!$masihAda) {
                        $transaksiUtama->update([
                            'status_bantuan_transaksi' => 'selesai', // Status pengerjaan cabang B
                            'status_transaksi'         => 'selesai'  // <--- TAMBAHAN: Status umum transaksi jadi selesai
                        ]);
                    }
                }
            }

            // 4. Logging
            $isi = "Operator " . auth()->user()->username . " menyelesaikan bantuan produksi " . $sub->no_spk . " (" . $sub->produk->nama_produk . ")";
            $this->log($isi, "Perbaruan Bantuan");
        });

        return redirect()
            ->route('operator.pesanan.bantuan') // Pastikan nama route ini benar sesuai web.php
            ->with('success', 'Status bantuan berhasil diperbarui');
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
