@extends('layouts.app')

@push('style')
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- ✅ CDN untuk plugin yang kamu gunakan --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />

<style>
    .ui-autocomplete {
        position: absolute;
        top: 100%;
        left: 0;
        z-index: 1000;
        float: left;
        display: none;
        min-width: 160px;
        padding: 4px 0;
        margin: 2px 0 0 0;
        list-style: none;
        background-color: #fff;
        border: 1px solid rgba(0, 0, 0, 0.2);
        border-radius: 5px;
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
    }
</style>
@endpush

@section('content')

{{-- ❗ ALERT DEFAULT DIHAPUS (karena diganti SweetAlert)
--}}

<div class="row">
    {{-- 🔍 FILTER PANEL --}}
    <div class="col-md-3">
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0"><i class="fa fa-search"></i> Cari Transaksi</h6>
            </div>
            <form action="{{ route('transaksibahanbaku.index') }}" method="GET">
                <div class="card-body">
                    <div class="mb-3">
                        <input type="text" name="no" value="{{ $no }}" class="form-control" placeholder="Nomor Nota">
                    </div>
                    <div class="mb-3">
                        <input type="text" name="tanggal" id="tanggal" value="{{ $date }}" readonly class="form-control" placeholder="Tanggal">
                    </div>
                    <div class="mb-3">
                        <select class="form-select select2" name="namabahanbaku">
                            <option value="semua">Semua Bahan Baku</option>
                            @foreach ($bahanbakus as $bahanbaku)
                            <option value="{{ encrypt($bahanbaku->id) }}"
                                {{ ($namabahanbaku == $bahanbaku->id) ? 'selected' : '' }}>
                                {{ $bahanbaku->nama_bahan }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <select class="form-select select2" name="cabangtujuan">
                            <option value="semua">Semua Cabang</option>
                            @foreach ($cabangs as $cabang)
                            <option value="{{ encrypt($cabang->id) }}"
                                {{ ($cabangtujuan == $cabang->id) ? 'selected' : '' }}>
                                {{ $cabang->nama ?? $cabang->Nama_Cabang }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="fa fa-search"></i> Tampilkan
                    </button>
                    <a href="{{ route('transaksibahanbaku.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fa fa-refresh"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- 📋 DATA TABLE --}}
    <div class="col-md-9">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                <h6 class="mb-0"><i class="fa fa-shopping-cart"></i> Transaksi Bahan Baku</h6>
                <a href="{{ route('transaksibahanbaku.create') }}" class="btn btn-light btn-sm">
                    <i class="fa fa-plus"></i> Tambah
                </a>
            </div>

            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No.</th>
                            <th>Bahan Baku</th>
                            <th>Tanggal</th>
                            <th>Cabang Dari</th>
                            <th>Cabang Tujuan</th>
                            <th>Banyak</th>
                            <th>Satuan</th>
                            <th>Keterangan</th>
                            <th>Pembuat</th>
                            <th class="text-center" style="width:80px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($datas as $data)
                        <tr>
                            <td>#{{ $data->id }}</td>
                            <td>{{ $data->nama_bahan }}</td>
                            <td>{{ $data->tanggal }}</td>
                            <td>{{ $data->cabangdari }}</td>
                            <td>{{ $data->cabangtujuan }}</td>
                            <td>{{ $data->banyak }}</td>
                            <td>{{ $data->satuan }}</td>
                            <td>{{ $data->keterangan }}</td>
                            <td>{{ $data->username }}</td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('transaksibahanbaku.edit', encrypt($data->id)) }}" class="btn btn-success">
                                        <i class="fa fa-edit"></i>
                                    </a>

                                    {{-- ❗ BUTTON DELETE (diubah jadi SweetAlert)
                                    --}}
                                    <button type="button"
                                        class="btn btn-danger btn-delete"
                                        data-id="{{ encrypt($data->id) }}">
                                        <i class="fa fa-trash"></i>
                                    </button>

                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted">Belum ada transaksi</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-footer text-end">
                {{ $datas->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Plugin --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(function() {
        $('.select2').select2();
        $('#tanggal').datepicker({
            format: "dd-mm-yyyy",
            autoclose: true,
            todayHighlight: true
        });
    });

    // ============================
    // SWEETALERT DELETE ACTION
    // ============================
    $('.btn-delete').click(function(e) {
        e.preventDefault();

        let id = $(this).data('id');
        let url = "{{ route('transaksibahanbaku.destroy', ':id') }}";
        url = url.replace(':id', id);

        Swal.fire({
            title: 'Hapus Transaksi?',
            text: "Data yang dihapus tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $('<form>', {
                    'method': 'POST',
                    'action': url
                })
                .append('@csrf')
                .append('@method("DELETE")')
                .appendTo('body')
                .submit();
            }
        });
    });

    // ============================
    // SWEETALERT SESSION SUCCESS
    // ============================
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}"
        });
    @endif

    // ============================
    // SWEETALERT SESSION ERROR
    // ============================
    @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: "{{ session('error') }}"
        });
    @endif
</script>
@endpush
