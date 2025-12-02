@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
  <div class="card shadow">
    <div class="card-header d-flex justify-content-between align-items-center" style="background-color:#6f42c1; color:white;">
      <h5 class="mb-0 fw-semibold">
        Inventaris Kantor - Cabang Banjarbaru
      </h5>
      <button class="btn btn-light btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#modalTambah">
        + Tambah Barang
      </button>
    </div>

    <div class="card-body bg-light">
      <table class="table table-bordered table-hover align-middle text-center shadow-sm">
        <thead style="background-color:#e9d8fd; color:#4b0082;">
          <tr>
            <th>Kode</th>
            <th>Nama Barang</th>
            <th>Jumlah</th>
            <th>Kondisi</th>
            <th>Tanggal Input</th>
          </tr>
        </thead>
        <tbody>
          @forelse($data ?? [] as $item)
          <tr>
            <td>{{ $item->kode_barang }}</td>
            <td>{{ $item->nama_barang }}</td>
            <td>{{ $item->jumlah }}</td>
            <td>
              @if($item->kondisi === 'Baik')
                <span class="badge bg-success">{{ $item->kondisi }}</span>
              @elseif($item->kondisi === 'Rusak')
                <span class="badge bg-danger">{{ $item->kondisi }}</span>
              @else
                <span class="badge bg-warning text-dark">{{ $item->kondisi }}</span>
              @endif
            </td>
            <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</td>
          </tr>
          @empty
          <tr>
            <td colspan="5" class="text-center text-muted">Belum ada data inventaris.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Tambah Data -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <form action="{{ route('cabang.inventaris.store', $cabang->slug) }}" method="POST">
        @csrf

        <!-- Header -->
        <div class="modal-header" style="background-color:#6f42c1;">
          <h5 class="modal-title fw-semibold text-white" id="modalTambahLabel">
            Tambah Inventaris
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Body -->
        <div class="modal-body bg-light">
          <input type="hidden" name="cabang_id" value="{{ $cabang->id ?? 1 }}">

          <div class="mb-3">
            <label class="form-label fw-semibold text-dark">Kode Barang</label>
            <input type="text" name="kode_barang" class="form-control bg-white text-dark border-secondary" placeholder="Masukkan kode barang" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold text-dark">Nama Barang</label>
            <input type="text" name="nama_barang" class="form-control bg-white text-dark border-secondary" placeholder="Masukkan nama barang" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold text-dark">Jumlah</label>
            <input type="number" name="jumlah" class="form-control bg-white text-dark border-secondary" placeholder="Masukkan jumlah" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold text-dark">Kondisi</label>
            <select name="kondisi" class="form-select bg-white text-dark border-secondary">
              <option value="Baik" selected>Baik</option>
              <option value="Rusak">Rusak</option>
              <option value="Perlu Perbaikan">Perlu Perbaikan</option>
            </select>
          </div>
        </div>

        <!-- Footer -->
        <div class="modal-footer" style="background-color:#f8f9fa;">
          <button type="button" class="btn btn-secondary px-4 fw-semibold" data-bs-dismiss="modal">
            Batal
          </button>
          <button type="submit" class="btn text-white px-4 fw-semibold" style="background-color:#6f42c1;">
            Simpan
          </button>
        </div>

      </form>
    </div>
  </div>
</div>

@endsection

@push('styles')
<style>
  .btn.btn-primary:hover, .btn.text-white:hover {
    background-color:#5a35a0 !important;
  }
  .form-control::placeholder {
    color: #6c757d !important;
    opacity: 0.8;
  }
  .table-hover tbody tr:hover {
    background-color: #f5f1ff !important;
  }
  .modal-title {
    font-size: 1.1rem;
  }
</style>
@endpush
