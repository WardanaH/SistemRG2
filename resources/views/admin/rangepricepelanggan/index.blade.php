@extends('layouts.app')

@section('content')
<div class="container-fluid">

<form id="formprice">
@csrf

<div class="row">

    {{-- ================= LEFT ================= --}}
    <div class="col-md-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Input Harga Pelanggan</h5>
            </div>

            <div class="card-body">

                {{-- PILIH PRODUK --}}
                <div class="mb-3">
                    <select class="form-select select2"
                            id="pilih_produk">
                        <option disabled selected>Pilih Produk</option>
                        @foreach ($produks as $produk)
                            <option value="{{ encrypt($produk->id) }}">
                                {{ $produk->nama_produk }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- HARGA KHUSUS --}}
                <div class="mb-3">
                    <input type="number"
                           id="harga_khusus"
                           class="form-control"
                           placeholder="Harga Khusus">
                </div>

                {{-- BUTTON --}}
                <div class="d-flex gap-2">
                    <button type="button"
                            class="btn btn-primary"
                            id="btnModalPelanggan"
                            data-bs-toggle="modal"
                            data-bs-target="#modalPelanggan"
                            disabled>
                        Tambah Pelanggan
                    </button>

                    <button type="button"
                            class="btn btn-secondary"
                            id="btnReset">
                        Reset
                    </button>

                    <button type="button"
                            class="btn btn-success ms-auto"
                            id="btnSimpan"
                            disabled>
                        Simpan
                    </button>
                </div>

            </div>
        </div>
    </div>

    {{-- ================= RIGHT ================= --}}
    <div class="col-md-7">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">Preview Harga Pelanggan</h5>
            </div>

            <div class="card-body">
                <table class="table table-bordered text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Pelanggan</th>
                            <th>Pemilik</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tablePreview">
                        <tr>
                            <td colspan="4">Belum ada data</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
</form>
</div>

{{-- ================= MODAL TAMBAH PELANGGAN ================= --}}
<div class="modal fade" id="modalPelanggan" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Tambah Pelanggan</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <select class="form-select select2"
                        id="pelanggan"
                        data-title="Pilih Pelanggan"
                        style="width:100%">
                    <option disabled selected>Pilih Pelanggan</option>
                    @foreach ($pelanggans as $p)
                        <option value="{{ encrypt($p->id) }}"
                                data-pemilik="{{ $p->nama_pemilik }}">
                            {{ $p->nama_perusahaan }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary"
                        data-bs-dismiss="modal">
                    Batal
                </button>
                <button class="btn btn-success"
                        id="btnTambahPelanggan">
                    Tambah
                </button>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let pelanggan_ids = []


$('#pilih_produk').on('change', function () {

    let produk_id = $(this).val()

    if (!produk_id) return

    $('#btnModalPelanggan').prop('disabled', false)

    $.get('/rangepricepelanggan/load-special/' + produk_id, function (res) {

        if (res.length === 0) {
            $('#tablePreview').html(
                `<tr><td colspan="4">Belum ada data</td></tr>`
            )
            return
        }

        let html = ''
        res.forEach(item => {
            html += renderRow({
                id: item.pelanggan_id,
                nama_perusahaan: item.pelanggan.nama_perusahaan,
                nama_pemilik: item.pelanggan.nama_pemilik,
                harga_khusus: item.harga_khusus
            })
        })

        $('#tablePreview').html(html)
    })
})



/* ================= RESET ================= */
$('#btnReset').on('click', function () {
    pelanggan_id = null
    $('#tablePreview').html(`<tr><td colspan="4">Belum ada data</td></tr>`)
    $('#pilih_produk').val(null).trigger('change')
    $('#harga_khusus').val('')
    $('#btnModalPelanggan').prop('disabled', true)
    $('#btnSimpan').prop('disabled', true)
})

/* ================= TAMBAH PELANGGAN KE PREVIEW ================= */
$('#btnTambahPelanggan').on('click', function () {

    let harga = $('#harga_khusus').val()
    let selected = $('#pelanggan').select2('data')[0]

    if (!harga || !selected) {
        Swal.fire({
            icon: 'warning',
            title: 'Lengkapi Data',
            text: 'Pilih pelanggan dan isi harga khusus'
        })
        return
    }

    Swal.fire({
        title: 'Tambah pelanggan?',
        text: 'Data akan dimasukkan ke daftar harga khusus',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, tambah',
        cancelButtonText: 'Batal'
    }).then((result) => {

        if (!pelanggan_ids.includes(selected.id)) {
            pelanggan_ids.push(selected.id)
        }

        if ($('#tablePreview tr td').length === 1) {
            $('#tablePreview').html('')
        }

        $('#tablePreview').append(renderRow({

            id: selected.id,
            nama_perusahaan: selected.text,
            nama_pemilik: selected.element.dataset.pemilik ?? '-',
            harga_khusus: harga
        }))

        $('#btnSimpan').prop('disabled', false)
        $('#modalPelanggan').modal('hide')

        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Pelanggan berhasil ditambahkan',
            timer: 1500,
            showConfirmButton: false
        })
    })
})


/* ================= RENDER ROW ================= */
function renderRow(data) {
    return `
        <tr id="row_${data.id}">
            <td>${data.nama_perusahaan}</td>
            <td>${data.nama_pemilik}</td>
            <td>Rp ${Number(data.harga_khusus).toLocaleString('id-ID')}</td>
            <td>
                <button class="btn btn-danger btn-sm"
                        onclick="hapus('${data.id}')">
                    Hapus
                </button>
            </td>
        </tr>
    `
}

//simpan
$('#btnSimpan').on('click', function () {

    if (pelanggan_ids.length === 0) return

    Swal.fire({
        title: 'Simpan data?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Simpan',
        cancelButtonText: 'Batal'
    }).then((result) => {

        if (!result.isConfirmed) return

        let requests = pelanggan_ids.map(id => {
            return $.ajax({
                url: "{{ route('rangepricepelanggan.storeSpecial') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    produk_id: $('#pilih_produk').val(),
                    harga_khusus: $('#harga_khusus').val(),
                    pelanggan_id: id
                }
            })
        })

        Promise.all(requests).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Tersimpan',
                timer: 1200,
                showConfirmButton: false
            })

            pelanggan_ids = []
            $('#pilih_produk').trigger('change')
        })
    })
})


/* ================= HAPUS PREVIEW ================= */
function hapus(id) {

    Swal.fire({
        title: 'Hapus pelanggan?',
        text: 'Data ini akan dihapus dari daftar',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {

        if (!result.isConfirmed) return

        pelanggan_id = null
        $('#btnSimpan').prop('disabled', true)
        $('#row_' + id).remove()

        if ($('#tablePreview tr').length === 0) {
            $('#btnSimpan').prop('disabled', true)
            $('#tablePreview').html(`<tr><td colspan="4">Belum ada data</td></tr>`)
        }

        Swal.fire({
            icon: 'success',
            title: 'Dihapus',
            text: 'Pelanggan berhasil dihapus',
            timer: 1200,
            showConfirmButton: false
        })
    })
}
</script>
@endpush
