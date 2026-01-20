<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();

        /* =========================
         * KARTU STATISTIK (HARI INI)
         * ========================= */

        $totalTransaksi = DB::table('transaksi_penjualans')
            ->whereDate('created_at', $today)
            ->whereNull('deleted_at')
            ->count();

        $totalPemasukan = DB::table('transaksi_penjualans')
            ->where('status_transaksi', 'selesai')
            ->whereDate('created_at', $today)
            ->whereNull('deleted_at')
            ->sum(DB::raw('COALESCE(jumlah_pembayaran, 0)'));

        // Mengambil pengeluaran asli dari tabel pengeluarans (asumsi nama tabel)
        // $totalPengeluaran = DB::table('pengeluarans')
        //     ->whereDate('created_at', $today)
        //     ->whereNull('deleted_at')
        //     ->sum('nominal') ?? 0;

        $pendapatanBersih = DB::table('sub_transaksi_penjualans as st')
            ->join('transaksi_penjualans as tp', 'tp.id', '=', 'st.penjualan_id')
            ->join('produks as p', 'p.id', '=', 'st.produk_id')
            ->where('tp.status_transaksi', 'selesai')
            ->whereDate('tp.created_at', $today)
            ->whereNull('tp.deleted_at')
            ->whereNull('st.deleted_at')
            ->select(DB::raw('SUM((p.harga_jual - p.harga_beli) * st.banyak) as total'))
            ->value('total') ?? 0;

        /* =========================
         * DATA GRAFIK LINE (12 HARI)
         * ========================= */

        $labels = [];
        $pemasukanSeries = [];
        $bersihSeries = [];
        $pengeluaranSeries = [];
        $transaksiSeries = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels[] = $date->format('d M');

            $pemasukanSeries[] = DB::table('transaksi_penjualans')
                ->where('status_transaksi', 'selesai')
                ->whereDate('created_at', $date)
                ->whereNull('deleted_at')
                ->sum('jumlah_pembayaran') ?? 0;

            // $pengeluaranSeries[] = DB::table('pengeluarans')
            //     ->whereDate('created_at', $date)
            //     ->whereNull('deleted_at')
            //     ->sum('nominal') ?? 0;

            $bersihSeries[] = DB::table('sub_transaksi_penjualans as st')
                ->join('transaksi_penjualans as tp', 'tp.id', '=', 'st.penjualan_id')
                ->join('produks as p', 'p.id', '=', 'st.produk_id')
                ->where('tp.status_transaksi', 'selesai')
                ->whereDate('tp.created_at', $date)
                ->whereNull('tp.deleted_at')
                ->select(DB::raw('SUM((p.harga_jual - p.harga_beli) * st.banyak) as total'))
                ->value('total') ?? 0;

            $transaksiSeries[] = DB::table('transaksi_penjualans')
                ->where('status_transaksi', 'selesai')
                ->whereDate('created_at', $date)
                ->whereNull('deleted_at')
                ->count();
        }

        /* =========================
         * PIE CHART: 5 TERLARIS (1 TAHUN)
         * ========================= */
        $terlaris = DB::table('sub_transaksi_penjualans as st')
            ->join('produks as p', 'p.id', '=', 'st.produk_id')
            ->join('transaksi_penjualans as tp', 'tp.id', '=', 'st.penjualan_id')
            ->select('p.nama_produk', DB::raw('SUM(st.banyak) as total_qty'))
            ->where('tp.status_transaksi', 'selesai')
            ->where('tp.created_at', '>=', Carbon::now()->subYear())
            ->whereNull('st.deleted_at')
            ->groupBy('p.id', 'p.nama_produk')
            ->orderBy('total_qty', 'desc')
            ->limit(5)
            ->get();

        $pieLabels = $terlaris->pluck('nama_produk')->toArray();
        $pieSeries = $terlaris->pluck('total_qty')->toArray();

        return view('dashboard.index', compact(
            'user',
            'totalTransaksi',
            'totalPemasukan',
            // 'totalPengeluaran',
            'pendapatanBersih',
            'labels',
            'pemasukanSeries',
            'bersihSeries',
            // 'pengeluaranSeries',
            'transaksiSeries',
            'pieLabels',
            'pieSeries'
        ));
    }
}
