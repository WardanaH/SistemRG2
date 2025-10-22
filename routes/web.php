<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CabangController;
use App\Http\Controllers\JenisPelanggansController;

// Guest (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Authenticated (sudah login)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Manajemen Sistem
    Route::middleware(['role:admin|direktur|manajemen|supervisor'])->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('cabangs', CabangController::class);
        Route::resource('roles', RoleController::class)->only(['index', 'store', 'update', 'destroy']);
    });
});

//supplier
Route::middleware(['auth'])->group(function () {
    Route::get('/supplier', [SupplierController::class, 'index'])->name('managesupplierindex');
    Route::get('/supplier/loadsupplier', [SupplierController::class, 'loadsupplier'])->name('loadsupplier');
    Route::post('/supplier/postsupplier', [SupplierController::class, 'store'])->name('storesupplier');
    Route::post('/supplier/updatesupplier', [SupplierController::class, 'update'])->name('updatesupplier');
    Route::post('/supplier/deletesupplier', [SupplierController::class, 'destroy'])->name('deletesupplier');
});

//jenis pelanggans
Route::middleware(['auth'])->group(function () {
    Route::get('/jenispelanggan', [JenisPelanggansController::class, 'index'])->name('jenispelanggan.index');
    Route::post('/jenispelanggan/store', [JenisPelanggansController::class, 'store'])->name('jenispelanggan.store');
    Route::put('/jenispelanggan/update', [JenisPelanggansController::class, 'update'])->name('jenispelanggan.update');
    Route::delete('/jenispelanggan/destroy', [JenisPelanggansController::class, 'destroy'])->name('jenispelanggan.destroy');
});

// Kategori
Route::middleware(['auth'])->group(function () {
    Route::get('/kategori', [App\Http\Controllers\KategoriController::class, 'index'])->name('managekategoriindex');
    Route::get('/kategori/loadkategori', [App\Http\Controllers\KategoriController::class, 'loadkategori'])->name('loadkategori');
    Route::post('/kategori/postkategori', [App\Http\Controllers\KategoriController::class, 'store'])->name('storekategori');
    Route::post('/kategori/updatekategori', [App\Http\Controllers\KategoriController::class, 'update'])->name('updatekategori');
    Route::post('/kategori/deletekategori', [App\Http\Controllers\KategoriController::class, 'destroy'])->name('deletekategori');
});

