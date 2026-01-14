<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user   = Auth::user();
        $cabang = $user->cabang->nama ?? '-';

        $today = Carbon::today();

        /* =========================
         * KARTU STATISTIK (HARI INI)
         * ========================= */

        // Total Transaksi (selesai, hari ini)
        $totalTransaksi = DB::table('transaksi_penjualans')
            ->where('status_transaksi', 'selesai')
            ->whereDate('created_at', $today)
            ->whereNull('deleted_at')
            ->count();

        // Total Pemasukan (hari ini)
        $totalPemasukan = DB::table('transaksi_penjualans')
            ->where('status_transaksi', 'selesai')
            ->whereDate('created_at', $today)
            ->whereNull('deleted_at')
            ->sum(DB::raw('COALESCE(jumlah_pembayaran, 0)'));

        // Total Pengeluaran (dummy)
        $totalPengeluaran = 0;

        // Pendapatan Bersih (hari ini)
        $pendapatanBersih = DB::table('sub_transaksi_penjualans as st')
            ->join('transaksi_penjualans as tp', 'tp.id', '=', 'st.penjualan_id')
            ->join('produks as p', 'p.id', '=', 'st.produk_id')
            ->where('tp.status_transaksi', 'selesai')
            ->where('st.status_sub_transaksi', 'selesai')
            ->whereDate('tp.created_at', $today)
            ->whereNull('tp.deleted_at')
            ->whereNull('st.deleted_at')
            ->whereNull('p.deleted_at')
            ->select(DB::raw('SUM((p.harga_jual - p.harga_beli) * st.banyak) as total'))
            ->value('total');

        /* =========================
         * DATA GRAFIK (12 HARI)
         * ========================= */

        $labels = [];
        $pemasukanMingguan = [];
        $bersihMingguan = [];
        $pengeluaranMingguan = [];

        // LOOP 12 HARI KE BELAKANG (KIRI → KANAN)
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);

            // Label hari (contoh: 12 Aug)
            $labels[] = $date->format('d M');

            // Total pemasukan per hari
            $pemasukanMingguan[] = DB::table('transaksi_penjualans')
                ->where('status_transaksi', 'selesai')
                ->whereDate('created_at', $date)
                ->whereNull('deleted_at')
                ->sum(DB::raw('COALESCE(jumlah_pembayaran, 0)'));

            // Pendapatan bersih per hari
            $bersihMingguan[] = DB::table('sub_transaksi_penjualans as st')
                ->join('transaksi_penjualans as tp', 'tp.id', '=', 'st.penjualan_id')
                ->join('produks as p', 'p.id', '=', 'st.produk_id')
                ->where('tp.status_transaksi', 'selesai')
                ->where('st.status_sub_transaksi', 'selesai')
                ->whereDate('tp.created_at', $date)
                ->whereNull('tp.deleted_at')
                ->whereNull('st.deleted_at')
                ->whereNull('p.deleted_at')
                ->select(DB::raw('SUM((p.harga_jual - p.harga_beli) * st.banyak) as total'))
                ->value('total') ?? 0;

            // Pengeluaran (dummy)
            $pengeluaranMingguan[] = 0;
        }

        return view('dashboard.index', compact(
            'user',
            'cabang',
            'totalTransaksi',
            'totalPemasukan',
            'totalPengeluaran',
            'pendapatanBersih',
            'labels',
            'pemasukanMingguan',
            'bersihMingguan',
            'pengeluaranMingguan'
        ));
    }
}
