<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\MTransaksiPenjualans;
use Illuminate\Support\Facades\Http;

class FonnteWebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::info("WEBHOOK RAW PAYLOAD", [
            'raw' => $request->getContent()
        ]);

        // WAJIB PAKAI INI
        $data = json_decode($request->getContent(), true) ?? [];

        Log::info("WEBHOOK DECODE", $data);

        $pengirim = $data['sender'] ?? '';
        $pesan    = trim(strtolower($data['message'] ?? ''));

        if (!$pesan) {
            return response("NO MESSAGE", 200);
        }

        /** =========================
         * 1. PERINTAH: CEK NOTA
         * Format:
         * CEK NOTA 2401220001
         =========================== */
        if (preg_match('/cek nota (\w+)/i', $pesan, $m)) {
            $nota = $m[1];
            return $this->replyCekNota($pengirim, $nota);
        }

        /** =========================
         * 2. PERINTAH: CEK STATUS
         * Format:
         * CEK STATUS 2401220001
         =========================== */
        if (preg_match('/cek status (\w+)/i', $pesan, $m)) {
            $nota = $m[1];
            return $this->replyStatusPesanan($pengirim, $nota);
        }

        /** =========================
         * 3. PERINTAH: CEK PESANAN (Nama)
         * Format:
         * CEK PESANAN Restu
         =========================== */
        if (preg_match('/cek pesanan (.+)/i', $pesan, $m)) {
            $nama = $m[1];
            return $this->replyPesananByNama($pengirim, $nama);
        }

        /** =========================
         * 4. PERINTAH: CEK ITEM
         * Format:
         * CEK ITEM 2401220001
         =========================== */
        if (preg_match('/cek item (\w+)/i', $pesan, $m)) {
            $nota = $m[1];
            return $this->replyItemPesanan($pengirim, $nota);
        }

        // Default balasan
        return $this->sendText(
            $pengirim,
            "*Perintah tidak dikenali.*

Gunakan perintah berikut:

• CEK NOTA <nomor>
• CEK STATUS <nomor>
• CEK PESANAN <nama>
• CEK ITEM <nomor>

Contoh:
CEK NOTA 2401220001"
        );
    }


    /* ====================================================
     *   FUNGSI: CEK NOTA
     * ==================================================== */
    private function replyCekNota($target, $nota)
    {
        $trx = MTransaksiPenjualans::where('nomor_nota', 'RG-'.$nota)->first();

        if (!$trx) {
            return $this->sendText($target, "*Nomor nota tidak ditemukan!*");
        }

        $msg = "
*Detail Pesanan*
Nota: *{$trx->nomor_nota}*
Nama: *{$trx->nama_pelanggan}*
Total: *{$trx->total_harga_format}*
Sisa Tagihan: *{$trx->sisa_tagihan_format}*
Status: *{$trx->status_transaksi}*
";

        return $this->sendText($target, $msg);
    }


    /* ====================================================
     *   FUNGSI: CEK STATUS PESANAN
     * ==================================================== */
    private function replyStatusPesanan($target, $nota)
    {
        $trx = MTransaksiPenjualans::with('subTransaksi.produk')->where('nomor_nota', 'RG-'.$nota)->first();

        if (!$trx) {
            return $this->sendText($target, "*Nomor nota tidak ditemukan!*");
        }

        $msg = "*Status Pekerjaan — Nota {$trx->nomor_nota}*\n\n";

        foreach ($trx->subTransaksi as $s) {
            $msg .= "- {$s->produk->nama_produk}: *{$s->status_sub_transaksi}*\n";
        }

        return $this->sendText($target, $msg);
    }


    /* ====================================================
     *   FUNGSI: CEK PESANAN BY NAMA
     * ==================================================== */
    private function replyPesananByNama($target, $nama)
    {
        $trx = MTransaksiPenjualans::where('nama_pelanggan', 'LIKE', "%$nama%")->get();

        if ($trx->isEmpty()) {
            return $this->sendText($target, "*Tidak ada pesanan atas nama '$nama'.*");
        }

        $msg = "*Daftar Pesanan atas nama $nama*\n\n";

        foreach ($trx as $t) {
            $msg .= "- Nota: *{$t->nomor_nota}* → Status: *{$t->status_transaksi}*\n";
        }

        return $this->sendText($target, $msg);
    }


    /* ====================================================
     *   FUNGSI: CEK ITEM
     * ==================================================== */
    private function replyItemPesanan($target, $nota)
    {
        $trx = MTransaksiPenjualans::with('subTransaksi.produk')->where('nomor_nota', 'RG-'.$nota)->first();

        if (!$trx) {
            return $this->sendText($target, "*Nomor nota tidak ditemukan!*");
        }

        $msg = "
*Detail Item — Nota {$trx->nomor_nota}*\n\n";

        foreach ($trx->subTransaksi as $s) {
            $msg .= "- {$s->produk->nama_produk}\n  Qty: {$s->jumlah}\n  Status: *{$s->status_sub_transaksi}*\n\n";
        }

        return $this->sendText($target, $msg);
    }



    /* ====================================================
     *   FUNGSI SEND TEXT KE FONNTE
     * ==================================================== */
    private function sendText($target, $msg)
    {
        $token = "bnTxfJGZWyYGxSNt1wGL";

        Http::withHeaders([
            "Authorization" => $token
        ])->post("https://api.fonnte.com/send", [
            "target"  => $target,
            "message" => $msg
        ]);

        return "OK";
    }
}
