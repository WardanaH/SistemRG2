@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Manajemen Cabang</h3>
    <hr>

    <form method="POST" action="{{ route('cabangs.store') }}" class="mb-4">
        @csrf
        <div class="row">
            <div class="col-md-3 mb-2">
                <input type="text" name="kode" class="form-control" placeholder="Kode Cabang" required>
            </div>
            <div class="col-md-3 mb-2">
                <input type="text" name="nama" class="form-control" placeholder="Nama Cabang" required>
            </div>
            <div class="col-md-3 mb-2">
                <input type="text" name="email" class="form-control" placeholder="Email Cabang">
            </div>
            <div class="col-md-3 mb-2">
                <select name="jenis" class="form-control">
                    <option value="pusat">Pusat</option>
                    <option value="cabang">Cabang</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-2">
                <input type="text" name="telepon" class="form-control" placeholder="Telepon Cabang">
            </div>
            <div class="col-md-6 mb-2">
                <input type="text" name="alamat" class="form-control" placeholder="Alamat Cabang">
            </div>
        </div>
        <button class="btn btn-primary mt-2">Tambah Cabang</button>
    </form>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama</th>
                <th>Jenis</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Alamat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cabangs as $cabang)
            <tr>
                <td>{{ $cabang->kode }}</td>
                <td>{{ $cabang->nama }}</td>
                <td>{{ ucfirst($cabang->jenis) }}</td>
                <td>{{ $cabang->email }}</td>
                <td>{{ $cabang->telepon }}</td>
                <td>{{ $cabang->alamat }}</td>
                <td>
                    <a href="{{ route('cabangs.edit', $cabang) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form method="POST" action="{{ route('cabangs.destroy', $cabang) }}" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus cabang ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
