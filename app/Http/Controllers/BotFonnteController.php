<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BotFonnteController extends Controller
{
    public function webhook(Request $request)
    {
        // Log semua request untuk debugging
        Log::info('Webhook Fonnte Masuk:', $request->all());

        // Cek token keamanan dari Fonnte
        if ($request->header('X-Fonnte-Token') !== env('FONNTE_WEBHOOK_TOKEN')) {
            Log::warning('Token webhook salah!');
            return response()->json(['status' => false, 'msg' => 'Invalid token'], 403);
        }

        // Pesan yang diterima (text)
        $message = $request->input('message') ?? '';
        $sender = $request->input('sender') ?? ''; // Nomor pengirim

        // Contoh balasan otomatis
        if (strtolower($message) == 'hai' || strtolower($message) == 'halo') {
            $this->sendMessage($sender, "Halo! Saya bot Laravel. Ada yang bisa saya bantu?");
        } else {
            $this->sendMessage($sender, "Pesan diterima: $message");
        }

        return response()->json(['status' => true]);
    }

    // Kirim balasan ke Fonnte API
    private function sendMessage($target, $text)
    {
        $token = env('FONNTE_TOKEN');

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.fonnte.com/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => [
                'target' => $target,
                'message' => $text,
            ],
            CURLOPT_HTTPHEADER => [
                "Authorization: $token"
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        Log::info('Fonnte Send Response:', ['response' => $response]);
    }
}
