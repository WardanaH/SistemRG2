<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BahanBakuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\PelanggansController;
use App\Http\Controllers\Admin\CabangController;
use App\Http\Controllers\StokBahanBakusController;
use App\Http\Controllers\JenisPelanggansController;
use App\Http\Controllers\RelasiBahanBakuController;
use App\Http\Controllers\TransaksiBahanBakusController;
use App\Http\Controllers\TransaksiPenjualansController;

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

//profile di header
Route::middleware('auth')->group(function () {
    Route::get('/profile', function () {return view('layouts.profile');})->name('profile');
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
    Route::get('/kategori', [KategoriController::class, 'index'])->name('managekategoriindex');
    Route::get('/kategori/loadkategori', [KategoriController::class, 'loadkategori'])->name('loadkategori');
    Route::post('/kategori/postkategori', [KategoriController::class, 'store'])->name('storekategori');
    Route::post('/kategori/updatekategori', [KategoriController::class, 'update'])->name('updatekategori');
    Route::post('/kategori/deletekategori', [KategoriController::class, 'destroy'])->name('deletekategori');
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

// Bahan Baku
Route::middleware(['auth'])->group(function () {
    Route::get('/bahanbaku', [BahanBakuController::class, 'index'])->name('managebahanbakuindex');
    Route::get('/bahanbaku/loadbahanbaku', [BahanBakuController::class, 'loadbahanbaku'])->name('bahanbaku.load');
    Route::post('/bahanbaku/postbahanbaku', [BahanBakuController::class, 'store'])->name('storebahanbaku');
    Route::post('/bahanbaku/updatebahanbaku', [BahanBakuController::class, 'update'])->name('updatebahanbaku');
    Route::post('/bahanbaku/deletebahanbaku', [BahanBakuController::class, 'destroy'])->name('deletebahanbaku');
});

// Relasi Bahan Baku
Route::middleware(['auth'])->group(function () {
    Route::get('/relasibahanbaku', [RelasiBahanBakuController::class, 'index'])->name('managerelasibahanbakuindex');
    Route::get('/relasibahanbaku/load', [RelasiBahanBakuController::class, 'loadrelasibahanbaku'])->name('loadrelasibahanbaku');
    Route::post('/relasibahanbaku/store', [RelasiBahanBakuController::class, 'store'])->name('storerelasibahanbaku');
    Route::post('/relasibahanbaku/update', [RelasiBahanBakuController::class, 'update'])->name('updaterelasibahanbaku');
    Route::post('/relasibahanbaku/delete', [RelasiBahanBakuController::class, 'destroy'])->name('deleterelasibahanbaku');
});

// Stok Bahan Baku
Route::middleware(['auth'])->group(function () {
    Route::get('/stokbahanbaku', [StokBahanBakusController::class, 'index'])->name('stokbahanbaku.index');
    Route::get('/stokbahanbaku/create', [StokBahanBakusController::class, 'create'])->name('stokbahanbaku.create');
    Route::post('/stokbahanbaku', [StokBahanBakusController::class, 'store'])->name('stokbahanbaku.store');
    Route::get('/stokbahanbaku/{id}/edit', [StokBahanBakusController::class, 'edit'])->name('stokbahanbaku.edit');
    Route::put('/stokbahanbaku/{id}', [StokBahanBakusController::class, 'update'])->name('stokbahanbaku.update');
    Route::delete('/stokbahanbaku', [StokBahanBakusController::class, 'destroy'])->name('stokbahanbaku.destroy');
});

// Transaksi Stok Bahan Baku
Route::middleware(['auth'])->group(function () {
    Route::get('/transaksi/bahan', [TransaksiBahanBakusController::class, 'index'])
        ->name('transaksibahanbaku.index');

    // Form tambah transaksi
    Route::get('/transaksi/bahan/create', [TransaksiBahanBakusController::class, 'create'])
        ->name('transaksibahanbaku.create');

    // Proses simpan transaksi baru
    Route::post('/transaksi/bahan/store', [TransaksiBahanBakusController::class, 'store'])
        ->name('transaksibahanbaku.store');

    // Form edit transaksi (menampilkan data yang sudah ada)
    Route::get('/transaksi/bahan/edit/{id}', [TransaksiBahanBakusController::class, 'show'])
        ->name('transaksibahanbaku.edit');

    // Proses update data transaksi
    Route::put('/transaksi/bahan/update/{id}', [TransaksiBahanBakusController::class, 'update'])
        ->name('transaksibahanbaku.update');

    // Hapus transaksi (soft delete)
    Route::get('/transaksi/bahan/delete/{id}', [TransaksiBahanBakusController::class, 'destroy'])
        ->name('transaksibahanbaku.destroy');

    // Menampilkan data transaksi yang sudah dihapus (soft deleted)
    Route::get('/transaksi/bahan/deleted', [TransaksiBahanBakusController::class, 'indexdeleted'])
        ->name('transaksibahanbaku.deleted');

    // Mengembalikan data bahan baku
    Route::get('/ajax/load-bahanbaku', [TransaksiBahanBakusController::class, 'loadBahanBaku'])
        ->name('loadbahanbaku');
});

// Transaksi Penjualan
Route::middleware(['auth'])->group(function () {
    Route::get('/transaksi', [TransaksiPenjualansController::class, 'index'])->name('transaksiindex');
    Route::get('/transaksi/penjualan', [TransaksiPenjualansController::class, 'transaksi'])->name('addtransaksiindex');
    Route::get('/transaksi/penjualan/load', [TransaksiPenjualansController::class, 'load'])->name('loadtransaksipenjualan');
    Route::post('/transaksi/penjualan/store', [TransaksiPenjualansController::class, 'store'])->name('storetransaksipenjualan');
    Route::post('/transaksi/penjualan/update', [TransaksiPenjualansController::class, 'update'])->name('updatetransaksipenjualan');
    Route::delete('/transaksi/penjualan/delete/{id}', [TransaksiPenjualansController::class, 'destroy'])->name('destroytransaksipenjualan');
});
