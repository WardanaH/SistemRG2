<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\DesignerController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\OperatorController;
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
use GuzzleHttp\Middleware;

require __DIR__.'/operator.php';

// Guest (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Authenticated (sudah login)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard-rg', [DashboardController::class, 'index'])->middleware('permission:index-home')->name('dashboard');

});

// Manajemen Users
Route::middleware(['auth'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->middleware('permission:manage-users')->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->middleware('permission:edit-users')->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->middleware('permission:add-users')->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->middleware('permission:edit-users')->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->middleware('permission:edit-users')->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->middleware('permission:delete-users')->name('users.destroy');
});

// Manajemen Cabang
Route::middleware(['auth'])->group(function () {
    Route::get('/cabang', [CabangController::class, 'index'])->middleware('permission:manage-cabang')->name('cabangs.index');
    Route::get('/cabang/create', [CabangController::class, 'create'])->middleware('permission:edit-cabang')->name('cabangs.create');
    Route::post('/cabang', [CabangController::class, 'store'])->middleware('permission:add-cabang')->name('cabangs.store');
    Route::get('/cabang/{cabang}/edit', [CabangController::class, 'edit'])->middleware('permission:edit-cabang')->name('cabangs.edit');
    Route::put('/cabang/{cabang}', [CabangController::class, 'update'])->middleware('permission:edit-cabang')->name('cabangs.update');
    Route::delete('/cabang/{cabang}', [CabangController::class, 'destroy'])->middleware('permission:delete-cabang')->name('cabangs.destroy');
});

