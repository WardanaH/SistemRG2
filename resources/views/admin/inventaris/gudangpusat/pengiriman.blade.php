@extends('layouts.app')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center">
    <h3 class="page-title">📦 Pengiriman Barang - Gudang Pusat</h3>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahPengiriman">
        <i class="bi bi-plus-circle"></i> Tambah Pengiriman
    </button>
</div>

{{-- ALERT SUCCESS --}}
@if(session('success'))
    <div class="alert alert-success mt-2">{{ session('success') }}</div>
@endif

{{-- ALERT ERROR --}}
@if(session('error'))
    <div class="alert alert-danger mt-2">{{ session('error') }}</div>
@endif

{{-- VALIDASI ERROR --}}
@if($errors->any())
    <div class="alert alert-danger mt-2">
        <strong>Terjadi kesalahan!</strong>
        <ul class="mt-1 mb-0">
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card mt-3">
    <div class="card-body">

        <h4 class="card-title">Daftar Pengiriman</h4>

        <div class="table-responsive mt-3">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Bahan</th>
                        <th>Jumlah</th>
                        <th>Satuan</th>
                        <th>Cabang Tujuan</th>
                        <th>Tanggal Kirim</th>
                        <th>Status</th>
                        <th>Tanggal Diterima</th>
                        <th width="90px">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($pengiriman as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->bahanbaku->nama_bahan ?? '-' }}</td>
                    <td>{{ $item->jumlah }}</td>
                    <td>{{ $item->satuan }}</td>
                    <td>{{ $item->cabangTujuan->nama ?? '-' }}</td>

                    <td>{{ \Carbon\Carbon::parse($item->tanggal_pengiriman)->format('d M Y') }}</td>

                    {{-- ✅ STATUS DROPDOWN FINAL --}}
<td>
    @if($item->status_pengiriman === 'Diterima')
        {{-- ✅ JIKA SUDAH DITERIMA: TAMPIL TEKS SAJA --}}
        <span class="badge bg-success">Diterima</span>
    @else
        {{-- ✅ JIKA BELUM DITERIMA: BOLEH DROPDOWN --}}
        <form action="{{ route('gudangpusat.pengiriman.updateStatus', $item->id) }}"
              method="POST"
              class="form-update-status">
            @csrf
            @method('PUT')

            <select name="status_pengiriman"
                    class="select2 form-select-sm status-dropdown"
                    data-status="{{ $item->status_pengiriman }}">

                <option value="Dikemas"
                    {{ $item->status_pengiriman == 'Dikemas' ? 'selected' : '' }}>
                    Dikemas
                </option>

                <option value="Dikirim"
                    {{ $item->status_pengiriman == 'Dikirim' ? 'selected' : '' }}>
                    Dikirim
                </option>

            </select>
        </form>
    @endif
</td>


                    {{-- ✅ TANGGAL DITERIMA --}}
                    <td>
                        {{ $item->tanggal_diterima
                            ? \Carbon\Carbon::parse($item->tanggal_diterima)->format('d M Y')
                            : '-' }}
                    </td>

                    {{-- ✅ AKSI HAPUS SWEETALERT --}}
                    <td class="text-center">
                        <form action="{{ route('gudangpusat.pengiriman.destroy', $item->id) }}"
                            method="POST"
                            class="form-hapus d-inline">
                            @csrf
                            @method('DELETE')

                            <button type="button" class="btn btn-danger btn-sm btn-hapus">
                             Hapus
                            </button>
                        </form>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-muted">
                        Belum ada data pengiriman.
                    </td>
                </tr>
                @endforelse
                </tbody>

            </table>
        </div>
    </div>
</div>

{{-- ============================================================
MODAL TAMBAH PENGIRIMAN
============================================================ --}}
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

<div class="mb-3">
    <label class="form-label">Nama Barang</label>
    <select name="id_stok"
            class="select2 @error('id_stok') is-invalid @enderror"
            required>
        <option value="">-- Pilih Barang --</option>
        @foreach($barangs as $barang)
            <option value="{{ $barang->id }}">
                {{ $barang->bahanbaku->nama_bahan }} (Stok: {{ $barang->banyak_stok }})
            </option>
        @endforeach
    </select>
    @error('id_stok') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Jumlah</label>
    <input type="number"
           name="jumlah"
           min="1"
           class="form-control @error('jumlah') is-invalid @enderror"
           value="{{ old('jumlah') }}"
           required>
    @error('jumlah') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Tujuan Pengiriman</label>
    <select name="tujuan"
            class="select2 @error('tujuan') is-invalid @enderror"
            required>
        <option value="">-- Pilih Cabang --</option>
        @foreach($cabangs as $cabang)
            <option value="{{ $cabang->id }}">{{ $cabang->nama }}</option>
        @endforeach
    </select>
    @error('tujuan') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Tanggal Pengiriman</label>
    <input type="date"
           name="tanggal"
           class="form-control @error('tanggal') is-invalid @enderror"
           value="{{ old('tanggal') }}"
           required>
    @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    $('.select2').select2({
        width: '100%',
        dropdownParent: $('#modalTambahPengiriman')
    });
});

/* ✅ SWEETALERT UPDATE STATUS */
document.querySelectorAll('.status-dropdown').forEach(function(dropdown) {
    dropdown.addEventListener('change', function() {

        let form = this.closest('form');
        let statusLama = this.dataset.status;
        let statusBaru = this.value;

        Swal.fire({
            title: 'Ubah Status?',
            text: 'Dari "' + statusLama + '" ke "' + statusBaru + '"',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Ubah',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            } else {
                this.value = statusLama;
            }
        });
    });
});

/* ✅ SWEETALERT HAPUS */
document.querySelectorAll('.form-hapus').forEach(function(form) {
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Yakin?',
            text: 'Pengiriman akan dibatalkan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {

    $('.select2').select2({
        width: '120px' // biar rapi di tabel
    });

    $('.status-dropdown').each(function() {
        let locked = $(this).data('locked');

        if (locked == 1) {
            $(this).prop('disabled', true);
        }
    });

    $('.status-dropdown').on('change', function() {

        let select = $(this);
        let form = select.closest('form');
        let statusLama = select.data('status');
        let statusBaru = select.val();

        Swal.fire({
            title: 'Ubah Status?',
            text: 'Dari "' + statusLama + '" ke "' + statusBaru + '"',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Ubah',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            } else {
                select.val(statusLama).trigger('change.select2');
            }
        });
    });

});
</script>


@if($errors->any())
<script>
    new bootstrap.Modal(document.getElementById('modalTambahPengiriman')).show();
</script>
@endif

@endpush
