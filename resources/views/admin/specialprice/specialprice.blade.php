@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">💰 Harga Khusus Pelanggan</h4>
            <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                + Tambah
            </button>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped text-center align-middle " id="tabel_spcprice">
                <thead class="table-primary">
                    <tr>
                        <th>Pelanggan</th>
                        <th>Produk</th>
                        <th>Harga Khusus</th>
                        <th width="150">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

{{-- ================= MODAL TAMBAH ================= --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form_tambah_spcprice">
                @csrf

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Tambah Special Price</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <select class="form-control select2" name="pilih_pelanggan" style="width:100%">
                            <option disabled selected>Pilih Pelanggan</option>
                            @foreach ($pelanggans as $p)
                                <option value="{{ encrypt($p->id) }}">{{ $p->nama_perusahaan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <select class="form-control select2" name="pilih_produk" style="width:100%">
                            <option disabled selected>Pilih Produk</option>
                            @foreach ($produks as $pr)
                                <option value="{{ encrypt($pr->id) }}">{{ $pr->nama_produk }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Harga Khusus</label>
                        <input type="text" class="form-control" name="tambah_harga_khusus" id="tambah_harga_khusus">
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" id="bt_simpan_tambah">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ================= MODAL EDIT ================= --}}
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form_edit_spcprice">
                @csrf
                <input type="hidden" name="spcprice_id" id="spcprice_id">

                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title">Edit Special Price</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <select class="form-control select2" id="pilih_edit_pelanggan" name="pilih_edit_pelanggan">
                            @foreach ($pelanggans as $p)
                                <option value="{{ $p->id }}">{{ $p->nama_perusahaan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <select class="form-control select2" id="pilih_edit_produk" name="pilih_edit_produk">
                            @foreach ($produks as $pr)
                                <option value="{{ $pr->id }}">{{ $pr->nama_produk }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Harga Khusus</label>
                        <input type="text" class="form-control" id="edit_harga_khusus" name="edit_harga_khusus">
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" id="bt_simpan_edit">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script>
$(function () {

$('.select2').each(function () {
    let title = $(this).data('title');

    $(this).select2({
        placeholder: title,
        allowClear: true,
        dropdownCssClass: "select2-with-title",
        templateResult: function (data) {
            if (!data.id) return data.text;
            return data.text;
        }
    });

    // Tambahin judul pas dropdown dibuka
    $(this).on('select2:open', function () {
        if (!$('.select2-dropdown .select2-title').length) {
            $('.select2-dropdown').prepend(
                `<div class="select2-title">${title}</div>`
            );
        }
    });
});


    const table = $('#tabel_spcprice').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('loadspecialprice') }}",
        columns: [
            { data: 'nama_perusahaan' },
            { data: 'nama_produk' },
            { data: 'harga_khusus' },
            { data: 'action', orderable: false, searchable: false }
        ]
    });

    // ===================== TAMBAH =====================
    $('#bt_simpan_tambah').on('click', function () {
        $.ajax({
            url: "{{ route('storespecialprice') }}",
            method: "POST",
            data: new FormData($('#form_tambah_spcprice')[0]),
            processData: false,
            contentType: false,
            success: function () {
                $('#modalTambah').modal('hide');
                $('#form_tambah_spcprice')[0].reset();
                $('.select2').val(null).trigger('change');
                table.ajax.reload();
                Swal.fire('Success', 'Data tersimpan', 'success');
            },
            error: function () {
                Swal.fire('Error', 'Gagal menyimpan data', 'error');
            }
        });
    });

    // ===================== OPEN EDIT =====================
    $(document).on('click', '.modal_edit', function () {
        $('#spcprice_id').val($(this).data('id'));
        $('#pilih_edit_pelanggan').val($(this).data('id_pelanggan')).trigger('change');
        $('#pilih_edit_produk').val($(this).data('id_produk')).trigger('change');
        $('#edit_harga_khusus').val($(this).data('harga_khusus'));
        $('#modalEdit').modal('show');
    });

    // ===================== UPDATE =====================
    $('#bt_simpan_edit').on('click', function () {
        $.ajax({
            url: "{{ route('updatespecialprice') }}",
            method: "POST",
            data: new FormData($('#form_edit_spcprice')[0]),
            processData: false,
            contentType: false,
            success: function () {
                $('#modalEdit').modal('hide');
                table.ajax.reload();
                Swal.fire('Success', 'Data diupdate', 'success');
            },
            error: function () {
                Swal.fire('Error', 'Gagal update data', 'error');
            }
        });
    });

    // ===================== DELETE SWEETALERT =====================
    $(document).on('click', '.modal_hapus', function () {

        let id = $(this).data('id');
        let pelanggan = $(this).data('nama_perusahaan');
        let produk = $(this).data('nama_produk');

        Swal.fire({
            title: 'Yakin hapus?',
            html: `
                <b>${pelanggan}</b><br>
                Produk: <b>${produk}</b>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#d33'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deletespecialprice') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        hapus_spcprice_id: id
                    },
                    success: function () {
                        table.ajax.reload();
                        Swal.fire('Deleted!', 'Data berhasil dihapus', 'success');
                    },
                    error: function (xhr) {
                        console.log(xhr.responseText);
                        Swal.fire('Error', 'Gagal menghapus data', 'error');
                    }
                });
            }
        });
    });

});
</script>
@endpush
