<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GudangCabangController;

/*
|--------------------------------------------------------------------------
| INVENTORY CABANG
|--------------------------------------------------------------------------
| Role  : Inventory Cabang
| Users : invbjm, invlgg, invplh, invmtp, invbjb
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| ROUTE STATIS (1 CABANG PER USER)
| Digunakan untuk dashboard inventory cabang
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:inventory cabang'])
    ->group(function () {

        // ======================
        // DASHBOARD
        // ======================
        Route::get('/dashboard', function () {
            return view('admin.inventaris.templateinventaris.dashboard');
        })->name('templateinventaris.dashboard');

    });

/*
|--------------------------------------------------------------------------
| ROUTE DINAMIS CABANG (BERDASARKAN SLUG)
| Dipakai oleh sidebar: route('cabang.*', $slug)
|--------------------------------------------------------------------------
| Role :
| - Inventory Utama   → bisa akses semua cabang
| - Inventory Cabang  → hanya cabang miliknya (dikunci di controller)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:inventory utama|inventory cabang'])
    ->prefix('cabang/{slug}')
    ->name('cabang.')
    ->group(function () {

        // ======================
        // BARANG
        // ======================
        Route::get('/barang-cabang', [GudangCabangController::class, 'barang'])
            ->name('barang.cabang');

        Route::post('/barang-cabang/store', [GudangCabangController::class, 'barangStore'])
            ->name('barang.cabang.store');

        Route::put('/barang-cabang/update/{id}', [GudangCabangController::class, 'barangUpdate'])
            ->name('barang.cabang.update');

        Route::delete('/barang-cabang/delete/{id}', [GudangCabangController::class, 'barangDestroy'])
            ->name('barang.cabang.destroy');

        // ======================
        // STOK
        // ======================
        Route::get('/stok-cabang', [GudangCabangController::class, 'stok'])
            ->name('stok.cabang');

        Route::post('/stok-cabang/store', [GudangCabangController::class, 'stokStore'])
            ->name('stok.cabang.store');

        Route::put('/stok-cabang/update/{id}', [GudangCabangController::class, 'stokUpdate'])
            ->name('stok.cabang.update');

        Route::delete('/stok-cabang/delete/{id}', [GudangCabangController::class, 'stokDestroy'])
            ->name('stok.cabang.destroy');

        // ======================
        // INVENTARIS
        // ======================
        Route::get('/inventaris-cabang', [GudangCabangController::class, 'inventaris'])
            ->name('inventaris.cabang');

        Route::post('/inventaris-cabang/store', [GudangCabangController::class, 'inventarisStore'])
            ->name('inventaris.cabang.store');

        Route::put('/inventaris-cabang/update/{id}', [GudangCabangController::class, 'inventarisUpdate'])
            ->name('inventaris.cabang.update');

        Route::delete('/inventaris-cabang/delete/{id}', [GudangCabangController::class, 'inventarisDestroy'])
            ->name('inventaris.cabang.destroy');

        // ======================
        // RIWAYAT
        // ======================
        Route::get('/riwayat-cabang', [GudangCabangController::class, 'riwayat'])
            ->name('riwayat.cabang');

        Route::put('/riwayat-cabang/terima/{id}', [GudangCabangController::class, 'riwayatTerima'])
            ->name('riwayat.cabang.terima');
    });
