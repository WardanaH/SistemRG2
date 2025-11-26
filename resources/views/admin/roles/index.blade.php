@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Manajemen Hak Akses</h4>

    {{-- Alert sukses dari session --}}
    @if(session('success'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                icon: "success",
                title: "Berhasil!",
                text: "{{ session('success') }}",
                confirmButtonColor: "#3085d6"
            });
        });
    </script>
    @endif

    {{-- Alert error --}}
    @if(session('error'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                icon: "error",
                title: "Gagal!",
                text: "{{ session('error') }}",
                confirmButtonColor: "#d33"
            });
        });
    </script>
    @endif

    <form method="POST" action="{{ route('roles.store') }}" class="mb-3" id="form-tambah-role">
        @csrf
        <div class="input-group">
            <input type="text" name="name" placeholder="Nama Role" class="form-control" required>
            <button class="btn btn-primary" type="button" onclick="confirmTambah()">Tambah</button>
        </div>
    </form>

    <table class="table table-bordered mt-3">
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
                    <form method="POST" action="{{ route('roles.update', $role) }}" class="form-permission">
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
                                        {{ config('permissions_labels.' . $perm->name, $perm->name) }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endforeach

                        <button type="button" class="btn btn-sm btn-success mt-3" onclick="confirmSimpan(this.form)">
                            Simpan
                        </button>
                    </form>
                </td>

                <td>
                    <form method="POST" action="{{ route('roles.destroy', $role) }}" class="form-hapus">
                        @csrf @method('DELETE')
                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmHapus(this.form)">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- SweetAlert Scripts --}}
<script>
function confirmTambah() {
    Swal.fire({
        title: "Tambah Role?",
        text: "Role baru akan ditambahkan!",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Ya, Tambah"
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById("form-tambah-role").submit();
        }
    });
}

function confirmSimpan(form) {
    Swal.fire({
        title: "Simpan Perubahan?",
        text: "Permissions akan diperbarui!",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Ya, Simpan"
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
}

function confirmHapus(form) {
    Swal.fire({
        title: "Hapus Role?",
        text: "Role ini akan dihapus permanen!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Ya, Hapus"
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
}
</script>

@endsection
