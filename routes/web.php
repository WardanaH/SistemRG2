<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CabangController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JenisPelanggansController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PelanggansController;

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

// Jenis Pelanggan
Route::controller(JenisPelanggansController::class)->group(function () {
    Route::get('/jenispelanggan', 'index')->name('jenispelanggan.index');
    Route::post('/jenispelanggan/store', 'store')->name('jenispelanggan.store');
    Route::put('/jenispelanggan/update', 'update')->name('jenispelanggan.update');
    Route::delete('/jenispelanggan/delete', 'destroy')->name('jenispelanggan.destroy');
    Route::get('/jenispelanggan/cari', 'jenispelanggancari')->name('jenispelanggan.cari');
});


// Kategori
Route::middleware(['auth'])->group(function () {
    Route::get('/kategori', [App\Http\Controllers\KategoriController::class, 'index'])->name('managekategoriindex');
    Route::get('/kategori/loadkategori', [App\Http\Controllers\KategoriController::class, 'loadkategori'])->name('loadkategori');
    Route::post('/kategori/postkategori', [App\Http\Controllers\KategoriController::class, 'store'])->name('storekategori');
    Route::post('/kategori/updatekategori', [App\Http\Controllers\KategoriController::class, 'update'])->name('updatekategori');
    Route::post('/kategori/deletekategori', [App\Http\Controllers\KategoriController::class, 'destroy'])->name('deletekategori');
});

// produk
Route::middleware(['auth'])->group(function () {
    Route::get('/produk', [ProdukController::class, 'index'])->name('manageprodukindex');
    Route::get('/produk/loadproduk', [ProdukController::class, 'loadproduk'])->name('loadproduk');
    Route::post('/produk/postproduk', [ProdukController::class, 'store'])->name('storeproduk');
    Route::post('/produk/updateproduk', [ProdukController::class, 'update'])->name('updateproduk');
    Route::post('/produk/deleteproduk', [ProdukController::class, 'destroy'])->name('deleteproduk');
});

// Pelanggan
Route::middleware('auth')->group(function () {
    Route::get('/pelanggan', [PelanggansController::class, 'index'])->name('pelanggan.index');
    Route::get('/pelanggan/data', [PelanggansController::class, 'getData'])->name('pelanggan.data');
    Route::get('/pelanggan/{id}/detail', [PelanggansController::class, 'show'])->name('pelanggan.show');
    Route::post('/pelanggan/store', [PelanggansController::class, 'store'])->name('pelanggan.store');
    Route::post('/pelanggan/update', [PelanggansController::class, 'update'])->name('pelanggan.update');
    Route::post('/pelanggan/destroy', [PelanggansController::class, 'destroy'])->name('pelanggan.destroy');
});
