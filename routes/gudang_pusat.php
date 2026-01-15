<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GudangPusatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GudangCabangController;


/*
|--------------------------------------------------------------------------
| GUDANG PUSAT ROUTES
|--------------------------------------------------------------------------
| Role : Inventory Utama
| User : invutama
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:inventory utama|owner'])
    ->group(function () {
        /*
        |=====================
        | DASHBOARD
        |=====================
        */
        Route::get('/dashboard-gudang-pusat', function () {
            return view('admin.inventaris.gudangpusat.dashboard');
        })->name('gudangpusat.dashboard');

        /*
        |=====================
        | MANAJEMEN CABANG (INVENTARIS)
        |=====================
        */
        Route::get('/manajemen-gudang-cabang', [GudangCabangController::class, 'manageCabang'])
            ->name('gudangpusat.cabang.index');

        Route::post('/manajemen-gudang-cabang/store', [GudangCabangController::class, 'manageCabangStore'])
            ->name('gudangpusat.cabang.store');

        Route::put('/manajemen-gudang-cabang/update/{id}', [GudangCabangController::class, 'manageCabangUpdate'])
            ->name('gudangpusat.cabang.update');

        Route::delete('/manajemen-gudang-cabang/delete/{id}', [GudangCabangController::class, 'manageCabangDelete'])
            ->name('gudangpusat.cabang.delete');

        Route::post('/manajemen-gudang-cabang/toggle/{id}', [GudangCabangController::class, 'toggle'])
            ->name('gudangpusat.cabang.toggle');
        /*
        |=====================
        | BARANG
        |=====================
        */
        Route::get('/barang-pusat', [GudangPusatController::class, 'barang'])
            ->name('barang.pusat');

        Route::post('/barang-pusat/store', [GudangPusatController::class, 'storeBarang'])
            ->name('barang.pusat.store');

        Route::put('/barang-pusat/update/{id}', [GudangPusatController::class, 'updateBarang'])
            ->name('barang.pusat.update');

        Route::delete('/barang-pusat/delete/{id}', [GudangPusatController::class, 'destroyBarang'])
            ->name('barang.pusat.destroy');

        /*
        |=====================
        | STOK
        |=====================
        */
        Route::get('/stok-pusat', [GudangPusatController::class, 'stok'])
            ->name('stok.pusat');

        Route::post('/stok-pusat/tambah', [GudangPusatController::class, 'tambahStok'])
            ->name('stok.pusat.tambah');

        Route::put('/stok-pusat/update/{id}', [GudangPusatController::class, 'updateStok'])
            ->name('stok.pusat.update');

        Route::delete('/stok-pusat/delete/{id}', [GudangPusatController::class, 'deleteStok'])
            ->name('stok.pusat.delete');

        /*
        |=====================
        | PENGIRIMAN
        |=====================
        */
        Route::get('/pengiriman-pusat', [GudangPusatController::class, 'index'])
            ->name('pengiriman.pusat.index');

        Route::post('/pengiriman-pusat/store', [GudangPusatController::class, 'store'])
            ->name('pengiriman.pusat.store');

        Route::put('/pengiriman-pusat/update-status/{id}', [GudangPusatController::class, 'updateStatus'])
            ->name('pengiriman.pusat.updateStatus');

        Route::delete('/pengiriman-pusat/delete/{id}', [GudangPusatController::class, 'destroy'])
            ->name('pengiriman.pusat.destroy');
    });
