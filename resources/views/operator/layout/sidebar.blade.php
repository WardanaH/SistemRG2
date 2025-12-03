<!--**********************************
    Sidebar start
***********************************-->
<div class="quixnav">
    <div class="quixnav-scroll">
        <ul class="metismenu" id="menu">

            <li class="nav-label first">MAIN MENU</li>
            <li>
                <a href="{{ route('operator.dashboard') }}">
                    <i class="icon icon-single-04"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>

            <li>
                <a class="has-arrow" href="javascript:void(0)" aria-expanded="false">
                    <i class="fa fa-cogs"></i>
                    <span class="nav-text">Panel Operator</span>
                </a>
                <ul aria-expanded="false">

                    <li>
                        <a href="{{ route('operator.pesanan') }}">Status Item Pesanan</a>
                    </li>
                    <li>
                        <a href="{{ route('operator.riwayat') }}">Riwayat Pesanan</a>
                    </li>
                </ul>
            </li>
            <!-- <li class="nav-label">SYSTEM</li>
            <li>
                <form action="{{ route('logout') }}" method="POST" class="px-3 mt-2">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm w-100">
                        <i class="icon icon-logout"></i> Logout
                    </button>
                </form>
            </li> -->

        </ul>
    </div>
</div>
<!--**********************************
    Sidebar end
***********************************-->
