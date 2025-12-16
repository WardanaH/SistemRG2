@extends('layouts.app')

@push('style')
{{-- CSS tambahan (optional, jika butuh select2 styling) --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    <strong>✅ Sukses!</strong> {{ session('success') }}
</div>
@endif

@if (session('error'))
<div class="alert alert-danger alert-dismissible">
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    <strong>❌ Gagal!</strong> {{ session('error') }}
</div>
@endif

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Tambah Transaksi Bahan Baku</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('transaksibahanbaku.store') }}">
            @csrf

            {{-- Bahan Baku --}}
            <div class="mb-3">
                <label class="form-label">Bahan Baku</label>
                <select class="form-select select2" id="bahanbaku_transaksibahanbaku" name="bahanbaku_transaksibahanbaku" required>
                    <option value="" selected disabled>Pilih Bahan Baku</option>
                    @foreach ($bahanbakus as $bahanbaku)
                    <option value="{{ encrypt($bahanbaku->id) }}">{{ $bahanbaku->nama_bahan }}</option>
                    @endforeach
                </select>
                @error('bahanbaku_transaksibahanbaku')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Cabang Tujuan --}}
            <div class="mb-3">
                <label class="form-label">Cabang Tujuan</label>
                <select class="form-select select2" id="cabangtujuan_transaksibahanbaku" name="cabangtujuan_transaksibahanbaku" required>
                    <option value="" selected disabled>Pilih Cabang Tujuan</option>
                    @foreach ($cabangs as $cabang)
                    <option value="{{ encrypt($cabang->id) }}">{{ $cabang->nama }}</option>
                    @endforeach
                </select>
                @error('cabangtujuan_transaksibahanbaku')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Banyak --}}
            <div class="mb-3">
                <label class="form-label">Banyak</label>
                <input type="number" class="form-control" id="banyak_transaksibahanbaku" name="banyak_transaksibahanbaku" placeholder="Jumlah bahan" min="1" required>
                @error('banyak_transaksibahanbaku')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Satuan --}}
            <div class="mb-3">
                <label class="form-label">Satuan</label>
                <input type="text" class="form-control" id="satuan_transaksibahanbaku" name="satuan_transaksibahanbaku" disabled placeholder="Satuan">
            </div>

            {{-- Keterangan --}}
            <div class="mb-3">
                <label class="form-label">Keterangan</label>
                <textarea class="form-control" id="keterangan_transaksibahanbaku" name="keterangan_transaksibahanbaku" rows="3" placeholder="Opsional..."></textarea>
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">💾 Simpan</button>
                <a href="{{ route('transaksibahanbaku.index') }}" class="btn btn-danger">↩️ Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
{{-- CDN Select2 & jQuery sudah di layout, jadi cukup load JS-nya --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {

        // Aktifkan Select2
        $('.select2').select2();


        // Jika bahan baku dipilih, load satuan otomatis
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
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Memuat Satuan',
                        text: 'Terjadi kesalahan saat mengambil data.'
                    });
                    console.error('Gagal memuat satuan bahan baku:', error);
                    console.log(xhr.responseText);
                }
            });
        });

    });
</script>

{{-- SweetAlert untuk ERROR --}}
@if (session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: "{{ session('error') }}"
    });
</script>
@endif

{{-- SweetAlert untuk SUCCESS --}}
@if (session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: "{{ session('success') }}"
    });
</script>
@endif

@endpush
