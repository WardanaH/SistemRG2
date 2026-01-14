<!--**********************************
    Sidebar start
***********************************-->
<div class="quixnav">
    <div class="quixnav-scroll">
        <ul class="metismenu" id="menu">

            {{-- ====================== MAIN MENU ====================== --}}
            <li class="nav-label first">MAIN MENU</li>

            <li>
                <a href="{{ route('dashboard') }}">
                    <i class="la la-home"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>

            {{-- ====================== MANAJEMEN UTAMA ====================== --}}
            <li class="nav-label">MANAJEMEN UTAMA</li>

            @can('manage-transaksipenjualan')
            <li>
                <a class="has-arrow" href="javascript:void(0)" aria-expanded="false">
                    <i class="fa fa-credit-card"></i>
                    <span class="nav-text">Transaksi</span>
                </a>
                <ul aria-expanded="false">
                    @can('add-transaksipenjualan')
                    <li><a href="{{ route('addtransaksiindex') }}">Tambah Transaksi Penjualan</a></li>
                    @endcan
                    @can('manage-transaksipenjualan')
                    <li><a href="{{ route('transaksiindex') }}">Manajemen Transaksi Penjualan</a></li>
                    @endcan
                    @can('deleted-transaksipenjualan')
                    <li><a href="{{ route('transaksiindexdeleted') }}">Manajemen Transaksi Penjualan Terhapus</a></li>
                    @endcan

                    @can('manage-angsuranpenjualan')
                    <li><a href="{{ route('angsuran.index') }}">Manajemen Angsuran Transaksi</a></li>
                    @endcan
                    @can('manage-angsuranpenjualan')
                    <li><a href="{{ route('angsuran.indexdeleted') }}">Manajemen Angsuran Transaksi Terhapus</a></li>
                    @endcan
                </ul>
            </li>
            @endcan

            @can('manage-transaksipenjualan')
            <li>
                <a class="has-arrow" href="javascript:void(0)" aria-expanded="false">
                    <i class="fa fa-handshake-o"></i>
                    <span class="nav-text">Bantuan</span>
                </a>
                <ul aria-expanded="false">
                    @can('manage-transaksipenjualan')
                    <li><a href="{{ route('bantuan') }}">Minta Bantuan Transaksi Penjualan</a></li>
                    @endcan
                    @can('manage-transaksipenjualan')
                    <li><a href="{{ route('bantuan.list') }}">Manajemen Bantuan Transaksi Penjualan</a></li>
                    @endcan
                    @can('manage-transaksipenjualan')
                    <li><a href="{{ route('bantuan.index') }}">Permintaan Bantuan Transaksi Penjualan</a></li>
                    @endcan
                    @can('manage-transaksipenjualan')
                    <li><a href="{{ route('bantuan.riwayat_bantuan_masuk') }}">Riwayat Bantuan Transaksi Penjualan Masuk</a></li>
                    @endcan
                    @can('manage-transaksipenjualan')
                    <li><a href="{{ route('bantuan.riwayat_bantuan_keluar') }}">Riwayat Bantuan Transaksi Penjualan Keluar</a></li>
                    @endcan
                </ul>
            </li>
            @endcan

            @can('manage-produk')
            <li>
                <a class="has-arrow" href="javascript:void(0)" aria-expanded="false">
                    <i class="la la-archive"></i>
                    <span class="nav-text">Produk & Kategori</span>
                </a>
                <ul aria-expanded="false">
                    @can('manage-kategori')
                    <li><a href="{{ route('managekategoriindex') }}">Kategori Produk</a></li>
                    @endcan
                    @can('manage-produk')
                    <li><a href="{{ route('manageprodukindex') }}">Manajemen Produk</a></li>
                    @endcan
                </ul>
            </li>
            @endcan

            @can('manage-bahanbaku')
            <li>
                <a class="has-arrow" href="javascript:void(0)" aria-expanded="false">
                    <i class="la la-cubes"></i>
                    <span class="nav-text">Bahan Baku</span>
                </a>
                <ul aria-expanded="false">
                    @can('manage-bahanbaku')
                    <li><a href="{{ route('managebahanbakuindex') }}">Daftar Bahan Baku</a></li>
                    @endcan
                    @can('manage-relasibahanbaku')
                    <li><a href="{{ route('managerelasibahanbakuindex') }}">Relasi Bahan Baku</a></li>
                    @endcan
                    @can('list-stokbahanbaku')
                    <li><a href="{{ route('stokbahanbaku.index') }}">Stok Bahan Baku</a></li>
                    @endcan
                    @can('manage-transaksistokbahanbaku')
                    <li><a href="{{ route('transaksibahanbaku.index') }}">Transaksi Bahan Baku</a></li>
                    @endcan
                </ul>
            </li>
            @endcan

            @can('manage-pelanggan')
            <li>
                <a class="has-arrow" href="javascript:void(0)" aria-expanded="false">
                    <i class="la la-users"></i>
                    <span class="nav-text">Pelanggan</span>
                </a>
                <ul aria-expanded="false">
                    @can('manage-jenispelanggan')
                    <li><a href="{{ route('jenispelanggan.index') }}">Jenis Pelanggan</a></li>
                    @endcan
                    @can('manage-pelanggan')
                    <li><a href="{{ route('pelanggan.index') }}">Daftar Pelanggan</a></li>
                    @endcan
                    <li><a href="{{ route('specialprice.index') }}">Manajemen Harga Khusus</a></li>
                    <li><a href="{{ route('specialpricegroup.index') }}">Manajemen Harga Khusus Group</a></li>
                    <li><a href="{{ route('rangepricepelanggan.page') }}">Harga Khusus Customer</a></li>
                </ul>
            </li>
            @endcan

            @can('manage-cabang')
            <li>
                <a class="has-arrow" href="javascript:void(0)" aria-expanded="false">
                    <i class="la la-map-marker"></i>
                    <span class="nav-text">Cabang & Supplier</span>
                </a>
                <ul aria-expanded="false">
                    @can('manage-cabang')
                    <li><a href="{{ route('cabangs.index') }}">Manajemen Cabang</a></li>
                    @endcan
                    @can('manage-supplier')
                    <li><a href="{{ route('managesupplierindex') }}">Manajemen Supplier</a></li>
                    @endcan
                </ul>
            </li>
            @endcan

            @can('manage-users')
            <li>
                <a class="has-arrow" href="javascript:void(0)" aria-expanded="false">
                    <i class="la la-key"></i>
                    <span class="nav-text">User & Akses</span>
                </a>
                <ul aria-expanded="false">
                    @can('manage-users')
                    <li><a href="{{ route('users.index') }}">Manajemen User</a></li>
                    @endcan
                    @can('manage-roles')
                    <li><a href="{{ route('roles.index') }}">Hak Akses</a></li>
                    @endcan
                    <li><a href="{{ route('designerindex') }}">Daftar Designer</a></li>
                    <li><a href="{{ route('operatorindex') }}">Daftar Operator</a></li>
                </ul>
            </li>
            @endcan

            @can('manage-proyek')
            <li>
                <a href="{{ route('companies.index') }}">
                    <i class="la la-briefcase"></i>
                    <span class="nav-text">Manajemen Proyek</span>
                </a>
            </li>
            @endcan

            @can('manage-gudang')
            <li>
                <a href="{{ route('gudangpusat.dashboard') }}">
                    <i class="la la-cubes"></i>
                    <span class="nav-text">Manajemen Inventaris</span>
                </a>
            </li>
            @endcan

        </ul>
    </div>
</div>
<!--**********************************
    Sidebar end
***********************************-->
