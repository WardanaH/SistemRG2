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
        Log::info("WEBHOOK RAW", [$request->getContent()]);

        $data = json_decode($request->getContent(), true) ?? [];

        $pengirim = $data['sender'] ?? null;
        $pesan    = trim(strtolower($data['message'] ?? ''));

        if (!$pengirim) return response("NO SENDER", 200);

        /** ======================================================
         *   CEK APAKAH CUSTOMER BARU (BELUM PERNAH CHAT)
         * ====================================================== */
        $isNew = \App\Models\ChatHistory::where('wa_number', $pengirim)->doesntExist();

        if ($isNew) {

            // SIMPAN SEBAGAI SUDAH CHAT
            \App\Models\ChatHistory::create(['wa_number' => $pengirim]);

            // KIRIM PANDUAN
            return $this->sendText(
                $pengirim,
                "*Selamat datang!* 👋

Gunakan perintah berikut:

• CEK NOTA <nomor>
• CEK STATUS <nomor>
• CEK PESANAN <nama>
• CEK ITEM <nomor>

Contoh:
CEK NOTA 2401220001"
            );
        }

        /** ======================================================
         *   JIKA TIDAK ADA PESAN → BOT DIAM
         * ====================================================== */
        if (!$pesan) return response("NO MESSAGE", 200);

        /** ======================================================
         *   CEK PERINTAH
         * ====================================================== */

        if (preg_match('/cek nota (\w+)/i', $pesan, $m)) {
            return $this->replyCekNota($pengirim, $m[1]);
        }

        if (preg_match('/cek status (\w+)/i', $pesan, $m)) {
            return $this->replyStatusPesanan($pengirim, $m[1]);
        }

        if (preg_match('/cek pesanan (.+)/i', $pesan, $m)) {
            return $this->replyPesananByNama($pengirim, $m[1]);
        }

        if (preg_match('/cek item (\w+)/i', $pesan, $m)) {
            return $this->replyItemPesanan($pengirim, $m[1]);
        }

        /** ======================================================
         *   BUKAN PERINTAH → BOT DIAM
         * ====================================================== */
        return response("NO REPLY", 200);
    }



    /* ====================================================
     *   FUNGSI: CEK NOTA
     * ==================================================== */
    private function replyCekNota($target, $nota)
    {
        $trx = MTransaksiPenjualans::where('nomor_nota', 'RG-' . $nota)->first();

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
        $trx = MTransaksiPenjualans::with('subTransaksi.produk')->where('nomor_nota', 'RG-' . $nota)->first();

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
        $trx = MTransaksiPenjualans::with('subTransaksi.produk')->where('nomor_nota', 'RG-' . $nota)->first();

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
