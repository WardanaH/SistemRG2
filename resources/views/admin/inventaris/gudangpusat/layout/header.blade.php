<!--**********************************
    Nav header start
***********************************-->
<div class="nav-header">
    <a href="{{ route('dashboard') }}" class="brand-logo">
        <img class="logo-abbr"
            src="{{ asset('images/logo_cropped.png') }}"
            alt="Logo Restu Guru Promosindo"
            style="height:75px !important; width:auto !important;">
        <span class="brand-title">
            Restu Guru<br>Promosindo
        </span>
    </a>

    <div class="nav-control">
        <div class="hamburger">
            <span class="line"></span><span class="line"></span><span class="line"></span>
        </div>
    </div>
</div>


<style>
/* ============================
   FIX NAV HEADER & LOGO
============================= */

.nav-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 85px !important;
    padding: 0 22px;
    background: #2f3558;
}

/* brand-logo sebagai flex horizontal */
.brand-logo {
    display: flex;
    align-items: center;
    gap: 14px;
}

/* LOGO */
.logo-abbr {
    width: 90px;
    height: 90px;
    object-fit: contain;
}

/* Teks 2 baris */
.brand-logo .brand-title {
    color: #fff;
    font-size: 18px;
    font-weight: 700;
    line-height: 1.2;
}

/* ---------- MODE COLLAPSED (sidebar menyempit) ---------- */

/* sembunyikan teks */
body.menu-toggle .brand-title {
    display: none !important;
}

/* logo ke tengah */
body.menu-toggle .brand-logo {
    justify-content: center !important;
    width: 100%;
}

/* hilangkan jarak */
body.menu-toggle .brand-logo {
    gap: 0 !important;
}

.brand-logo .brand-title {
    color: #fff !important;
    font-size: 18px !important;
    font-weight: 700 !important;

    display: block !important;
    white-space: normal !important;
    line-height: 1.2 !important;

    margin: 0 !important;
    padding: 0 !important;

    max-width: 120px !important;
}

</style>

<!--**********************************
    Header start
***********************************-->
<div class="header">
    <div class="header-content">
        <nav class="navbar navbar-expand">
            <div class="collapse navbar-collapse justify-content-between">

                <div class="header-left d-flex align-items-center">

                    <!-- GLOBAL SEARCH -->
                    @php
                        $hideSearch = in_array(request()->path(), [
                            'transaksi',
                            'transaksideleted',
                            'transaksi/bahan'
                        ]);
                    @endphp

                    @if (!$hideSearch)
                    <div class="search_bar dropdown">
                        <span class="search_icon p-3 c-pointer" data-toggle="dropdown">
                            <i class="mdi mdi-magnify"></i>
                        </span>
                        <div class="dropdown-menu p-0 m-0">
                            <form>
                                <input id="globalSearchInput"
                                    class="form-control"
                                    type="search"
                                    placeholder="Search"
                                    aria-label="Search">
                            </form>
                        </div>
                    </div>
                    @endif
                </div>

                <ul class="navbar-nav header-right">
                    <li class="nav-item dropdown notification_dropdown">
                        <a class="nav-link" href="#" role="button" data-toggle="dropdown">
                            <i class="mdi mdi-bell"></i>
                            <div class="pulse-css"></div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <ul class="list-unstyled">
                                <li class="media dropdown-item">
                                    <span class="success"><i class="ti-user"></i></span>
                                    <div class="media-body">
                                        <a href="#"><p><strong>Martin</strong> added a customer</p></a>
                                    </div>
                                    <span class="notify-time">3:20 am</span>
                                </li>
                            </ul>
                            <a class="all-notification" href="#">See all notifications <i class="ti-arrow-right"></i></a>
                        </div>
                    </li>

                    <li class="nav-item dropdown header-profile">
                        <a class="nav-link" href="#" role="button" data-toggle="dropdown">
                            <i class="mdi mdi-account"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="{{ route('profile') }}" class="dropdown-item">
                                <i class="icon-user"></i>
                                <span class="ml-2">Profile</span>
                            </a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item border-0 bg-transparent w-100 text-start">
                                    <i class="icon-key"></i> <span class="ml-2">Logout</span>
                                </button>
                            </form>
                        </div>
                    </li>
                </ul>

            </div>
        </nav>
    </div>
</div>
<!--**********************************
    Header end
***********************************-->

<script>
document.addEventListener("DOMContentLoaded", function () {

    // MENU TOGGLE
    const btn = document.querySelector('.nav-control');
    btn.addEventListener('click', function () {
        document.body.classList.toggle('menu-toggle');
    });

    // GLOBAL SEARCH DISABLE on blocked pages
    @php
        $hideSearch = in_array(request()->path(), [
        'transaksi',
        'transaksideleted',
        'transaksi/bahan'
        ]);
    @endphp

    @if (!$hideSearch)
        const searchInput = document.getElementById("globalSearchInput");

        if (searchInput) {
            searchInput.addEventListener("input", function () {
                const keyword = this.value.toLowerCase();

                document.querySelectorAll("table tbody").forEach(tbody => {
                    const rows = tbody.querySelectorAll("tr");

                    rows.forEach(row => {
                        const text = row.innerText.toLowerCase();

                        if (text.includes(keyword)) {
                            row.style.display = "";
                            row.classList.add("table-highlight");
                        } else {
                            row.style.display = "none";
                            row.classList.remove("table-highlight");
                        }
                    });

                    if (keyword === "") {
                        rows.forEach(row => {
                            row.style.display = "";
                            row.classList.remove("table-highlight");
                        });
                    }
                });
            });
        }
    @endif

});
</script>
