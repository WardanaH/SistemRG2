<!--**********************************
    Sidebar start
***********************************-->
<div class="quixnav">
    <div class="quixnav-scroll">
        <ul class="metismenu" id="menu">

            <li class="nav-label first">MAIN MENU</li>
            <li>
                <a href="{{ route('dashboard') }}">
                    <i class="icon icon-single-04"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>

            <li class="nav-label">MANAJEMEN UTAMA</li>

            <li>
                <a class="has-arrow" href="javascript:void(0)" aria-expanded="false">
                    <i class="icon icon-people"></i>
                    <span class="nav-text">User & Akses</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('users.index') }}">Manajemen User</a></li>
                    <li><a href="{{ route('roles.index') }}">Hak Akses</a></li>
                </ul>
            </li>

            <li>
                <a class="has-arrow" href="javascript:void(0)" aria-expanded="false">
                    <i class="icon icon-home"></i>
                    <span class="nav-text">Cabang & Supplier</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('cabangs.index') }}">Manajemen Cabang</a></li>
                    <li><a href="{{ route('managesupplierindex') }}">Manajemen Supplier</a></li>
                </ul>
            </li>

            <li>
                <a class="has-arrow" href="javascript:void(0)" aria-expanded="false">
                    <i class="icon icon-bag"></i>
                    <span class="nav-text">Produk & Kategori</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('managekategoriindex') }}">Kategori Produk</a></li>
                    <li><a href="{{ route('manageprodukindex') }}">Manajemen Produk</a></li>
                </ul>
            </li>

            <li>
                <a class="has-arrow" href="javascript:void(0)" aria-expanded="false">
                    <i class="icon icon-layers"></i>
                    <span class="nav-text">Bahan Baku</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('managebahanbakuindex') }}">Daftar Bahan Baku</a></li>
                    <li><a href="{{ route('managerelasibahanbakuindex') }}">Relasi Bahan Baku</a></li>
                    <li><a href="{{ route('stokbahanbaku.index') }}">Stok Bahan Baku</a></li>
                    <li><a href="{{ route('transaksibahanbaku.index') }}">Transaksi Bahan Baku</a></li>
                </ul>
            </li>

            <li>
                <a class="has-arrow" href="javascript:void(0)" aria-expanded="false">
                    <i class="icon icon-credit-card"></i>
                    <span class="nav-text">Transaksi</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('addtransaksiindex') }}">Tambah Transaksi</a></li>
                    <li><a href="{{ route('transaksiindex') }}">Manajemen Penjualan</a></li>
                </ul>
            </li>

            <li>
                <a class="has-arrow" href="javascript:void(0)" aria-expanded="false">
                    <i class="icon icon-users-mm"></i>
                    <span class="nav-text">Pelanggan</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('jenispelanggan.index') }}">Jenis Pelanggan</a></li>
                    <li><a href="{{ route('pelanggan.index') }}">Daftar Pelanggan</a></li>
                </ul>
            </li>

            <li class="nav-label">SYSTEM</li>
            <li>
                <form action="{{ route('logout') }}" method="POST" class="px-3 mt-2">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm w-100">
                        <i class="icon icon-logout"></i> Logout
                    </button>
                </form>
            </li>

        </ul>
    </div>
</div>
<!--**********************************
    Sidebar end
***********************************-->
