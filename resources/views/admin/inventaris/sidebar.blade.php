<!--**********************************
    Sidebar start
***********************************-->
<div class="quixnav">
    <div class="quixnav-scroll">
        <ul class="metismenu" id="menu">

            {{-- ====================== MAIN MENU ====================== --}}
            <li class="nav-label first">MAIN MENU</li>

            @php
                $user = auth()->user();
            @endphp

            <li>
                <a href="{{ $user->hasRole('Inventory Utama')
                    ? route('gudangpusat.dashboard')
                    : route('templateinventaris.dashboard') }}">
                    <i class="icon icon-single-04"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>

            {{-- ===================== CABANG & INVENTARIS ===================== --}}
            <li class="nav-label mt-3">CABANG & INVENTARIS</li>

            @php
                $nonaktif = config('cabang_nonaktif.ids', []);

                // Inventory Cabang → hanya cabangnya
                if ($user->hasRole('Inventory Cabang')) {
                    $cabangs = \App\Models\Cabang::where('id', $user->cabang_id)
                        ->where('jenis', 'cabang')
                        ->get();
                }
                // Inventory Utama → semua cabang
                else {
                    $cabangs = \App\Models\Cabang::where('jenis', 'cabang')->get();
                }
            @endphp

            {{-- Manajemen Cabang (Inventory Utama saja) --}}
            @if($user->hasRole('Inventory Utama'))
            <li>
                <a href="{{ route('gudangpusat.cabang.index') }}">
                    <i class="icon icon-settings"></i>
                    <span class="nav-text">Manajemen Cabang</span>
                </a>
            </li>
            @endif

            {{-- List Cabang --}}
            @foreach($cabangs as $c)
                @if(!in_array($c->id, $nonaktif))
                <li>
                    <a class="has-arrow" href="javascript:void(0)">
                        <i class="icon icon-home"></i>
                        <span class="nav-text">{{ $c->nama }}</span>
                    </a>
                    <ul>
                        <li><a href="{{ route('cabang.barang.cabang', $c->slug) }}">Daftar Barang</a></li>
                        <li><a href="{{ route('cabang.stok.cabang', $c->slug) }}">Stok Tersedia</a></li>
                        <li><a href="{{ route('cabang.riwayat.cabang', $c->slug) }}">Riwayat Pengiriman</a></li>
                        <li><a href="{{ route('cabang.inventaris.cabang', $c->slug) }}">Inventaris Kantor</a></li>
                    </ul>
                </li>
                @endif
            @endforeach

            {{-- ===================== GUDANG PUSAT ===================== --}}
            @if($user->hasRole('Inventory Utama|owner'))
            <li class="nav-label mt-4">GUDANG PUSAT</li>

            <li>
                <a class="has-arrow" href="javascript:void(0)">
                    <i class="icon icon-folder"></i>
                    <span class="nav-text">Gudang Pusat</span>
                </a>
                <ul>
                    <li><a href="{{ route('barang.pusat') }}">Daftar Barang</a></li>
                    <li><a href="{{ route('stok.pusat') }}">Stok Tersedia</a></li>
                    <li><a href="{{ route('pengiriman.pusat.index') }}">Pengiriman Barang</a></li>
                </ul>
            </li>
            @endif

        </ul>
    </div>
</div>
<!--**********************************
    Sidebar end
***********************************-->


{{-- ====================== FORCE METISMENU INIT ====================== --}}
<script>
document.addEventListener("DOMContentLoaded", function () {
    if (typeof $ !== 'undefined' && $('#menu').length) {
        $('#menu').metisMenu();
    }
});
</script>
