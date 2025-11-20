@extends('layouts.app')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center">
    <h3 class="page-title">📦 Pengiriman Barang - Gudang Pusat</h3>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahPengiriman">
        <i class="bi bi-plus-circle"></i> Tambah Pengiriman
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

        <h4 class="card-title">Daftar Pengiriman</h4>

        <div class="table-responsive mt-3">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Tujuan</th>
                        <th>Tanggal</th>
                        <th>Status Pengiriman</th>
                        <th>Status Penerimaan</th>
                        <th width="90px">Aksi</th>
                    </tr>
                </thead>
                <tbody>

                    @forelse($pengiriman as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>

                        {{-- Relasi barang --}}
                        <td>{{ $item->barang->nama_barang ?? '-' }}</td>

                        <td>{{ $item->jumlah }}</td>

                        {{-- Slug cabang --}}
                        <td>{{ ucfirst($item->tujuan_pengiriman) }}</td>

                        <td>{{ \Carbon\Carbon::parse($item->tanggal_pengiriman)->format('d M Y') }}</td>

                        <td>
                            <form action="{{ route('gudangpusat.pengiriman.updateStatus', $item->id_pengiriman) }}"
                                  method="POST">
                                @csrf
                                @method('PUT')

                                <select name="status_pengiriman"
                                        class="form-select form-select-sm"
                                        onchange="this.form.submit()"
                                        {{ $item->status_pengiriman != 'Dikemas' ? 'disabled' : '' }}>
                                    <option value="Dikemas" {{ $item->status_pengiriman == 'Dikemas' ? 'selected' : '' }}>
                                        Dikemas
                                    </option>
                                    <option value="Dikirim" {{ $item->status_pengiriman == 'Dikirim' ? 'selected' : '' }}>
                                        Dikirim
                                    </option>
                                </select>
                            </form>
                        </td>

                        <td>{{ $item->status_penerimaan ?? '-' }}</td>

                        <td>
                            <form action="{{ route('gudangpusat.pengiriman.destroy', $item->id_pengiriman) }}"
                                  method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus pengiriman ini?')">

                                @csrf
                                @method('DELETE')

                                <button class="btn btn-danger btn-sm"
                                        {{ $item->status_pengiriman != 'Dikemas' ? 'disabled' : '' }}>
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">Belum ada data pengiriman.</td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

    </div>
</div>


{{-- ===========================
        MODAL TAMBAH PENGIRIMAN
=========================== --}}
<div class="modal fade" id="modalTambahPengiriman" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="{{ route('gudangpusat.pengiriman.store') }}" method="POST">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Tambah Pengiriman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    {{-- Pilih barang --}}
                    <div class="mb-3">
                        <label class="form-label">Nama Barang</label>
                        <select name="id_barang" class="form-control" required>
                            <option value="">-- Pilih Barang --</option>

                            @foreach($barangs as $barang)
                                <option value="{{ $barang->id_barang }}">
                                    {{ $barang->nama_barang }} (Stok: {{ $barang->stok }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Jumlah --}}
                    <div class="mb-3">
                        <label class="form-label">Jumlah</label>
                        <input type="number" name="jumlah" class="form-control" min="1" required>
                    </div>

                    {{-- Tujuan --}}
                    <div class="mb-3">
                        <label class="form-label">Tujuan Pengiriman</label>
                        <select name="tujuan_pengiriman" class="form-control" required>
                            <option value="">-- Pilih Cabang --</option>

                            @foreach($cabangs as $cabang)
                                <option value="{{ $cabang->slug }}">
                                    {{ ucfirst($cabang->nama) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tanggal --}}
                    <div class="mb-3">
                        <label class="form-label">Tanggal Pengiriman</label>
                        <input type="date" name="tanggal_pengiriman" class="form-control" required>
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
