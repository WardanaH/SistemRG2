<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sistem Restu Guru Promosindo') }}</title>

    {{-- ============ FOCUS TEMPLATE CORE CSS ============ --}}
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon.png') }}">
    <link href="{{ asset('vendor/pg-calendar/css/pignose.calendar.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/chartist/css/chartist.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/jqvmap/css/jqvmap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/owl-carousel/css/owl.carousel.min.css') }}" rel="stylesheet">

    {{-- ============ ICON PACK ============ --}}
    <!-- <link href="{{ asset('vendor/themify-icons/themify-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/fontawesome/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/line-awesome/css/line-awesome.min.css') }}" rel="stylesheet"> -->

    {{-- ============ MAIN STYLE ============ --}}
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    {{-- ============ FONT FAMILY ============ --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body, h1, h2, h3, h4, h5, h6, .nav-text, .card-title, .welcome-text {
            font-family: 'Poppins', sans-serif !important;
        }
    </style>

    <!-- Select2 Core -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">

    <!-- Select2 Bootstrap 5 Theme -->
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">

    <!-- ====================== CUSTOM STYLE ====================== -->
     @stack('styles')
    <style>
    /* ===========================================================
       HEADER CARD DALAM CONTENT
    =========================================================== */
    .content-body .card-header {
        background-color: #4B28D2 !important;
        color: #ffffff !important;
        font-weight: 600;
        letter-spacing: 0.3px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
        padding: 0.75rem 1rem;
    }
    .content-body .card-header * { color: #fff !important; }
    .content-body .card-header i { color: #ffeb3b !important; margin-right: 8px; }

    /* ===========================================================
       FIX TEKS TABEL PUCAT / TRANSPARAN
    =========================================================== */
    .table, .table td, .table th {
        color: #2b2b2b !important;
        opacity: 1 !important;
    }
    .content-body .table thead th {
        background-color: #f8f9fc !important;
        color: #212121 !important;
        font-weight: 600 !important;
        border-color: #e5e7eb !important;
    }
    .content-body .table tbody td {
        color: #2b2b2b !important;
        font-weight: 500;
        background-color: #fff !important;
    }
    .content-body .table tbody tr:nth-child(even) td { background-color: #fafafa !important; }

    /* ===========================================================
       STYLE GLOBAL UNTUK SEMUA DROPDOWN / SELECT
    =========================================================== */
    select.form-select, select {
        border: 1.5px solid #dcdcdc;
        border-bottom: 2px solid #5f9df7;  /* Biru muda bawah */
        border-radius: 10px;
        padding: 6px 10px;
        font-size: 0.95rem;
        transition: all 0.2s ease-in-out;
        font-weight: 500;
        color: #2b2b2b;
    }
    select.form-select:focus, select:focus {
        border-color: #5f9df7 !important;
        box-shadow: 0 0 0 3px rgba(119, 161, 224, 0.5);
        outline: none;
    }
    select option {
        color: #333;
        font-weight: 500;
    }

    /* === Select2 Styling === */
    .select2-container--bootstrap-5 .select2-selection {
        border-radius: 10px !important;
        border: 1.5px solid #dcdcdc !important;
        border-bottom: 2px solid #5f9df7 !important;
        min-height: 38px !important;
        padding: 4px 10px !important;
        transition: all 0.2s ease-in-out;
    }
    .select2-container--bootstrap-5.select2-container--focus .select2-selection {
        border-color: #5f9df7 !important;
        box-shadow: 0 0 0 3px rgba(119, 161, 224, 0.4);
    }
    .select2-container--bootstrap-5 .select2-results__options {
        max-height: 180px !important;
        border-radius: 0 0 10px 10px !important;
    }

    /* ===========================================================
       WARNA TOMBOL PUTIH DI HEADER CARD
    =========================================================== */
    .card-header .btn-light,
    .card-header .btn-white {
        background-color: #fff !important;
        color: #4B28D2 !important;
        font-weight: 500;
        border: 1px solid #eee;
        transition: all 0.3s ease;
    }
    .card-header .btn-light:hover,
    .card-header .btn-white:hover {
        background-color: #f3f3f3 !important;
        color: #2b0cae !important;
    }

    /* === Perbaiki posisi & rotasi logo di sidebar === */
.nav-header .brand-logo .logo-abbr {
    transform: none !important;      /* hilangkan efek miring */
    rotate: 0deg !important;         /* pastikan tidak miring */
    margin-top: 0 !important;
    margin-left: 0 !important;
    display: block;
}
.nav-header .brand-logo {
    display: flex;
    align-items: center;
    justify-content: flex-start;
}
.nav-header .brand-title {
    color: #ffffff;
    font-weight: 600;
    font-size: 16px;
    line-height: 1.2;
    margin-left: 8px;
}

/* ==== FIX LOGO NAV-HEADER AGAR TIDAK MIRING ==== */
.nav-header .brand-logo {
    display: flex !important;
    align-items: center !important;
    justify-content: flex-start !important;
    gap: 8px;
    transform: none !important;
}

/* Logo utama di kiri atas */
.nav-header .brand-logo .logo-abbr {
    transform: none !important;
    rotate: 0deg !important;
    margin: 0 !important;
    height: 60px !important;
    width: auto !important;
    display: inline-block !important;
}

/* Teks di sebelah logo */
.nav-header .brand-logo .brand-title {
    display: inline-block !important;
    color: #ffffff !important;
    font-weight: 600 !important;
    font-size: 15px !important;
    line-height: 1.1 !important;
    margin-left: 6px !important;
    text-align: left !important;
}

/* Hapus rotasi global yang diset Focus di style.css */
.logo-abbr, .brand-logo img {
    transform: none !important;
    rotate: 0 !important;
}

/* ===== Hapus efek miring default Focus Template ===== */
.nav-header .brand-logo .logo-abbr,
.nav-header .brand-logo img.logo-abbr,
.nav-header img.logo-abbr {
    transform: none !important;
    rotate: 0deg !important;
    margin: 0 !important;
    height: 38px !important;
    width: auto !important;
    display: inline-block !important;
    vertical-align: middle !important;
}

    </style>
</head>

<body>

    {{-- ============ PRELOADER ============ --}}
    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>

    <div id="main-wrapper">
        @auth
            @include('operator.layout.header')
            @include('operator.layout.sidebar')
        @endauth

        <div class="content-body">
            <div class="container-fluid">
                {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif

                {{-- Konten Dinamis --}}
                @yield('content')
            </div>
        </div>

        @include('operator.layout.footer')
    </div>

    {{-- ============ CORE JS ============ --}}
    <script src="{{ asset('vendor/global/global.min.js') }}"></script>
    <script src="{{ asset('js/quixnav-init.js') }}"></script>
    <script src="{{ asset('js/custom.min.js') }}"></script>

    {{-- ============ ADDITIONAL PLUGINS ============ --}}
    <script src="{{ asset('vendor/moment/moment.min.js') }}"></script>
    <script src="{{ asset('vendor/pg-calendar/js/pignose.calendar.min.js') }}"></script>
    <!-- <script src="{{ asset('vendor/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script> -->
    <script src="{{ asset('vendor/owl-carousel/js/owl.carousel.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- ============ DASHBOARD SCRIPT ============ --}}
    <script src="{{ asset('js/dashboard/dashboard-2.js') }}"></script>

    {{-- ============ PRELOADER FIX ============ --}}
    <script>
        window.addEventListener('load', () => {
            const pre = document.getElementById('preloader');
            if (pre) pre.style.display = 'none';
        });
    </script>

    {{-- ============ EXTERNAL LIBRARIES ============ --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js"></script>

    {{-- ============ SCRIPT CHILD VIEW ============ --}}
    @stack('scripts')

</body>
</html>
