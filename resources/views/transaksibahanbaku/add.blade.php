@extends('layouts.app')

@push('style')
<link rel="stylesheet" href="{{ asset('bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet" href="{{ asset('bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/timepicker/bootstrap-timepicker.min.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
<link rel="stylesheet" href="{{ asset('bower_components/Ionicons/css/ionicons.min.css') }}">
<link rel="stylesheet" href="{{ asset('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/iCheck/all.css') }}">
<link rel="stylesheet" href="{{ asset('bower_components/select2/dist/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/iCheck/square/blue.css') }}">
<link rel="stylesheet" href="{{ asset('dist/css/skins/_all-skins.min.css') }}">

<style>
    select,
    option {
        font-family: 'FontAwesome', Helvetica;
    }
</style>
@endpush

@section('content')
@if (session('success'))
<div class="alert alert-success alert-dismissible">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <h4><i class="icon fa fa-check"></i> Sukses</h4>
    {{ session('success') }}
</div>
@endif

@if (session('error'))
<div class="alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <h4><i class="icon fa fa-ban"></i> Gagal</h4>
    {{ session('error') }}
</div>
@endif

<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Tambah Transaksi Bahan Baku</h3>
            </div>

            <form method="POST" action="{{ route('transaksibahanbaku.store') }}">
                @csrf
                <div class="box-body">
                    {{-- Bahan Baku --}}
                    <div class="form-group {{ $errors->has('bahanbaku_transaksibahanbaku') ? 'has-error' : '' }}">
                        <label>Bahan Baku</label>
                        <select class="form-control select2" id="bahanbaku_transaksibahanbaku" name="bahanbaku_transaksibahanbaku" required>
                            <option value="" selected disabled>Pilih Bahan Baku</option>
                            @foreach ($bahanbakus as $bahanbaku)
                            <option value="{{ encrypt($bahanbaku->id) }}">{{ $bahanbaku->nama_bahan }}</option>
                            @endforeach
                        </select>
                        @error('bahanbaku_transaksibahanbaku')
                        <span class="help-block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Cabang Tujuan --}}
                    <div class="form-group {{ $errors->has('cabangtujuan_transaksibahanbaku') ? 'has-error' : '' }}">
                        <label>Cabang Tujuan</label>
                        <select class="form-control select2" id="cabangtujuan_transaksibahanbaku" name="cabangtujuan_transaksibahanbaku" required>
                            <option value="" selected disabled>Pilih Cabang Tujuan</option>
                            @foreach ($cabangs as $cabang)
                            <option value="{{ encrypt($cabang->id) }}">{{ $cabang->nama }}</option>
                            @endforeach
                        </select>
                        @error('cabangtujuan_transaksibahanbaku')
                        <span class="help-block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Banyak --}}
                    <div class="form-group {{ $errors->has('banyak_transaksibahanbaku') ? 'has-error' : '' }}">
                        <label>Banyak</label>
                        <input type="number" class="form-control" id="banyak_transaksibahanbaku" name="banyak_transaksibahanbaku" placeholder="Jumlah bahan" min="1" required>
                        @error('banyak_transaksibahanbaku')
                        <span class="help-block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Satuan --}}
                    <div class="form-group">
                        <label for="satuan_transaksibahanbaku">Satuan</label>
                        <input type="text" class="form-control" id="satuan_transaksibahanbaku" name="satuan_transaksibahanbaku" disabled placeholder="Satuan">
                    </div>

                    {{-- Keterangan --}}
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea class="form-control" id="keterangan_transaksibahanbaku" name="keterangan_transaksibahanbaku" rows="3" placeholder="Opsional..."></textarea>
                    </div>
                </div>

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">💾 Simpan</button>
                    <a href="{{ route('transaksibahanbaku.index') }}" class="btn btn-danger">↩️ Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2();

        $('#bahanbaku_transaksibahanbaku').on('change', function() {
            const id = $(this).val();

            if (!id) return;

            $.ajax({
                type: 'GET',
                url: '{{ route("loadbahanbaku") }}',
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    console.log('Response:', response);
                    if (response.success && response.satuan) {
                        $('#satuan_transaksibahanbaku').val(response.satuan);
                    } else {
                        $('#satuan_transaksibahanbaku').val('');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Gagal memuat satuan bahan baku:', error);
                    console.log(xhr.responseText);
                }
            });
        });
    });
</script>
@endpush

@endsection
