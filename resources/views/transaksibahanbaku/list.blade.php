@extends('layouts.app')

@push('style')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet" href="{{ asset('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('bower_components/select2/dist/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('bower_components/font-awesome/css/font-awesome.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/iCheck/all.css') }}">
<link rel="stylesheet" href="{{ asset('dist/css/skins/_all-skins.min.css') }}">
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


{{-- ✅ Alerts --}}
@if (session('success'))
<div class="alert alert-success alert-dismissible">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="icon fa fa-check"></i> {{ session('success') }}
</div>
@endif
@if (session('error'))
<div class="alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="icon fa fa-ban"></i> {{ session('error') }}
</div>
@endif

<div class="row">
    {{-- 🔍 FILTER PANEL --}}
    <div class="col-md-3">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Cari Transaksi</h3>
            </div>
            <form action="{{ route('transaksibahanbaku.index') }}" method="GET">
                <div class="box-body">
                    <div class="form-group">
                        <input type="text" name="no" value="{{ $no }}" class="form-control" placeholder="Nomor Nota">
                    </div>
                    <div class="form-group">
                        <input type="text" name="tanggal" id="tanggal" value="{{ $date }}" readonly class="form-control" placeholder="Tanggal">
                    </div>
                    <div class="form-group">
                        <select class="form-control select2" name="namabahanbaku" style="width:100%;">
                            <option value="semua">Semua Bahan Baku</option>
                            @foreach ($bahanbakus as $bahanbaku)
                            <option value="{{ encrypt($bahanbaku->id) }}"
                                {{ ($namabahanbaku == $bahanbaku->id) ? 'selected' : '' }}>
                                {{ $bahanbaku->nama_bahan }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <select class="form-control select2" name="cabangtujuan" style="width:100%;">
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
                <div class="box-footer text-right">
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="fa fa-search"></i> Tampilkan
                    </button>
                    <a href="{{ route('transaksibahanbaku.index') }}" class="btn btn-default btn-sm">
                        <i class="fa fa-refresh"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- 📋 DATA TABLE --}}
    <div class="col-md-9">
        <div class="box box-primary">
            <div class="box-header with-border d-flex justify-content-between">
                <h3 class="box-title">Transaksi Bahan Baku <i class="fa fa-shopping-cart"></i></h3>
                <a href="{{ route('transaksibahanbaku.create') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i> Tambah
                </a>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
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
                                <th>Aksi</th>
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
                                <td class="text-center" style="width:80px;">
                                    <div class="btn-group">
                                        <a href="{{ route('transaksibahanbaku.edit', encrypt($data->id)) }}" class="btn btn-success btn-xs">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <form action="{{ route('transaksibahanbaku.destroy', encrypt($data->id)) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
                                        </form>
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
            </div>

            <div class="box-footer">
                <ul class="pagination pagination-sm no-margin pull-right">
                    {{ $datas->appends(request()->query())->links() }}
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(function() {
        $('.select2').select2();
        $('#tanggal').datepicker({
            format: "dd-mm-yyyy",
            autoclose: true
        });
    });
</script>
@endpush
@endsection
