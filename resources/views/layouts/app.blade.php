<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'Sistem Restu Guru Promosindo') }}</title>

    {{-- Bootstrap & Font Awesome --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">

    {{-- Select2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    {{-- Optional: SweetAlert2 modern --}}
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .sidebar {
            height: 100vh;
            background: #0d6efd;
            color: #fff;
            padding: 20px 15px;
            position: fixed;
            width: 220px;
        }

        .sidebar a {
            color: #fff;
            display: block;
            text-decoration: none;
            padding: 8px 0;
        }

        .sidebar a:hover {
            text-decoration: underline;
        }

        .main {
            margin-left: 240px;
            padding: 25px;
        }
    </style>
</head>

<body>
    @auth
    <div class="sidebar">
        <h5 class="text-white">Menu</h5>

        <a href="{{ route('dashboard') }}">🏠 Dashboard</a>
        <a href="{{ route('users.index') }}">👤 Manajemen User</a>
        <a href="{{ route('addtransaksiindex') }}">🛒 Transaksi</a>
        <a href="{{ route('cabangs.index') }}">🏢 Manajemen Cabang</a>
        <a href="{{ route('roles.index') }}">🔐 Manajemen Hak Akses</a>
        <a href="{{ route('managesupplierindex') }}">📦 Manajemen Supplier</a>
        <a href="{{ route('managekategoriindex') }}">📂 Manajemen Kategori</a>
        <a href="{{ route('manageprodukindex') }}">🛒 Manajemen Produk</a>
        <a href="{{ route('managebahanbakuindex') }}">🧱 Manajemen Bahan Baku</a>
        <a href="{{ route('managerelasibahanbakuindex') }}">🧩 Aturan Bahan Baku</a>
        <a href="{{ route('jenispelanggan.index') }}">🧾 Manajemen Jenis Pelanggan</a>
        <a href="{{ route('stokbahanbaku.index') }}">📦 Daftar Stock Bahan Baku</a>
        <a href="{{ route('transaksibahanbaku.index') }}">📦 Transaksi Bahan Baku</a>
        <a href="{{ route('pelanggan.index') }}">👥 Manajemen Pelanggan</a>

        <form action="{{ route('logout') }}" method="POST" class="mt-3">
            @csrf
            <button class="btn btn-light btn-sm w-100">Logout</button>
        </form>
    </div>
    @endauth

    <div class="main">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        @yield('content')
    </div>

    {{-- jQuery wajib diletakkan sebelum script yang lain --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    {{-- Bootstrap Bundle --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Select2 --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    {{-- SweetAlert2 modern (optional) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js"></script>

    {{-- Tempat semua script tambahan dari child view --}}
    @stack('scripts')
</body>

</html>
