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
                    <i class="icon icon-single-04"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>

            {{-- ====================== MANAJEMEN UTAMA ====================== --}}
            <li class="nav-label">MANAJEMEN UTAMA</li>

            <li>
                <a class="has-arrow" href="javascript:void(0)">
                    <i class="icon icon-people"></i>
                    <span class="nav-text">User & Akses</span>
                </a>
                <ul>
                    <li><a href="{{ route('users.index') }}">Manajemen User</a></li>
                    <li><a href="{{ route('roles.index') }}">Hak Akses</a></li>
                    <li><a href="{{ route('designerindex') }}">Daftar Designer</a></li>
                    <li><a href="{{ route('operatorindex') }}">Daftar Operator</a></li>
                </ul>
            </li>

            <li>
                <a class="has-arrow" href="javascript:void(0)">
                    <i class="icon icon-home"></i>
                    <span class="nav-text">Cabang & Supplier</span>
                </a>
                <ul>
                    <li><a href="{{ route('cabangs.index') }}">Manajemen Cabang</a></li>
                    <li><a href="{{ route('managesupplierindex') }}">Manajemen Supplier</a></li>
                </ul>
            </li>

            <li>
                <a class="has-arrow" href="javascript:void(0)">
                    <i class="icon icon-bag"></i>
                    <span class="nav-text">Produk & Kategori</span>
                </a>
                <ul>
                    <li><a href="{{ route('managekategoriindex') }}">Kategori Produk</a></li>
                    <li><a href="{{ route('manageprodukindex') }}">Manajemen Produk</a></li>
                </ul>
            </li>

            <li>
                <a class="has-arrow" href="javascript:void(0)">
                    <i class="icon icon-layers"></i>
                    <span class="nav-text">Bahan Baku</span>
                </a>
                <ul>
                    <li><a href="{{ route('managebahanbakuindex') }}">Daftar Bahan Baku</a></li>
                    <li><a href="{{ route('managerelasibahanbakuindex') }}">Relasi Bahan Baku</a></li>
                    <li><a href="{{ route('stokbahanbaku.index') }}">Stok Bahan Baku</a></li>
                    <li><a href="{{ route('transaksibahanbaku.index') }}">Transaksi Bahan Baku</a></li>
                </ul>
            </li>

            <li>
                <a class="has-arrow" href="javascript:void(0)">
                    <i class="icon icon-credit-card"></i>
                    <span class="nav-text">Transaksi</span>
                </a>
                <ul>
                    <li><a href="{{ route('addtransaksiindex') }}">Tambah Transaksi</a></li>
                    <li><a href="{{ route('transaksiindex') }}">Manajemen Penjualan</a></li>
                </ul>
            </li>

            <li>
                <a class="has-arrow" href="javascript:void(0)">
                    <i class="icon icon-users-mm"></i>
                    <span class="nav-text">Pelanggan</span>
                </a>
                <ul>
                    <li><a href="{{ route('jenispelanggan.index') }}">Jenis Pelanggan</a></li>
                    <li><a href="{{ route('pelanggan.index') }}">Daftar Pelanggan</a></li>
                </ul>
            </li>

            {{-- ===================== CABANG & INVENTARIS ===================== --}}
            <li class="nav-label mt-3">CABANG & INVENTARIS</li>

            <li>
                <a href="{{ route('inventaris.cabang.index') }}">
                    <i class="icon icon-settings"></i>
                    <span class="nav-text">Manajemen Cabang</span>
                </a>
            </li>

            @php
                // CABANG BENERAN (bukan gudang)
                $cabangs = App\Models\Cabang::where('jenis', 'cabang')->get();
            @endphp

            @foreach($cabangs as $c)
                <li>
                    <a class="has-arrow" href="javascript:void(0)">
                        <i class="icon icon-home"></i>
                        <span class="nav-text">{{ $c->nama }}</span>
                    </a>
                    <ul>
                        <li><a href="{{ url('cabang/'.$c->slug.'/barang') }}">Daftar Barang</a></li>
                        <li><a href="{{ url('cabang/'.$c->slug.'/stok') }}">Stok Tersedia</a></li>
                        <li><a href="{{ url('cabang/'.$c->slug.'/riwayat') }}">Riwayat Pengiriman</a></li>
                        <li><a href="{{ url('cabang/'.$c->slug.'/inventaris') }}">Inventaris Kantor</a></li>
                    </ul>
                </li>
            @endforeach

            {{-- ===================== GUDANG PUSAT ===================== --}}
            <li class="nav-label mt-4">GUDANG PUSAT</li>

            <li>
                <a class="has-arrow" href="javascript:void(0)">
                    <i class="icon icon-folder"></i>
                    <span class="nav-text">Gudang Pusat</span>
                </a>
                <ul>
                    <li><a href="{{ route('gudangpusat.barang') }}">Daftar Barang</a></li>
                    <li><a href="{{ route('gudangpusat.stok') }}">Stok Tersedia</a></li>
                    <li><a href="{{ route('gudangpusat.pengiriman.index') }}">Pengiriman Barang</a></li>
                </ul>
            </li>

        </ul>
    </div>
</div>
<!--**********************************
    Sidebar end
***********************************-->
