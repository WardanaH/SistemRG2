<?php

use App\Models\MTransaksiPenjualans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/wa/webhook', function (Request $request) {

    $from = $request->from;
    $msg  = strtolower(trim($request->message));

    // Contoh pelanggan mengetik: STATUS 12345 (no pesanan)
    if (str_starts_with($msg, "status")) {
        $parts = explode(" ", $msg);
        $kodePesanan = $parts[1] ?? null;

        if (!$kodePesanan) {
            return ["reply" => "Format salah.\n\nContoh:\nSTATUS 12345"];
        }

        // Cek database transaksi
        $trx = MTransaksiPenjualans::where("nomor_nota", $kodePesanan)->first();

        if (!$trx) {
            return ["reply" => "Pesanan dengan kode *$kodePesanan* tidak ditemukan."];
        }

        return [
            "reply" => "📦 *STATUS PESANAN*\n" .
                "No Pesanan : {$trx->nomor_pesanan}\n" .
                "Tanggal    : " . $trx->created_at->format('d-m-Y') . "\n" .
                "Status     : {$trx->status_transaksi}\n" .
                "Total      : Rp " . number_format($trx->total_harga, 0, ',', '.')
        ];
    }

    // Jika kirim pesan 'halo'
    if ($msg === "halo") {
        return [
            "reply" => "Halo! Untuk cek status pesanan, ketik:\n\nSTATUS <nomor>"
        ];
    }

    // Default reply
    return [
        "reply" => "Perintah tidak dikenal.\n\nCoba ketik: *halo*"
    ];
});
