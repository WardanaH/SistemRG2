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
    <!-- Select2 Core -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">

    <!-- Select2 Bootstrap 5 Theme -->
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">

    <style>
        body,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .nav-text,
        .card-title,
        .welcome-text {
            font-family: 'Poppins', sans-serif !important;
        }
    </style>

    <!-- ====================== CUSTOM STYLE ====================== -->
    @stack('styles')
    <style>
        /* =========================================================== HEADER CARD DALAM CONTENT =========================================================== */
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

        .content-body .card-header * {
            color: #fff !important;
        }

        .content-body .card-header i {
            color: #ffeb3b !important;
            margin-right: 8px;
        }

        /* ===========================================================
       FIX TEKS TABEL PUCAT / TRANSPARAN=========================================================== */
        .table,
        .table td,
        .table th {
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

        .content-body .table tbody tr:nth-child(even) td {
            background-color: #fafafa !important;
        }

        /* ===========================================================
       STYLE GLOBAL UNTUK SEMUA DROPDOWN / SELECT=========================================================== */
        /* select.form-select,
        select {
            border: 1.5px solid #dcdcdc;
            border-bottom: 2px solid #5f9df7;
            border-radius: 10px;
            padding: 6px 10px;
            font-size: 0.95rem;
            transition: all 0.2s ease-in-out;
            font-weight: 500;
            color: #2b2b2b;
        } */

        /* select.form-select:focus,
        select:focus {
            border-color: #5f9df7 !important;
            box-shadow: 0 0 0 3px rgba(119, 161, 224, 0.5);
            outline: none;
        } */

        /* select option {
            color: #333;
            font-weight: 500;
        } */

        /* === Select2 Styling === */
        /* .select2-container--bootstrap-5 .select2-selection {
            border-radius: 10px !important;
            border: 1.5px solid #dcdcdc !important;
            border-bottom: 2px solid #5f9df7 !important;
            min-height: 38px !important;
            padding: 4px 10px !important;
            transition: all 0.2s ease-in-out;
        } */

        .select2-container--bootstrap-5.select2-container--focus .select2-selection {
            border-color: #5f9df7 !important;
            box-shadow: 0 0 0 3px rgba(119, 161, 224, 0.4);
        }

        .select2-container--bootstrap-5 .select2-results__options {
            max-height: 180px !important;
            border-radius: 0 0 10px 10px !important;
        }

        /* === Select2 Final Clean Style === */
        .select2-container--bootstrap-5 .select2-selection {
            border-radius: 10px !important;
            border: 1.5px solid #2793beff !important;
            border-bottom: 2px solid #5f9df7 !important;
            min-height: 42px !important;
            padding: 6px 10px !important;
            display: flex !important;
            align-items: center !important;
        }

        .select2-container--bootstrap-5 .select2-selection__rendered {
            font-size: 0.95rem !important;
            font-weight: 500 !important;
            color: #2b2b2b !important;
        }

        .select2-container--bootstrap-5.select2-container--focus .select2-selection {
            border-color: #5f9df7 !important;
            box-shadow: 0 0 0 3px rgba(119, 161, 224, 0.4) !important;
        }

        .select2-container--bootstrap-5 .select2-dropdown {
            border-radius: 10px !important;
            border-color: #5f9df7 !important;
        }


        /* ===========================================================
       WARNA TOMBOL PUTIH DI HEADER CARD=========================================================== */
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
            transform: none !important;
            /* hilangkan efek miring */
            rotate: 0deg !important;
            /* pastikan tidak miring */
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
        .logo-abbr,
        .brand-logo img {
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

        /* Sembunyikan nav label saat sidebar collapse */
        .menu-toggle .quixnav .nav-label {
            opacity: 0 !important;
            height: 0 !important;
            margin: 0 !important;
            padding: 0 !important;
            overflow: hidden !important;
        }

        /* Hilangkan tulisan menu saat collapse */
        .menu-toggle .quixnav .nav-text {
            opacity: 0 !important;
            width: 0 !important;
            overflow: hidden !important;
            white-space: nowrap !important;
        }

        /* Biar ikon tetap rapat */
        .menu-toggle .quixnav i {
            margin-right: 0 !important;
        }
    </style>

    <style>
        /* ============================================================ GLOBAL MODAL STYLE — Berlaku untuk semua modal Bootstrap ============================================================ */

        /* Kontainer utama modal */
        .modal-content {
            border-radius: 13px !important;
            border: none !important;
            overflow: hidden !important;
            box-shadow: 0px 8px 28px rgba(0, 0, 0, 0.18);
            animation: modalPop 0.25s ease-out;
        }

        @keyframes modalPop {
            from {
                transform: scale(0.95);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .modal-header {
            background: #4B28D2 !important;
            color: #fff !important;
            padding: 16px 20px !important;
            border-bottom: none !important;
        }

        .modal-header .modal-title {
            font-weight: 600 !important;
            font-size: 1.15rem !important;
            color: #fff !important;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1) !important;
            opacity: 0.85;
        }

        .modal-header .btn-close:hover {
            opacity: 1;
        }

        .modal-body {
            padding: 20px !important;
            background: #ffffff !important;
        }

        .modal-footer {
            border-top: 1px solid #eaeaea !important;
            padding: 14px 20px !important;
            background: #fafafa !important;
        }

        .modal-footer .btn {
            border-radius: 10px !important;
            font-weight: 500 !important;
            padding: 8px 16px !important;
        }

        .modal-footer .btn-primary:hover {
            background-color: #3a1fba !important;
        }

        .modal-footer .btn-secondary:hover {
            background-color: #e3e3e3 !important;
        }

        .btn-close {
            background: none !important;
            border: none !important;
            width: 1.2rem !important;
            height: 1.2rem !important;
            opacity: 1 !important;
            position: relative;
        }

        .btn-close::before,
        .btn-close::after {
            content: "" !important;
            position: absolute;
            top: 50%;
            left: 50%;
            width: 1.2rem;
            height: 2px;
            background-color: #fff; /* warna X */
            transform-origin: center;
        }

        .btn-close::before {
            transform: translate(-50%, -50%) rotate(45deg);
        }

        .btn-close::after {
            transform: translate(-50%, -50%) rotate(-45deg);
        }



    </style>

    </style>
    <style>
    /* .select2-container--bootstrap-5 .select2-selection {
        border-radius: 10px !important;
        border: 1.5px solid #dcdcdc !important;
        border-bottom: 2px solid #5f9df7 !important;
        height: 38px !important;
        padding: 6px 10px !important;
    } */

    .select2-container--bootstrap-5 .select2-selection__rendered {
        padding-left: 4px !important;
        padding-right: 20px !important;
        font-size: 0.95rem !important;
        font-weight: 500 !important;
    }

    .select2-container--bootstrap-5 .select2-dropdown {
        border-radius: 10px !important;
        border-color: #5f9df7 !important;
    }
</style>
<style>
    /* ================= CUSTOM MODERN SELECT WITH SEARCH ================= */

    .custom-dropdown {
        position: relative;
        width: 100%;
    }

    .custom-dropdown .dropdown-display {
        border: 1.5px solid #dcdcdc;
        border-bottom: 2px solid #5f9df7;
        border-radius: 10px;
        padding: 8px 10px;
        cursor: pointer;
        background: #fff;
        font-weight: 500;
        color: #2b2b2b;
    }

    .custom-dropdown .dropdown-display:hover {
        background: #f7f9ff;
    }

    .custom-dropdown .dropdown-panel {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 0 0 10px 10px;
        max-height: 220px;
        overflow-y: auto;
        display: none;
        z-index: 99999;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
    }

    .custom-dropdown input.search {
        width: 100%;
        border: none;
        padding: 8px 12px;
        border-bottom: 1px solid #eee;
        outline: none;
        font-size: 0.9rem;
    }

    .custom-dropdown .item {
        padding: 8px 12px;
        cursor: pointer;
        font-size: 0.9rem;
    }

    .custom-dropdown .item:hover {
        background: #eef4ff;
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
        @include('layouts.header')
        @include('layouts.sidebar')
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

        @include('layouts.footer')
    </div>

    {{-- ============ CORE JS ============ --}}
    <script src="{{ asset('vendor/global/global.min.js') }}"></script>
    <script src="{{ asset('js/quixnav-init.js') }}"></script>
    <script src="{{ asset('js/custom.min.js') }}"></script>

    {{-- ============ ADDITIONAL PLUGINS ============ --}}
    <script src="{{ asset('vendor/chartist/js/chartist.min.js') }}"></script>
    <script src="{{ asset('vendor/chartist-plugin-tooltips/js/chartist-plugin-tooltip.min.js') }}"></script>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js"></script>

    {{-- DATATABLES --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>


    {{-- ============ SCRIPT CHILD VIEW ============ --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%',
                dropdownAutoWidth: false,
            });
        });
    </script>

    @stack('scripts')

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById("globalSearchInput");

            if (!searchInput) return;

            searchInput.addEventListener("input", function() {
                let keyword = this.value.toLowerCase().trim();

                // Ambil semua table yang ada di halaman
                const tables = document.querySelectorAll("table");

                tables.forEach(table => {
                    const rows = table.querySelectorAll("tbody tr");

                    rows.forEach(row => {
                        const text = row.innerText.toLowerCase();

                        if (keyword === "" || text.includes(keyword)) {
                            row.style.display = "";
                        } else {
                            row.style.display = "none";
                        }
                    });

                    // Naikkan row yang cocok ke atas
                    if (keyword !== "") {
                        let tbody = table.querySelector("tbody");
                        let matched = [];
                        let unmatched = [];

                        rows.forEach(row => {
                            if (row.style.display === "") matched.push(row);
                            else unmatched.push(row);
                        });

                        tbody.innerHTML = "";
                        matched.forEach(r => tbody.appendChild(r));
                        unmatched.forEach(r => tbody.appendChild(r));
                    }
                });
            });
        });
    </script>

    <style>
        /* Hide brand text when sidebar collapse */
        body.menu-toggle .nav-header .brand-title {
            display: none !important;
        }

        /* Center logo when text hidden */
        body.menu-toggle .nav-header .brand-logo {
            justify-content: center !important;
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const btn = document.querySelector('.nav-control');
            btn.addEventListener('click', function() {
                document.body.classList.toggle('menu-toggle');
            });
        });
    </script>

</body>

</html>
