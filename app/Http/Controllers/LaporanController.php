<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index()
    {
        return view('admin.laporan.index');
    }

    public function filter(Request $request)
    {
        $cabangId = Auth::user()->cabang_id;
        $start = Carbon::parse($request->startDate)->startOfDay();
        $end = Carbon::parse($request->endDate)->endOfDay();

        // --- DATA RINGKASAN PENJUALAN ---
        $pembayaranPenjualan = DB::table('transaksi_penjualans')
            ->where('cabang_id', $cabangId)
            ->whereBetween('tanggal', [$start, $end])
            ->sum('jumlah_pembayaran');

        $piutangPenjualan = DB::table('transaksi_penjualans')
            ->where('cabang_id', $cabangId)
            ->whereBetween('tanggal', [$start, $end])
            ->sum('sisa_tagihan');

        // --- DATA TRANSAKSI PENCAIRAN PIUTANG (ANGSURAN) ---
        $c_Pencairan_Piutang = DB::table('angsurans')
            ->where('cabang_id', $cabangId)
            ->where('metode_pembayaran', 'Cash')
            ->whereBetween('tanggal_angsuran', [$start, $end])
            ->sum('nominal_angsuran');

        $t_Pencairan_Piutang = DB::table('angsurans')
            ->where('cabang_id', $cabangId)
            ->where('metode_pembayaran', 'Transfer')
            ->whereBetween('tanggal_angsuran', [$start, $end])
            ->sum('nominal_angsuran');

        // --- DATA GRAFIK (HANYA PEMASUKAN & PIUTANG) ---
        $monthText = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $monthLabel = [];
        $mPemasukan = [];
        $mPiutang = [];

        for ($m = 1; $m <= 12; $m++) {
            $monthLabel[] = $monthText[$m - 1];

            $mPemasukan[] = DB::table('transaksi_penjualans')
                ->where('cabang_id', $cabangId)
                ->whereMonth('tanggal', $m)
                ->whereYear('tanggal', date('Y'))
                ->sum('jumlah_pembayaran') ?: 0;

            $mPiutang[] = DB::table('transaksi_penjualans')
                ->where('cabang_id', $cabangId)
                ->whereMonth('tanggal', $m)
                ->whereYear('tanggal', date('Y'))
                ->sum('sisa_tagihan') ?: 0;
        }

        return response()->json([
            'Pembayaran_Penjualan' => (int)$pembayaranPenjualan,
            'Piutang_Penjualan'    => (int)$piutangPenjualan,
            'c_Pencairan_Piutang'  => (int)$c_Pencairan_Piutang,
            't_Pencairan_Piutang'  => (int)$t_Pencairan_Piutang,
            'datachartMonth'       => $monthLabel,
            'datachartPemasukan'   => $mPemasukan,
            'datachartPiutang'     => $mPiutang,
        ]);
    }
}
