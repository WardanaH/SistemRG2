@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Edit Cabang</h4>
        <a href="{{ route('cabangs.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <form action="{{ route('cabangs.update', $cabang) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Kode Cabang</label>
                        <input type="text" name="kode" class="form-control @error('kode') is-invalid @enderror"
                            value="{{ old('kode', $cabang->kode) }}">
                        @error('kode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Nama Cabang</label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                            value="{{ old('nama', $cabang->nama) }}">
                        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row mb-3">

                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $cabang->email) }}">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Telepon</label>
                        <input type="text" name="telepon" class="form-control @error('telepon') is-invalid @enderror"
                            value="{{ old('telepon', $cabang->telepon) }}">
                        @error('telepon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                </div>

                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" rows="3"
                        class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', $cabang->alamat) }}</textarea>
                    @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <button class="btn btn-success px-4">Simpan Perubahan</button>

            </form>

        </div>
    </div>

</div>
@endsection