// Manajemen Roles
Route::middleware(['auth'])->group(function () {
    Route::get('/roles', [RoleController::class, 'index'])->middleware('permission:manage-roles')->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->middleware('permission:edit-roles')->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->middleware('permission:add-roles')->name('roles.store');
    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->middleware('permission:edit-roles')->name('roles.edit');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->middleware('permission:edit-roles')->name('roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->middleware('permission:delete-roles')->name('roles.destroy');
});

//profile di header
Route::middleware('auth')->group(function () {
    Route::get('/profile', function () {
        return view('layouts.profile');
    })->name('profile');
});

// supplier
Route::middleware(['auth'])->group(function () {
    Route::get('/supplier', [SupplierController::class, 'index'])->middleware('permission:manage-supplier')->name('managesupplierindex');
    Route::get('/supplier/loadsupplier', [SupplierController::class, 'loadsupplier'])->middleware('permission:manage-supplier')->name('loadsupplier');
    Route::post('/supplier/postsupplier', [SupplierController::class, 'store'])->middleware('permission:add-supplier')->name('storesupplier');
    Route::post('/supplier/updatesupplier', [SupplierController::class, 'update'])->middleware('permission:edit-supplier')->name('updatesupplier');
    Route::post('/supplier/deletesupplier', [SupplierController::class, 'destroy'])->middleware('permission:delete-supplier')->name('deletesupplier');
});


// Jenis Pelanggan
Route::middleware('auth')->group(function () {
    Route::get('/jenispelanggan', [JenisPelanggansController::class, 'index'])->middleware('permission:manage-jenispelanggan')->name('jenispelanggan.index');
    Route::get('/jenispelanggan/loadjenispelanggan', [JenisPelanggansController::class, 'loadjenispelanggan'])->middleware('permission:manage-jenispelanggan')->name('jenispelanggan.load');
    Route::post('/jenispelanggan/store', [JenisPelanggansController::class, 'store'])->middleware('permission:add-jenispelanggan')->name('jenispelanggan.store');
    Route::put('/jenispelanggan/update', [JenisPelanggansController::class, 'update'])->middleware('permission:edit-jenispelanggan')->name('jenispelanggan.update');
    Route::delete('/jenispelanggan/delete', [JenisPelanggansController::class, 'destroy'])->middleware('permission:delete-jenispelanggan')->name('jenispelanggan.destroy');
    Route::get('/jenispelanggan/cari', [JenisPelanggansController::class, 'jenispelanggancari'])->middleware('permission:manage-jenispelanggan')->name('jenispelanggan.cari');
});

// Kategori
Route::middleware(['auth'])->group(function () {
    Route::get('/kategori', [KategoriController::class, 'index'])->middleware('permission:manage-kategori')->name('managekategoriindex');
    Route::get('/kategori/loadkategori', [KategoriController::class, 'loadkategori'])->middleware('permission:manage-kategori')->name('loadkategori');
    Route::post('/kategori/postkategori', [KategoriController::class, 'store'])->middleware('permission:add-kategori')->name('storekategori');
    Route::post('/kategori/updatekategori', [KategoriController::class, 'update'])->middleware('permission:edit-kategori')->name('updatekategori');
    Route::post('/kategori/deletekategori', [KategoriController::class, 'destroy'])->middleware('permission:delete-kategori')->name('deletekategori');
});

// produk
Route::middleware(['auth'])->group(function () {
    Route::get('/produk', [ProdukController::class, 'index'])->middleware('permission:manage-produk')->name('manageprodukindex');
    Route::get('/produk/loadproduk', [ProdukController::class, 'loadproduk'])->middleware('permission:manage-produk')->name('loadproduk');
    Route::post('/produk/postproduk', [ProdukController::class, 'store'])->middleware('permission:add-produk')->name('storeproduk');
    Route::post('/produk/updateproduk', [ProdukController::class, 'update'])->middleware('permission:edit-produk')->name('updateproduk');
    Route::post('/produk/deleteproduk', [ProdukController::class, 'destroy'])->middleware('permission:delete-produk')->name('deleteproduk');
});

// Pelanggan
Route::middleware('auth')->group(function () {
    Route::get('/pelanggan', [PelanggansController::class, 'index'])->middleware('permission:manage-pelanggan')->name('pelanggan.index');
    Route::get('/pelanggan/data', [PelanggansController::class, 'getData'])->middleware('permission:manage-pelanggan')->name('pelanggan.data');
    Route::get('/pelanggan/{id}/detail', [PelanggansController::class, 'show'])->middleware('permission:manage-pelanggan')->name('pelanggan.show');
    Route::post('/pelanggan/store', [PelanggansController::class, 'store'])->middleware('permission:add-pelanggan')->name('pelanggan.store');
    Route::post('/pelanggan/update', [PelanggansController::class, 'update'])->middleware('permission:edit-pelanggan')->name('pelanggan.update');
    Route::post('/pelanggan/destroy', [PelanggansController::class, 'destroy'])->middleware('permission:delete-pelanggan')->name('pelanggan.destroy');
});

// Bahan Baku
Route::middleware(['auth'])->group(function () {
    Route::get('/bahanbaku', [BahanBakuController::class, 'index'])->middleware('permission:manage-bahanbaku')->name('managebahanbakuindex');
    Route::get('/bahanbaku/loadbahanbaku', [BahanBakuController::class, 'loadbahanbaku'])->middleware('permission:manage-bahanbaku')->name('bahanbaku.load');
    Route::post('/bahanbaku/postbahanbaku', [BahanBakuController::class, 'store'])->middleware('permission:add-bahanbaku')->name('storebahanbaku');
    Route::post('/bahanbaku/updatebahanbaku', [BahanBakuController::class, 'update'])->middleware('permission:edit-bahanbaku')->name('updatebahanbaku');
    Route::post('/bahanbaku/deletebahanbaku', [BahanBakuController::class, 'destroy'])->middleware('permission:delete-bahanbaku')->name('deletebahanbaku');
});

// Relasi Bahan Baku
Route::middleware(['auth'])->group(function () {
    Route::get('/relasibahanbaku', [RelasiBahanBakuController::class, 'index'])->middleware('permission:manage-relasibahanbaku')->name('managerelasibahanbakuindex');
    Route::get('/relasibahanbaku/load', [RelasiBahanBakuController::class, 'loadrelasibahanbaku'])->middleware('permission:manage-relasibahanbaku')->name('loadrelasibahanbaku');
    Route::post('/relasibahanbaku/store', [RelasiBahanBakuController::class, 'store'])->middleware('permission:add-relasibahanbaku')->name('storerelasibahanbaku');
    Route::post('/relasibahanbaku/update', [RelasiBahanBakuController::class, 'update'])->middleware('permission:edit-relasibahanbaku')->name('updaterelasibahanbaku');
    Route::post('/relasibahanbaku/delete', [RelasiBahanBakuController::class, 'destroy'])->middleware('permission:delete-relasibahanbaku')->name('deleterelasibahanbaku');
});

// Stok Bahan Baku
Route::middleware(['auth'])->group(function () {
    Route::get('/stokbahanbaku', [StokBahanBakusController::class, 'index'])->middleware('permission:manage-stokbahanbaku')->name('stokbahanbaku.index');
    Route::get('/stokbahanbaku/create', [StokBahanBakusController::class, 'create'])->middleware('permission:add-stokbahanbaku')->name('stokbahanbaku.create');
    Route::post('/stokbahanbaku', [StokBahanBakusController::class, 'store'])->middleware('permission:add-stokbahanbaku')->name('stokbahanbaku.store');
    Route::get('/stokbahanbaku/{id}/edit', [StokBahanBakusController::class, 'edit'])->middleware('permission:edit-stokbahanbaku')->name('stokbahanbaku.edit');
    Route::put('/stokbahanbaku/{id}', [StokBahanBakusController::class, 'update'])->middleware('permission:edit-stokbahanbaku')->name('stokbahanbaku.update');
    Route::delete('/stokbahanbaku', [StokBahanBakusController::class, 'destroy'])->middleware('permission:delete-stokbahanbaku')->name('stokbahanbaku.destroy');
});

// Transaksi Stok Bahan Baku
Route::middleware(['auth',])->group(function () {
    Route::get('/transaksi/bahan', [TransaksiBahanBakusController::class, 'index'])->middleware('permission:manage-transaksistokbahanbaku')->name('transaksibahanbaku.index');
    Route::get('/transaksi/bahan/create', [TransaksiBahanBakusController::class, 'create'])->middleware('permission:add-transaksistokbahanbaku')->name('transaksibahanbaku.create');
    Route::post('/transaksi/bahan/store', [TransaksiBahanBakusController::class, 'store'])->middleware('permission:add-transaksistokbahanbaku')->name('transaksibahanbaku.store');
    Route::get('/transaksi/bahan/edit/{id}', [TransaksiBahanBakusController::class, 'show'])->name('transaksibahanbaku.edit');
    Route::put('/transaksi/bahan/update/{id}', [TransaksiBahanBakusController::class, 'update'])->name('transaksibahanbaku.update');
    Route::get('/transaksi/bahan/delete/{id}', [TransaksiBahanBakusController::class, 'destroy'])->name('transaksibahanbaku.destroy');
    Route::get('/transaksi/bahan/deleted', [TransaksiBahanBakusController::class, 'indexdeleted'])->name('transaksibahanbaku.deleted');
    Route::get('/ajax/load-bahanbaku', [TransaksiBahanBakusController::class, 'loadBahanBaku'])->name('loadbahanbaku');
});

// Transaksi Penjualan
Route::middleware(['auth'])->group(function () {
    Route::get('/transaksi', [TransaksiPenjualansController::class, 'index'])->middleware('permission:manage-transaksipenjualan')->name('transaksiindex');
    Route::get('/transaksideleted', [TransaksiPenjualansController::class, 'indexdeleted'])->middleware('permission:manage-transaksipenjualan')->name('transaksiindexdeleted');
    Route::get('/transaksi/penjualan', [TransaksiPenjualansController::class, 'transaksi'])->middleware('permission:deleted-transaksipenjualan')->name('addtransaksiindex');
    Route::get('/transaksi/penjualan/load', [TransaksiPenjualansController::class, 'load'])->middleware('permission:manage-transaksipenjualan')->name('loadtransaksipenjualan');
    Route::post('/transaksi/penjualan/store', [TransaksiPenjualansController::class, 'store'])->middleware('permission:add-transaksipenjualan')->name('storetransaksipenjualan');
    Route::post('/transaksi/penjualan/update', [TransaksiPenjualansController::class, 'update'])->middleware('permission:edit-transaksipenjualan')->name('updatetransaksipenjualan');
    Route::delete('/transaksi/penjualan/delete/{id}', [TransaksiPenjualansController::class, 'destroy'])->middleware('permission:delete-transaksipenjualan')->name('destroytransaksipenjualan');
    Route::get('/transaksi-penjualan/show-sub', [TransaksiPenjualansController::class, 'showSubTransaksi'])->middleware('permission:manage-transaksipenjualan')->name('showsubtransaksi');
    Route::get('/transaksi/report/{id}', [TransaksiPenjualansController::class, 'report'])->middleware('permission:manage-transaksipenjualan')->name('transaksi.report');
});

// Manajemen Designer
Route::middleware(['auth'])->group(function () {
    Route::get('/designer', [DesignerController::class, 'index'])->name('designerindex');
    Route::get('/designer/load', [DesignerController::class, 'load'])->name('loaddesigner');
    Route::post('/designer/store', [DesignerController::class, 'store'])->name('storedesigner');
    Route::post('/designer/update', [DesignerController::class, 'update'])->name('updatedesigner');
    Route::delete('/designer/delete', [DesignerController::class, 'destroy'])->name('destroydesigner');
});

// Manajemen Operator
Route::middleware(['auth'])->group(function () {
    Route::get('/operator', [OperatorController::class, 'index'])->name('operatorindex');
    Route::get('/operator/load', [OperatorController::class, 'load'])->name('loadoperator');
    Route::post('/operator/store', [OperatorController::class, 'store'])->name('storeoperator');
    Route::post('/operator/update', [OperatorController::class, 'update'])->name('updateoperator');
    Route::delete('/operator/delete', [OperatorController::class, 'destroy'])->name('destroyoperator');
});
