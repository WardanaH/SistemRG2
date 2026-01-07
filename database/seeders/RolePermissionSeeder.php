<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        /**
         * ============================
         *  FULL LIST 93 PERMISSIONS
         * ============================
         */
        $permissions = [

            // Dashboard
            'index-home',

            // Users & Roles
            'manage-users','add-users','edit-users','delete-users',
            'manage-roles','add-roles','edit-roles','delete-roles',

            // Transaksi Penjualan
            'manage-transaksipenjualan','add-transaksipenjualan','edit-transaksipenjualan','delete-transaksipenjualan',
            'deleted-transaksipenjualan',

            // Angsuran Penjualan
            'manage-angsuranpenjualan','list-angsuranpenjualan','add-angsuranpenjualan','delete-angsuranpenjualan',
            'report-angsuranpenjualan','report-angsuranpenjualandetail','deleted-angsuranpenjualan',

            // Transaksi Pengeluaran
            'manage-transaksipengeluaran','add-transaksipengeluaran','edit-transaksipengeluaran','delete-transaksipengeluaran',
            'deleted-transaksipengeluaran','report-transaksipengeluaran',

            // Angsuran Pengeluaran
            'manage-angsuranpengeluaran','list-angsuranpengeluaran','add-angsuranpengeluaran','delete-angsuranpengeluaran',
            'report-angsuranpengeluaran','report-angsuranpengeluarandetail','deleted-angsuranpengeluaran',

            // Cabang
            'manage-cabang','add-cabang','edit-cabang','delete-cabang',

            // Pelanggan
            'manage-pelanggan','add-pelanggan','edit-pelanggan','delete-pelanggan',

            // Jenis Pelanggan
            'manage-jenispelanggan','add-jenispelanggan','edit-jenispelanggan','delete-jenispelanggan',

            // Jenis Pengeluaran
            'manage-jenispengeluaran', 'add-jenispengeluaran', 'edit-jenispengeluaran', 'delete-jenispengeluaran',

            // Supplier
            'manage-supplier','add-supplier','edit-supplier','delete-supplier',

            // Produk
            'manage-produk','add-produk','edit-produk','delete-produk',

            // Kategori
            'manage-kategori','add-kategori','edit-kategori','delete-kategori',

            // Special Price
            'manage-specialprice','manage-specialprice-many-customer','add-specialprice',
            'edit-specialprice','delete-specialprice',

            // Special Price Group
            'manage-specialpricegroup','add-specialpricegroup','edit-specialpricegroup','delete-specialpricegroup',

            // Menu
            'manage-menu','add-menu','edit-menu','delete-menu',

            // Nama Menu
            'manage-menuname','edit-menuname',

            // Bahan Baku
            'manage-bahanbaku','add-bahanbaku','edit-bahanbaku','delete-bahanbaku',

            // Aturan Bahan Baku
            'manage-relasibahanbaku','add-relasibahanbaku','edit-relasibahanbaku','delete-relasibahanbaku',

            // Stok Bahan Baku
            'list-stokbahanbaku', 'add-stokbahanbaku', 'edit-stokbahanbaku', 'delete-stokbahanbaku',

            // Manajemen Transaksi Stok Bahan Baku
            'manage-transaksistokbahanbaku','add-transaksistokbahanbaku','edit-transaksistokbahanbaku',
            'delete-transaksistokbahanbaku','deleted-transaksistokbahanbaku',

            // Laporan & Timeline
            'view-laporan',
            'index-timeline',

            // Manajemen Gudang
            'manage-gudang','add-gudang','edit-gudang','delete-gudang',

            // Manajemen Proyek
            'manage-proyek','add-proyek','edit-proyek','delete-proyek',
        ];

        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p]);
        }

        /**
         * ================
         *  ROLES
         * ================
         */
        $owner      = Role::firstOrCreate(['name' => 'owner']);
        $direktur   = Role::firstOrCreate(['name' => 'direktur']);
        $manajemen  = Role::firstOrCreate(['name' => 'manajemen']);
        $supervisor = Role::firstOrCreate(['name' => 'supervisor']);
        $admin      = Role::firstOrCreate(['name' => 'admin']);

        $all = Permission::all();

        /**
         * ============================
         *  OWNER / DIREKTUR / MANAJEMEN
         *  → FULL ACCESS
         * ============================
         */
        $owner->syncPermissions($all);
        $direktur->syncPermissions($all);
        $manajemen->syncPermissions($all);

        /**
         * ============================
         * SUPERVISOR PERMISSIONS
         * (sesuai narasi kamu)
         * ============================
         */
        $supervisorPermissions = [
            'index-home',
            'manage-users','add-users','edit-users','delete-users',
            'edit-roles','delete-roles',

            'add-transaksipenjualan','edit-transaksipenjualan','manage-transaksipenjualan',
            'deleted-transaksipenjualan',

            'manage-angsuranpenjualan','add-angsuranpenjualan',
            'report-angsuranpenjualan','report-angsuranpenjualandetail','deleted-angsuranpenjualan',

            'manage-transaksipengeluaran','add-transaksipengeluaran','edit-transaksipengeluaran',
            'delete-transaksipengeluaran','deleted-transaksipengeluaran','report-transaksipengeluaran',

            'manage-angsuranpengeluaran','add-angsuranpengeluaran','delete-angsuranpengeluaran',
            'report-angsuranpengeluaran','report-angsuranpengeluarandetail','deleted-angsuranpengeluaran',

            'manage-jenispengeluaran','add-jenispengeluaran','edit-jenispengeluaran','delete-jenispengeluaran',

            'manage-pelanggan','add-pelanggan','edit-pelanggan','delete-pelanggan',

            'edit-jenispelanggan','delete-jenispelanggan',

            'manage-produk','add-produk','edit-produk',

            'add-kategori','edit-kategori',

            'manage-specialprice','add-specialprice','edit-specialprice','delete-specialprice',

            'add-specialpricegroup','edit-specialpricegroup','delete-specialpricegroup',

            'view-laporan',
        ];

        $supervisor->syncPermissions($supervisorPermissions);

        /**
         * ============================
         * ADMIN PERMISSIONS
         * (sesuai narasi kamu)
         * ============================
         */
        $adminPermissions = [
            'index-home',
            'edit-roles',

            'add-transaksipenjualan','edit-transaksipenjualan','manage-transaksipenjualan',
            'deleted-transaksipenjualan',

            'manage-angsuranpenjualan','add-angsuranpenjualan',
            'report-angsuranpenjualan','report-angsuranpenjualandetail','deleted-angsuranpenjualan',

            'manage-transaksipengeluaran','add-transaksipengeluaran','edit-transaksipengeluaran',
            'delete-transaksipengeluaran','deleted-transaksipengeluaran','report-transaksipengeluaran',

            'manage-angsuranpengeluaran','add-angsuranpengeluaran','delete-angsuranpengeluaran',
            'report-angsuranpengeluaran','report-angsuranpengeluarandetail','deleted-angsuranpengeluaran',

            'manage-jenispengeluaran','add-jenispengeluaran','edit-jenispengeluaran',

            'manage-pelanggan','add-pelanggan',

            'manage-produk','add-produk',

            'view-laporan',
        ];

        $admin->syncPermissions($adminPermissions);
    }
}
