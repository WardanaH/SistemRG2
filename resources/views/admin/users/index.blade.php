@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Manajemen User</h3>
    <hr>

    <form method="POST" action="{{ route('users.store') }}" class="mb-4">
        @csrf
        <div class="row">
            <div class="col-md-3 mb-2">
                <input type="text" name="name" class="form-control" placeholder="Nama Lengkap" required>
            </div>
            <div class="col-md-2 mb-2">
                <input type="text" name="username" class="form-control" placeholder="Username" required>
            </div>
            <div class="col-md-3 mb-2">
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="col-md-2 mb-2">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <div class="col-md-2 mb-2">
                <select name="role" class="form-control">
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 mb-2">
                <input type="text" name="telepon" class="form-control" placeholder="Telepon">
            </div>
            <div class="col-md-3 mb-2">
                <input type="text" name="gaji" class="form-control" placeholder="Gaji">
            </div>
            <div class="col-md-4 mb-2">
                <input type="text" name="alamat" class="form-control" placeholder="Alamat">
            </div>
            <div class="col-md-2 mb-2">
                <select name="cabang_id" class="form-control">
                    @foreach($cabangs as $c)
                        <option value="{{ $c->id }}">{{ $c->nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <button class="btn btn-primary mt-2">Tambah User</button>
    </form>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Cabang</th>
                <th>Telepon</th>
                <th>Gaji</th>
                <th>Alamat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->username }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->getRoleNames()->implode(', ') }}</td>
                <td>{{ $user->cabang->nama ?? '-' }}</td>
                <td>{{ $user->telepon ?? '-' }}</td>
                <td>{{ $user->gaji ?? '-' }}</td>
                <td>{{ $user->alamat ?? '-' }}</td>
                <td>
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form method="POST" action="{{ route('users.destroy', $user) }}" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus user ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
