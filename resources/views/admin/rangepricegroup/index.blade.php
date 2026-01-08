@extends('layouts.app')

@section('content')
<div class="container-fluid">

@if(!$specialPriceGroup)
    <div class="alert alert-info">
        <strong>Info:</strong> Silakan buat / pilih <b>Special Price Group</b> terlebih dahulu.
    </div>
@else

@php
    $spgId = encrypt($specialPriceGroup->id);
@endphp

<div class="row">

    {{-- INPUT --}}
    <div class="col-md-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Input Range Harga Pelanggan</h5>
            </div>

            <div class="card-body">
                <label class="fw-bold">Jenis Pelanggan</label>

                @foreach ($jenispelanggans as $jp)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox">
                        <label class="form-check-label">
                            {{ $jp->jenis_pelanggan }}
                        </label>
                    </div>
                @endforeach

                <hr>

                <button class="btn btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#modalTambah">
                    Tambah Range Harga
                </button>
            </div>
        </div>
    </div>

    {{-- LIST --}}
    <div class="col-md-7">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Daftar Range Harga</h5>
            </div>

            <div class="card-body">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Produk</th>
                            <th>Qty</th>
                            <th>Harga</th>
                            <th width="80">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="listRange">
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                Memuat data...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- MODAL --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white">Tambah Range Harga</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="mb-2">
                    <label class="fw-bold">Produk</label>
                    <select id="produk" class="form-control select2">
                        <option value="">Pilih Produk</option>
                        @foreach ($produks as $p)
                            <option value="{{ $p->id }}">{{ $p->nama_produk }}</option>
                        @endforeach
                    </select>
                </div>

                <input type="number" id="nilai_awal" class="form-control mb-2" placeholder="Qty Awal">
                <input type="number" id="nilai_akhir" class="form-control mb-2" placeholder="Qty Akhir">
                <input type="number" id="harga_khusus" class="form-control" placeholder="Harga">
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" id="btnTambah">
                    Simpan
                </button>
            </div>

        </div>
    </div>
</div>

@endif
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if($specialPriceGroup)
<script>
let dataRange = []

/* ================= INIT ================= */
$(document).ready(function () {

    $('#produk').select2({
        dropdownParent: $('#modalTambah'),
        width: '100%',
        placeholder: 'Pilih Produk'
    })

    loadData()
})

/* ================= LOAD ================= */
function loadData(){
    $.get("{{ route('rangepricegroup.data', $spgId) }}", function(res){
        dataRange = res
        render()
    })
}

/* ================= RENDER ================= */
function render(){
    $('#listRange').html('')

    if(dataRange.length === 0){
        $('#listRange').html(
            `<tr><td colspan="4" class="text-center">Data belum ada</td></tr>`
        )
        return
    }

    dataRange.forEach(item => {
        $('#listRange').append(`
            <tr>
                <td>${item.produk?.nama_produk ?? '-'}</td>
                <td>${item.nilai_awal} - ${item.nilai_akhir}</td>
                <td>${item.harga_khusus}</td>
                <td>
                    <button class="btn btn-danger btn-sm"
                        onclick="hapus('${item.uniq_id}')">
                        Hapus
                    </button>
                </td>
            </tr>
        `)
    })
}

/* ================= SIMPAN ================= */
$(document).on('click', '#btnTambah', function () {

    let produk = $('#produk').val()
    let awal = $('#nilai_awal').val()
    let akhir = $('#nilai_akhir').val()
    let harga = $('#harga_khusus').val()

    if (!produk || !awal || !akhir || !harga) {
        Swal.fire({
            icon: 'warning',
            title: 'Lengkapi Data',
            text: 'Semua field wajib diisi'
        })
        return
    }

    Swal.fire({
        title: 'Simpan data?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Simpan',
        cancelButtonText: 'Batal'
    }).then((result) => {

        if (!result.isConfirmed) return

        $.post("{{ route('rangepricegroup.store', $spgId) }}", {
            _token: "{{ csrf_token() }}",
            produk_id: produk,
            nilai_awal: awal,
            nilai_akhir: akhir,
            harga_khusus: harga
        }).done(res => {

            dataRange.push(res)
            render()

            $('#modalTambah').modal('hide')
            $('#produk').val(null).trigger('change')
            $('#nilai_awal,#nilai_akhir,#harga_khusus').val('')

            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Range harga berhasil disimpan',
                timer: 1200,
                showConfirmButton: false
            })
        })
    })
})

/* ================= HAPUS ================= */
function hapus(id){

    Swal.fire({
        title: 'Hapus data?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {

        if (!result.isConfirmed) return

        $.ajax({
            url: "{{ route('rangepricegroup.destroy', [$spgId, 'RID']) }}".replace('RID', id),
            type: 'DELETE',
            data: {_token: "{{ csrf_token() }}"}
        }).done(() => {

            dataRange = dataRange.filter(r => r.uniq_id !== id)
            render()

            Swal.fire({
                icon: 'success',
                title: 'Dihapus',
                timer: 1000,
                showConfirmButton: false
            })
        })
    })
}
</script>
@endif
@endpush
