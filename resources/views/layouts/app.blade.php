<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'Sistem Restu Guru Promosindo') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
        <a href="{{ route('cabangs.index') }}">🏢 Manajemen Cabang</a>
        <a href="{{ route('roles.index') }}">🔐 Manajemen Hak Akses</a>
        <a href="{{ route('managesupplierindex') }}">📦 Manajemen Supplier</a>
        <a href="{{ route('managekategoriindex') }}">📂 Manajemen Kategori</a>
        <a href="{{ route('jenispelanggan.index') }}">🧾 Manajemen Jenis Pelanggan</a>
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
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</html>
