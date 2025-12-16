@extends('layouts.app')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center">
    <h3 class="page-title">Data Barang - Cabang {{ ucfirst($cabangData->nama) }}</h3>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahBarang">
        <i class="bi bi-plus-circle"></i> Tambah Barang
    </button>
</div>

@if(session('success'))
    <div class="alert alert-success mt-2">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger mt-2">{{ session('error') }}</div>
@endif

<div class="card mt-3">
    <div class="card-body">
        <h4 class="card-title">Daftar Barang</h4>

        <div class="table-responsive">
            <table class="table table-striped text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($barangs as $index => $barang)

                        @php
                            $stok = $barang->stok ?? 0;

                            if ($stok == 0) {
                                $status = 'Habis';
                                $badgeClass = 'bg-danger';
                            } elseif ($stok < 100) {
                                $status = 'Hampir Habis';
                                $badgeClass = 'bg-warning text-dark';
                            } elseif ($stok < 300) {
                                $status = 'Cukup';
                                $badgeClass = 'bg-info text-dark';
                            } else {
                                $status = 'Masih Banyak';
                                $badgeClass = 'bg-success';
                            }
                        @endphp

                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $barang->nama_barang }}</td>
                            <td>Rp {{ number_format($barang->harga, 0, ',', '.') }}</td>
                            <td>{{ $barang->stok }}</td>
                            <td><span class="badge {{ $badgeClass }}">{{ $status }}</span></td>

                            <td>
                                <!-- Edit -->
                                <button class="btn btn-warning btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditBarang{{ $barang->id_barang }}">
                                    Edit
                                </button>

                                <!-- Delete -->
                                <form
                                    action="{{ route('cabang.barang.destroy', ['slug' => $cabangData->slug, 'id' => $barang->id_barang]) }}"
                                    method="POST"
                                    class="d-inline">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Yakin ingin menghapus barang ini?')">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Modal Edit -->
                        <div class="modal fade"
                             id="modalEditBarang{{ $barang->id_barang }}"
                             tabindex="-1">

                            <div class="modal-dialog">
                                <div class="modal-content">

                                    <form action="{{ route('cabang.barang.update', [
                                        'slug' => $cabangData->slug,
                                        'id' => $barang->id_barang
                                    ]) }}" method="POST">

                                        @csrf
                                        @method('PUT')

                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Barang</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Nama Barang</label>
                                                <input type="text" name="nama_barang"
                                                       class="form-control"
                                                       value="{{ $barang->nama_barang }}" required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Harga</label>
                                                <input type="number" name="harga"
                                                       class="form-control"
                                                       value="{{ $barang->harga }}">
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Stok</label>
                                                <input type="number" name="stok"
                                                       class="form-control"
                                                       value="{{ $barang->stok }}" required>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Batal</button>

                                            <button type="submit" class="btn btn-primary">
                                                Simpan Perubahan
                                            </button>
                                        </div>

                                    </form>

                                </div>
                            </div>

                        </div>
                        <!-- END MODAL EDIT -->

                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                Belum ada barang
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambahBarang" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="{{ route('cabang.barang.store', ['slug' => $cabangData->slug]) }}"
                  method="POST">

                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Tambah Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" name="nama_barang" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Harga</label>
                        <input type="number" name="harga" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Stok</label>
                        <input type="number" name="stok" class="form-control" required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>

            </form>

        </div>
    </div>
</div>

@endsection
