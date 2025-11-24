@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Manajemen Hak Akses</h4>

    <form method="POST" action="{{ route('roles.store') }}" class="mb-3">
        @csrf
        <div class="input-group">
            <input type="text" name="name" placeholder="Nama Role" class="form-control" required>
            <button class="btn btn-primary">Tambah</button>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama Role</th>
                <th>Permissions</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($roles as $role)
            <tr>
                <td>{{ $role->name }}</td>
                <td>
                    <form method="POST" action="{{ route('roles.update', $role) }}">
                        @csrf @method('PUT')

                        @foreach ($permissions as $group => $items)
                        <h6 class="mt-3 text-primary text-uppercase">{{ $group }}</h6>
                        <div class="row">

                            @foreach ($items as $perm)
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input"
                                        type="checkbox"
                                        name="permissions[]"
                                        value="{{ $perm->name }}"
                                        {{ $role->hasPermissionTo($perm->name) ? 'checked' : '' }}>

                                    <label class="form-check-label">
                                        {{ config('permissions_label.' . $perm->name, $perm->name) }}
                                    </label>
                                </div>
                            </div>
                            @endforeach

                        </div>
                        @endforeach

                        <button class="btn btn-sm btn-success mt-3">Simpan</button>
                    </form>


                </td>
                <td>
                    <form method="POST" action="{{ route('roles.destroy', $role) }}">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
