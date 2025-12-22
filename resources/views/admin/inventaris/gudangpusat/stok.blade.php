@extends('admin.inventaris.gudangpusat.layout.app')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center">
    <h3 class="page-title">Stok Bahan Baku – Cabang {{ ucfirst($gudang->nama) }}</h3>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahStok">
        <i class="bi bi-plus-circle"></i> Tambah Stok
    </button>
</div>

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
                    </tr>
                </thead>

                <tbody>
                @forelse ($datas as $index => $d)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $d->nama_bahan }}</td>
                    <td>{{ $d->banyak_stok }}</td>
                    <td>{{ $d->satuan_stok ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">
                        Belum ada data stok
                    </td>
                </tr>
                @endforelse
                </tbody>

            </table>
        </div>
    </div>
</div>

<!-- =======================
MODAL TAMBAH / UPDATE STOK
======================= -->
<div class="modal fade" id="modalTambahStok" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="{{ route('stok.pusat.tambah') }}" method="POST">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Tambah Stok</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    {{-- PILIH BAHAN --}}
                    <div class="mb-3">
                        <label class="form-label">Nama Bahan</label>
                        <select name="bahanbaku_id"
                                id="selectBahan"
                                class="form-control select2"
                                required>
                            <option value="">-- Pilih Bahan --</option>
                            @foreach ($barangs as $b)
                                <option value="{{ $b->id }}"
                                        data-satuan="{{ $b->satuan }}">
                                    {{ $b->nama_bahan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- JUMLAH --}}
                    <div class="mb-3">
                        <label class="form-label">Jumlah Stok</label>
                        <input type="number"
                               name="banyak_stok"
                               class="form-control"
                               min="0"
                               required>
                    </div>

                    {{-- SATUAN OTOMATIS --}}
                    <div class="mb-3">
                        <label class="form-label">Satuan</label>
                        <input type="text"
                               name="satuan"
                               id="inputSatuan"
                               class="form-control"
                               readonly>
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

@push('scripts')
<script>
$(document).ready(function () {

    // init select2 (kalau belum)
    $('#selectBahan').select2({
        dropdownParent: $('#modalTambahStok'),
        width: '100%'
    });

    // ambil satuan saat bahan dipilih
    $('#selectBahan').on('select2:select', function (e) {
        const data = e.params.data.element;
        const satuan = $(data).data('satuan');

        $('#inputSatuan').val(satuan ?? '');
    });

    // reset saat modal ditutup
    $('#modalTambahStok').on('hidden.bs.modal', function () {
        $('#selectBahan').val(null).trigger('change');
        $('#inputSatuan').val('');
    });

    // sweetalert
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '{{ session('success') }}',
            timer: 2000,
            showConfirmButton: false
        });
    @endif
});
</script>
@endpush

