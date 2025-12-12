@extends('layouts.app')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center">
    <h3 class="page-title">Stok Bahan Baku – Cabang {{ ucfirst($gudang->nama) }}</h3>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahStok">
        <i class="bi bi-plus-circle"></i> Tambah / Update Stok
    </button>
</div>

@if(session('success'))
    <div class="alert alert-success mt-2">{{ session('success') }}</div>
@endif

<div class="card mt-3">
    <div class="card-body">
        <h4 class="card-title">Daftar Stok Bahan Baku</h4>

        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle text-center styletable">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Bahan</th>
                        <th>Stok</th>
                        <th>Satuan</th>
                        {{-- <th>Aksi</th> --}}
                    </tr>
                </thead>

                <tbody>
                @forelse ($datas as $index => $d)
                <tr>
                    <td>{{ $index + 1 }}</td>

                    <!-- Nama bahan -->
                    <td>{{ $d->nama_bahan }}</td>

                    <!-- Stok -->
                    <td>{{ $d->banyak_stok }}</td>

                    <!-- Satuan -->
                    <td>{{ $d->satuan_stok ?? '-' }}</td>

                    {{-- <!-- Aksi -->
                    <td>

                        <!-- EDIT hanya jika stok_id ada -->
                        @if ($d->stok_id)
                        <button class="btn btn-warning btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEditStok{{ $d->stok_id }}">
                            Edit
                        </button>
                        @endif

                        <!-- DELETE hanya jika stok_id ada -->
                        @if ($d->stok_id)
                        <form action="{{ route('cabang.stok.destroy', ['slug' => $gudang->slug, 'id' => $d->stok_id]) }}"
                              method="POST" class="d-inline">

                            @csrf
                            @method('DELETE')

                            <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus stok ini?')">
                                Hapus
                            </button>
                        </form>
                        @endif

                    </td> --}}
                </tr>

                <!-- MODAL EDIT STOK -->
                @if ($d->stok_id)
                <div class="modal fade" id="modalEditStok{{ $d->stok_id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <form action="{{ route('cabang.stok.update', ['slug' => $gudang->slug, 'id' => $d->stok_id]) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Stok</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">

                                    <div class="mb-3">
                                        <label class="form-label">Nama Bahan</label>
                                        <input type="text" class="form-control" value="{{ $d->nama_bahan }}" disabled>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Jumlah Stok</label>
                                        <input type="number" name="banyak_stok" class="form-control" min="0"
                                               value="{{ $d->banyak_stok }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Satuan</label>
                                        <input type="text" name="satuan" class="form-control"
                                               value="{{ $d->satuan_stok }}">
                                    </div>

                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button class="btn btn-primary">Simpan Perubahan</button>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
                @endif

                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">Belum ada data stok</td>
                </tr>
                @endforelse
                </tbody>

            </table>
        </div>
    </div>
</div>



<!-- MODAL TAMBAH STOK -->
<div class="modal fade" id="modalTambahStok" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="{{ route('cabang.stok.store', ['slug' => $gudang->slug]) }}" method="POST">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Tambah / Update Stok</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Nama Bahan</label>
                        <select name="bahanbaku_id" class="form-control" required>
                            <option value="">-- Pilih Bahan --</option>
                            @foreach ($barangs as $b)
                                <option value="{{ $b->id }}">{{ $b->nama_bahan }}</option>
                            @endforeach

                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah Stok</label>
                        <input type="number" name="banyak_stok" class="form-control" min="0" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Satuan</label>
                        <input type="text" name="satuan" class="form-control" placeholder="kg, pcs, liter, dll">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>

            </form>

        </div>
    </div>
</div>

@endsection
