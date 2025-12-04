@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">

    {{-- ALERT SUCCESS --}}
    @if(session('success'))
        <div class="alert alert-success fw-semibold">
            {{ session('success') }}
        </div>
    @endif

    {{-- ALERT ERROR GLOBAL --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Terjadi kesalahan!</strong>
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- CARD --}}
    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center"
             style="background-color:#6f42c1; color:white;">
            <h5 class="mb-0 fw-semibold">Inventaris Kantor - {{ $cabang->nama }}</h5>

            <button class="btn btn-light btn-sm fw-semibold"
                    data-bs-toggle="modal"
                    data-bs-target="#modalTambah">
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
                    <th>QR Code</th>
                    <th>Aksi</th> {{-- TAMBAHAN --}}
                </tr>
                </thead>

                <tbody>
                @forelse ($data as $item)
                    <tr>
                        <td>{{ $item->kode_barang }}</td>
                        <td>{{ $item->nama_barang }}</td>
                        <td>{{ $item->jumlah }}</td>

                        <td>
                            @if($item->kondisi == 'Baik')
                                <span class="badge bg-success">{{ $item->kondisi }}</span>
                            @elseif($item->kondisi == 'Rusak')
                                <span class="badge bg-danger">{{ $item->kondisi }}</span>
                            @else
                                <span class="badge bg-warning text-dark">{{ $item->kondisi }}</span>
                            @endif
                        </td>

                        <td>
                            {{ $item->tanggal_input ? \Carbon\Carbon::parse($item->tanggal_input)->format('d M Y') : '-' }}
                        </td>

                        {{-- QR CODE --}}
                        <td>
                            @if($item->qr_code)
                                <img src="{{ asset('storage/' . $item->qr_code) }}"
                                     alt="QR Code"
                                     width="60"
                                     class="mb-1">

                                <br>

                                <a href="{{ asset('storage/' . $item->qr_code) }}"
                                   download
                                   class="btn btn-sm btn-outline-primary mt-1">
                                    Download
                                </a>
                            @else
                                <span class="text-muted">QR belum tersedia</span>
                            @endif
                        </td>

                        {{-- AKSI --}}
                        <td>

                            {{-- EDIT --}}
                            <button class="btn btn-sm btn-warning"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEdit{{ $item->id }}">
                                Edit
                            </button>

                            {{-- HAPUS --}}
                            <form action="{{ route('cabang.inventaris.destroy', ['slug' => $cabang->slug, 'id' => $item->id]) }}"
                                  method="POST"
                                  class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"
                                        onclick="return confirm('Yakin ingin menghapus?')">
                                    Hapus
                                </button>
                            </form>

                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            Belum ada data inventaris.
                        </td>
                    </tr>
                @endforelse
                </tbody>

            </table>
        </div>
    </div>

</div>

{{-- =======================================================
        MODAL TAMBAH BARANG
======================================================= --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">

            <form action="{{ route('cabang.inventaris.store', $cabang->slug) }}" method="POST">
                @csrf

                <div class="modal-header" style="background-color:#6f42c1;">
                    <h5 class="modal-title fw-semibold text-white">Tambah Inventaris</h5>
                    <button type="button" class="btn-close btn-close-white"
                            data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body bg-light">

                    <input type="hidden" name="cabang_id" value="{{ $cabang->id }}">

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Kode Barang</label>
                        <input type="text"
                               name="kode_barang"
                               class="form-control border-secondary"
                               value="{{ old('kode_barang') }}"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Nama Barang</label>
                        <input type="text"
                               name="nama_barang"
                               class="form-control border-secondary"
                               value="{{ old('nama_barang') }}"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Jumlah</label>
                        <input type="number"
                               name="jumlah"
                               class="form-control border-secondary"
                               value="{{ old('jumlah') }}"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Kondisi</label>
                        <select name="kondisi" class="select2 border-secondary">
                            <option value="Baik">Baik</option>
                            <option value="Rusak">Rusak</option>
                            <option value="Perlu Perbaikan">Perlu Perbaikan</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Tanggal Input</label>
                        <input type="date"
                               name="tanggal_input"
                               class="form-control border-secondary"
                               value="{{ old('tanggal_input') }}"
                               required>
                    </div>

                </div>

                <div class="modal-footer" style="background-color:#f8f9fa;">
                    <button type="button" class="btn btn-secondary px-4 fw-semibold"
                            data-bs-dismiss="modal">Batal</button>

                    <button type="submit"
                            class="btn text-white px-4 fw-semibold"
                            style="background-color:#6f42c1;">
                        Simpan
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

{{-- =======================================================
        MODAL EDIT BARANG (AUTO LOOP)
======================================================= --}}
@foreach($data as $item)
<div class="modal fade" id="modalEdit{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">

            <form action="{{ route('cabang.inventaris.update', ['slug' => $cabang->slug, 'id' => $item->id]) }}"
                  method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header" style="background-color:#ffb100;">
                    <h5 class="modal-title fw-semibold text-white">Edit Inventaris</h5>
                    <button type="button" class="btn-close btn-close-white"
                            data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body bg-light">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kode Barang</label>
                        <input type="text" name="kode_barang"
                               class="form-control"
                               value="{{ $item->kode_barang }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Barang</label>
                        <input type="text" name="nama_barang"
                               class="form-control"
                               value="{{ $item->nama_barang }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jumlah</label>
                        <input type="number" name="jumlah"
                               class="form-control"
                               value="{{ $item->jumlah }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kondisi</label>
                        <select name="kondisi" class="select2">
                            <option {{ $item->kondisi == 'Baik' ? 'selected' : '' }}>Baik</option>
                            <option {{ $item->kondisi == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                            <option {{ $item->kondisi == 'Perlu Perbaikan' ? 'selected' : '' }}>Perlu Perbaikan</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal Input</label>
                        <input type="date"
                               name="tanggal_input"
                               class="form-control"
                               value="{{ $item->tanggal_input }}" required>
                    </div>

                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">Batal</button>

                    <button type="submit" class="btn btn-warning text-white">
                        Simpan Perubahan
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
@endforeach


@endsection


{{-- =======================================================
        SCRIPT — AUTO OPEN MODAL KETIKA ERROR
======================================================= --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    @if ($errors->any())
    let modalTambah = new bootstrap.Modal(document.getElementById('modalTambah'));
    let modalEl = document.getElementById('modalTambah');

    modalTambah.show();

    modalEl.addEventListener('shown.bs.modal', function () {
        let firstError = document.querySelector('.is-invalid');
        if (firstError) firstError.focus();
    });
    @endif
});
</script>
@endpush

@push('styles')
<style>
.table-hover tbody tr:hover {
    background-color: #f5f1ff !important;
}
.modal-title {
    font-size: 1.1rem;
}
</style>
@endpush
