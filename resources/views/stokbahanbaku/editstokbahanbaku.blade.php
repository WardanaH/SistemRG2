@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow mt-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">✏️ Edit Stok Bahan Baku</h5>
        </div>

        <div class="card-body">
            <form method="POST" action="{{ route('stokbahanbaku.update', $id) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nama Bahan Baku</label>
                    <input type="text" class="form-control" value="{{ $data->bahanbaku->nama_bahan ?? '-' }}" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label">Cabang</label>
                    <input type="text" class="form-control" value="{{ $data->cabang->nama ?? '-' }}" disabled>
                </div>

                <div class="mb-3">
                    <label for="banyak_stok" class="form-label">Banyak Stok</label>
                    <input type="number" name="banyak_stok" id="banyak_stok"
                        value="{{ $data->banyak_stok }}" class="form-control" required>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('stokbahanbaku.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-success">💾 Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection