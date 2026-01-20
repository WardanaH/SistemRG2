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
    <link href="{{ asset('vendor/jqvmap/css/jqvmap.min.css') }}" rel="stylesheet">

    {{-- ============ ICON PACK (commented) ============ --}}
    <link href="{{ asset('vendor/themify-icons/themify-icons.css') }}" rel="stylesheet">
    <!-- <link href="{{ asset('vendor/fontawesome/css/all.min.css') }}" rel="stylesheet"> -->

    {{-- ============ MAIN STYLE ============ --}}
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    @stack('styles')

    {{-- ============ FONT FAMILY & SELECT2 CSS ============ --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">

    <style>
        /* ====================== GLOBAL FONT ====================== */
        body, h1, h2, h3, h4, h5, h6, .nav-text, .card-title, .welcome-text {
            font-family: 'Poppins', sans-serif !important;
        }

        /* ====================== HEADER CARD ====================== */
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

        /* ====================== TABLES ====================== */
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
        .content-body .table tbody tr:nth-child(even) td {
            background-color: #fafafa !important;
        }

        /* ====================== SELECT2 (Final cleaned & merged) ====================== */
        .select2-container--bootstrap-5 .select2-selection {
            border-radius: 8px !important;
            border: 1.5px solid #2793beff !important;
            border-bottom: 2px solid #5f9df7 !important;
            min-height: 33px !important;
            padding: 6px 10px !important;
            display: flex !important;
            align-items: center !important;
        }
        .select2-container--bootstrap-5 .select2-selection__rendered {
            font-size: 0.95rem !important;
            font-weight: 500 !important;
            color: #2b2b2b !important;
            padding-left: 4px !important;
            padding-right: 20px !important;
        }
        .select2-container--bootstrap-5.select2-container--focus .select2-selection {
            border-color: #5f9df7 !important;
            box-shadow: 0 0 0 3px rgba(119,161,224,0.4) !important;
        }
        .select2-container--bootstrap-5 .select2-results__options { max-height: 180px !important; border-radius: 0 0 10px 10px !important; }
        .select2-container--bootstrap-5 .select2-dropdown { border-radius: 10px !important; border-color: #5f9df7 !important; }

        /* ====================== HEADER CARD WHITE BUTTONS ====================== */
        .card-header .btn-light, .card-header .btn-white {
            background-color: #fff !important;
            color: #4B28D2 !important;
            font-weight: 500;
            border: 1px solid #eee;
            transition: all 0.3s ease;
        }
        .card-header .btn-light:hover, .card-header .btn-white:hover {
            background-color: #f3f3f3 !important;
            color: #2b0cae !important;
        }

        /* ====================== NAV HEADER / LOGO / SIDEBAR ====================== */
        .nav-header .brand-logo {
            display: flex !important;
            align-items: center !important;
            justify-content: flex-start !important;
            gap: 8px;
            transform: none !important;
        }
        .nav-header .brand-logo .logo-abbr {
            transform: none !important;
            rotate: 0deg !important;
            margin: 0 !important;
            height: 38px !important;
            width: auto !important;
            display: inline-block !important;
            vertical-align: middle !important;
        }
        .nav-header .brand-logo .brand-title {
            display: inline-block !important;
            color: #ffffff !important;
            font-weight: 600 !important;
            font-size: 15px !important;
            line-height: 1.1 !important;
            margin-left: 6px !important;
            text-align: left !important;
        }
        .logo-abbr, .brand-logo img { transform: none !important; rotate: 0 !important; }

        /* ====================== COLLAPSE BEHAVIOUR ====================== */
        .menu-toggle .quixnav .nav-label { opacity: 0 !important; height: 0 !important; margin: 0 !important; padding: 0 !important; overflow: hidden !important; }
        .menu-toggle .quixnav .nav-text  { opacity: 0 !important; width: 0 !important; overflow: hidden !important; white-space: nowrap !important; }
        .menu-toggle .quixnav i { margin-right: 0 !important; }

        /* ====================== HIDE BRAND TEXT WHEN COLLAPSE (small helper) ====================== */
        body.menu-toggle .nav-header .brand-title { display: none !important; }
        body.menu-toggle .nav-header .brand-logo { justify-content: center !important; }

        /* ====================== QUICK GLOBAL HELPERS ====================== */
        .card-header .btn-light, .card-header .btn-white { cursor: pointer; }

        /* ====================== FONT SEBELUM INPUT ====================== */
        label {
            color: #2b2b2b !important;
            font-weight: 500 !important;
        }
    </style>

    {{-- ====================== GLOBAL MODAL STYLE (merged) ====================== --}}
    <style>
        .modal-content {
            border-radius: 13px !important;
            border: none !important;
            overflow: hidden !important;
            box-shadow: 0px 8px 28px rgba(0,0,0,0.18);
            animation: modalPop 0.25s ease-out;
        }
        @keyframes modalPop {
            from { transform: scale(0.95); opacity: 0; }
            to   { transform: scale(1); opacity: 1; }
        }
        .modal-header {
            background: #4B28D2 !important;
            color: #fff !important;
            padding: 16px 20px !important;
            border-bottom: none !important;
        }
        .modal-header .modal-title { font-weight: 600 !important; font-size: 1.15rem !important; color: #fff !important; }
        .modal-header .btn-close { filter: brightness(0) invert(1) !important; opacity: .85; }
        .modal-header .btn-close:hover { opacity: 1; }
        .modal-body { padding: 20px !important; background: #fff !important; }
        .modal-footer { border-top: 1px solid #eaeaea !important; padding: 14px 20px !important; background: #fafafa !important; }
        .modal-footer .btn { border-radius: 10px !important; font-weight: 500 !important; padding: 8px 16px !important; }
        .modal-footer .btn-primary:hover { background-color: #3a1fba !important; }
        .modal-footer .btn-secondary:hover { background-color: #e3e3e3 !important; }

        .btn-close {
            background: none !important;
            border: none !important;
            width: 1.2rem !important;
            height: 1.2rem !important;
            opacity: 1 !important;
            position: relative;
        }
        .btn-close::before, .btn-close::after {
            content: "" !important;
            position: absolute;
            top: 50%;
            left: 50%;
            width: 1.2rem;
            height: 2px;
            background-color: #fff;
            transform-origin: center;
        }
        .btn-close::before { transform: translate(-50%, -50%) rotate(45deg); }
        .btn-close::after  { transform: translate(-50%, -50%) rotate(-45deg); }
    </style>

    {{-- ====================== CUSTOM MODERN SELECT WITH SEARCH (standalone) ====================== --}}
    <style>
        .custom-dropdown { position: relative; width: 100%; }
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
        .custom-dropdown .dropdown-display:hover { background: #f7f9ff; }
        .custom-dropdown .dropdown-panel {
            position: absolute; top: 100%; left: 0; right: 0;
            background: #fff; border: 1px solid #ddd;
            border-radius: 0 0 10px 10px; max-height: 220px; overflow-y: auto;
            display: none; z-index: 99999; box-shadow: 0 4px 10px rgba(0,0,0,.12);
        }
        .custom-dropdown input.search { width: 100%; border: none; padding: 8px 12px; border-bottom: 1px solid #eee; outline: none; font-size: .9rem; }
        .custom-dropdown .item { padding: 8px 12px; cursor: pointer; font-size: .9rem; }
        .custom-dropdown .item:hover { background: #eef4ff; }
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
                <!-- @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif -->

                <!-- @if($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif -->

                {{-- Konten Dinamis --}}
                @yield('content')
            </div>
            @include('layouts.footer')
        </div>

    </div>

    {{-- ============ CORE JS ============ --}}
    <script src="{{ asset('vendor/global/global.min.js') }}"></script>
    <script src="{{ asset('js/quixnav-init.js') }}"></script>
    <script src="{{ asset('js/custom.min.js') }}"></script>

    {{-- ============ ADDITIONAL PLUGINS ============ --}}
    <!-- <script src="{{ asset('vendor/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script> -->

    {{-- SweetAlert2 (keputusan: pakai 11.10.0 yang lengkap) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js"></script>

    {{-- ============ PRELOADER FIX ============ --}}
    <script>
        window.addEventListener('load', function () {
            var pre = document.getElementById('preloader');
            if (pre) pre.style.display = 'none';
        });
    </script>

    {{-- ============ EXTERNAL LIBRARIES (merged) ============ --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    {{-- DATATABLES --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
    $(document).ready(function() {
        $('.styletable').DataTable({
            pageLength: 10,
            lengthMenu: [5,10,25,50,100],
            processing: true,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ baris",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ baris",
                paginate: { previous: "Previous", next: "Next" }
            }
        });

        // Apply select2 untuk dropdown di tabel kalau ada
        $('.styletable').on('draw.dt', function(){
            $(this).find('select').select2({
                minimumResultsForSearch: Infinity,
                theme: 'bootstrap-5',
                width: '100%'
            });
        });
    });
    </script>


    {{-- ============ SCRIPT CHILD VIEW & INITS (merged select2 init + modal fix) ============ --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // init select2 global (kecuali yang berada di modal, handled below)
            $('.select2:not(.in-modal)').select2({
                theme: 'bootstrap-5',
                width: '100%',
                dropdownAutoWidth: false
            });

            // init select2 yang ada di modal -> dropdownParent untuk mengatasi z-index
            $('.modal').on('shown.bs.modal', function () {
                $(this).find('.select2').select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    dropdownParent: $(this)
                });
            });

            // tambahan: jika ada select2 in modal yang di-open dinamis, juga bisa dipanggil dengan shown.bs.modal handler di atas

            // preloader already hidden on window load listener above
        });
    </script>

    @stack('scripts')

    {{-- ============ GLOBAL TABLE SEARCH (preserve original behaviour) ============ --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var searchInput = document.getElementById("globalSearchInput");
            if (!searchInput) return;

            searchInput.addEventListener("input", function() {
                var keyword = this.value.toLowerCase().trim();
                var tables = document.querySelectorAll("table");

                tables.forEach(function(table) {
                    var rows = table.querySelectorAll("tbody tr");
                    rows.forEach(function(row) {
                        var text = row.innerText.toLowerCase();
                        if (keyword === "" || text.includes(keyword)) {
                            row.style.display = "";
                        } else {
                            row.style.display = "none";
                        }
                    });

                    // Naikkan row yang cocok ke atas
                    if (keyword !== "") {
                        var tbody = table.querySelector("tbody");
                        var matched = [];
                        var unmatched = [];

                        rows.forEach(function(row) {
                            if (row.style.display === "") matched.push(row);
                            else unmatched.push(row);
                        });

                        // kosongkan dan append ulang
                        tbody.innerHTML = "";
                        matched.forEach(function(r){ tbody.appendChild(r); });
                        unmatched.forEach(function(r){ tbody.appendChild(r); });
                    }
                });
            });
        });
    </script>

    {{-- ============ SIDEBAR TOGGLE (preserve original) ============ --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var btn = document.querySelector('.nav-control');
            if (!btn) return;
            btn.addEventListener('click', function() {
                document.body.classList.toggle('menu-toggle');
            });
        });
    </script>

</body>
</html>
