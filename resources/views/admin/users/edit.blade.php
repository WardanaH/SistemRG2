@extends('layouts.app')

@section('content')
<div class="container">

    <h4 class="mb-4">Edit User</h4>

    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Nama --}}
        <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" name="nama" class="form-control"
                value="{{ old('nama', $user->nama) }}" required>
        </div>

        {{-- Username --}}
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control"
                value="{{ old('username', $user->username) }}" required>
        </div>

        {{-- Cabang --}}
        <div class="mb-3">
            <label class="form-label">Cabang</label>
            <select name="cabang_id" id="cabang_id" class="form-select select2-single" required>
                @foreach ($cabangs as $id => $namaCabang)
                <option value="{{ $id }}"
                    {{ $user->cabang_id == $id ? 'selected' : '' }}>
                    {{ $namaCabang }}
                </option>
                @endforeach
            </select>
        </div>

        {{-- Role --}}
        <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="roles[]" id="roles" class="form-select select2-single" required>
                @foreach ($roles as $role)
                <option value="{{ $role }}"
                    {{ $user->hasRole($role) ? 'selected' : '' }}>
                    {{ ucwords(str_replace('-', ' ', $role)) }}
                </option>
                @endforeach
            </select>
        </div>

        {{-- Password --}}
        <div class="mb-3">
            <label class="form-label">Password (kosongkan jika tidak diganti)</label>
            <input type="password" name="password" class="form-control">
        </div>

        <button class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Kembali</a>

    </form>

</div>
@endsection

@push('scripts')
<!-- Select2 CDN -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('.select2-single').select2({
            theme: "bootstrap-5",
            width: '100%',
            placeholder: "Pilih salah satu"
        });
    });
</script>
@endpush
