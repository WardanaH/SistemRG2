@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Selamat Datang, {{ $user->name }}</h3>
    <p>Role Anda: <strong>{{ $user->getRoleNames()->implode(', ') }}</strong></p>

    <div class="mt-4">
        <a href="{{ route('users.index') }}" class="btn btn-outline-primary">Manajemen User</a>
        <a href="{{ route('cabangs.index') }}" class="btn btn-outline-success">Manajemen Cabang</a>
        <a href="{{ route('roles.index') }}" class="btn btn-outline-warning">Manajemen Hak Akses</a>
        <a href="{{ route('managesupplierindex') }}" class="btn btn-outline-warning">Manajemen Supplier</a>
        <a href="{{ route('managekategoriindex') }}" class="btn btn-outline-warning">Manajemen Kategori</a>
    </div>
</div>
@endsection
