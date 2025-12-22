@extends('admin.inventaris.templateinventaris.layout_cabang.app')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center">
    <h3>Data Bahan Baku - {{ ucfirst($cabang->nama) }}</h3>
    {{-- <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahBarang">
        <i class="bi bi-plus-circle"></i> Tambah Bahan
    </button> --}}
</div>

@if(session('success'))
    <div class="alert alert-success mt-2">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger mt-2">{{ session('error') }}</div>
@endif

<div class="card mt-3">
    <div class="card-body">
        <!-- <h4 class="card-title">Daftar Bahan Baku</h4> -->

        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center align-middle styletable">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Satuan</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Status</th>
                        {{-- <th>Aksi</th> --}}
                    </tr>
                </thead>

                <tbody>
                    @forelse ($datas as $index => $item)

                        @php
                            $stok = $item->banyak_stok ?? 0;

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
                            <td>{{ $item->nama_bahan }}</td>
                            <td>{{ $item->Nama_Kategori }}</td>
                            <td>{{ $item->satuan }}</td>
                            <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td>{{ $stok }}</td>
                            <td><span class="badge {{ $badgeClass }}">{{ $status }}</span></td>
                        </tr>

                        <!-- MODAL EDIT -->
                        <div class="modal fade" id="modalEditBarang{{ $item->id_bahanbaku }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">

                                    <form action="{{ route('cabang.barang.cabang.update', [
                                        'slug' => $cabang->slug,
                                        'id' => $item->id_bahanbaku
                                    ]) }}" method="POST">

                                        @csrf
                                        @method('PUT')

                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Bahan Baku</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">

                                            <div class="mb-3">
                                                <label class="form-label">Kategori</label>
                                                <select name="kategori_id" class="select2" required>
                                                    @foreach($kategori as $kat)
                                                        <option value="{{ $kat->id }}"
                                                            {{ $item->kategori_id == $kat->id ? 'selected' : '' }}>
                                                            {{ $kat->Nama_Kategori }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Nama Bahan</label>
                                                <input type="text" name="nama_bahan"
                                                       class="form-control"
                                                       value="{{ $item->nama_bahan }}" required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Harga</label>
                                                <input type="number" name="harga"
                                                       class="form-control"
                                                       value="{{ $item->harga }}">
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Satuan</label>
                                                <select name="satuan" class="select2" required>
                                                    <option value="PCS" {{ $item->satuan == 'PCS' ? 'selected' : '' }}>PCS</option>
                                                    <option value="PAKET" {{ $item->satuan == 'PAKET' ? 'selected' : '' }}>PAKET</option>
                                                    <option value="CENTIMETER" {{ $item->satuan == 'CENTIMETER' ? 'selected' : '' }}>CENTIMETER</option>
                                                    <option value="METER" {{ $item->satuan == 'METER' ? 'selected' : '' }}>METER</option>
                                                    <option value="KG" {{ $item->satuan == 'KG' ? 'selected' : '' }}>KG</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Stok</label>
                                                <input type="number" name="stok"
                                                       class="form-control"
                                                       value="{{ $stok }}" required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Batas Stok</label>
                                                <input type="number" name="batas_stok"
                                                       class="form-control"
                                                       value="{{ $item->batas_stok }}">
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Keterangan</label>
                                                <textarea name="keterangan" class="form-control">{{ $item->keterangan }}</textarea>
                                            </div>

                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                Batal
                                            </button>

                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>

                                    </form>

                                </div>
                            </div>
                        </div>
                        <!-- END MODAL EDIT -->

                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Belum ada bahan baku</td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

    </div>
</div>


<!-- MODAL TAMBAH -->
<div class="modal fade" id="modalTambahBarang" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="{{ route('cabang.barang.cabang.store', ['slug' => $cabang->slug]) }}" method="POST">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Tambah Bahan Baku</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select name="kategori_id" class="select2" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategori as $kat)
                                <option value="{{ $kat->id }}">{{ $kat->Nama_Kategori }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Bahan</label>
                        <input type="text" name="nama_bahan" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Harga</label>
                        <input type="number" name="harga" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Satuan</label>
                        <select name="satuan" class="select2" required>
                            <option value="">-- Pilih Satuan --</option>
                            <option value="PCS">PCS</option>
                            <option value="PAKET">PAKET</option>
                            <option value="CENTIMETER">CENTIMETER</option>
                            <option value="METER">METER</option>
                            <option value="KG">KG</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Stok</label>
                        <input type="number" name="stok" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Batas Stok</label>
                        <input type="number" name="batas_stok" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control"></textarea>
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
