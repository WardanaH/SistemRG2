<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\OperatorBantuanController;

Route::middleware(['auth'])->group(function () {
    Route::get('/operators', [OperatorController::class, 'dashboard'])->name('operator.dashboard');
    Route::get('/operators/profile', function () {
        return view('operator.layout.profile');
    })->name('operator.profile');

    Route::get('/operators/pesanan', [OperatorController::class, 'pesanan'])->name('operator.pesanan');
    Route::put('/operator/sub-transaksi/{id}', [OperatorController::class, 'updateStatus'])
        ->name('operator.updateStatus');

    Route::get('/operators/riwayat', [OperatorController::class, 'riwayat'])->name('operator.riwayat');

    // Halaman Daftar Kerja (Bantuan Masuk)
    Route::get('/operator/pesanan-bantuan', [OperatorBantuanController::class, 'pesanan'])->name('operator.pesanan.bantuan');

    // Proses Update Status
    Route::put('/operator/update-bantuan/{id}', [OperatorBantuanController::class, 'updateStatus'])->name('operator.update.bantuan');

    // Halaman Riwayat
    Route::get('/operator/riwayat-bantuan', [OperatorBantuanController::class, 'riwayat'])->name('operator.riwayat.bantuan');
});
