<?php

// use Illuminate\Support\Facades\Route;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BotFonnteController;

// Route::post('/fonnte/webhook', function (Request $request) {

//     $message = strtolower($request->input('message', ''));

//     if ($message == 'halo') {
//         return [
//             'status' => true,
//             'reply' => 'Hai juga! Ada yang bisa saya bantu?'
//         ];
//     }

//     if ($message == 'menu') {
//         return [
//             'status' => true,
//             'reply' => "Menu:\n1. Cek Nota\n2. Cek Angsuran\n3. Bantuan"
//         ];
//     }

//     return [
//         'status' => true,
//         'reply' => 'Saya tidak mengerti, kirim "menu" untuk bantuan.'
//     ];
// });



// Route::post('/webhook/fonnte', [BotFonnteController::class, 'webhook']);

Route::post('/webhook/fonnte', function(Request $request) {
    Log::info('Fonnte Webhook:', $request->all());

    // Contoh balasan otomatis
    $text = strtolower($request->input('message', ''));

    if ($text === 'halo' || $text === 'hi') {
        return response()->json([
            "reply" => "Halo juga! Ada yang bisa saya bantu?"
        ]);
    }

    return response()->json(['reply' => null]);
});
