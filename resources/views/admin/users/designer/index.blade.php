@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">👩‍🎨 Daftar Desainer</h5>
            <a href="{{ route('dashboard') }}" class="btn btn-light btn-sm">Kembali</a>
        </div>
        <div class="card-body">
            @if ($designers->isEmpty())
            <div class="alert alert-info text-center">
                Belum ada user dengan role <strong>Designer</strong>.
            </div>
            @else
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Jumlah Transaksi</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th>Cabang</th>
                        <th>Dibuat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($designers as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->nama }}</td>
                        <td>{{ $user->transaksi_desain_count }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->telepon ?? '-' }}</td>
                        <td>{{ $user->cabang->nama ?? '-' }}</td>
                        <td>{{ $user->created_at->format('d M Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
</div>
@endsection