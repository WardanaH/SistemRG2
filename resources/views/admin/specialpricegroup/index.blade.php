@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="card shadow">

        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">💰 Harga Khusus Grup Pelanggan</h4>
            <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                + Tambah
            </button>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped text-center align-middle" id="tabel_spg">
                <thead class="table-primary">
                    <tr>
                        <th>Jenis Pelanggan</th>
                        <th>Produk</th>
                        <th>Harga Khusus</th>
                        <th>User</th>
                        <th>Tanggal</th>
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

            <form id="form_tambah_spg">
                @csrf

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Tambah Harga Khusus Grup</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label>Jenis Pelanggan</label>
                        <select class="form-control select2" name="jenispelanggan_id">
                            <option disabled selected>Pilih Jenis Pelanggan</option>
                            @foreach ($jenispelanggans as $j)
                                <option value="{{ encrypt($j->id) }}">{{ $j->jenis_pelanggan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Produk</label>
                        <select class="form-control select2" name="produk_id">
                            <option disabled selected>Pilih Produk</option>
                            @foreach ($produks as $p)
                                <option value="{{ $p->id }}">{{ $p->nama_produk }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Harga Khusus</label>
                        <input type="text" class="form-control" name="harga_khusus">
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

            <form id="form_edit_spg">
                @csrf
                <input type="hidden" name="id_spg" id="id_spg">

                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title">Edit Harga Khusus Grup</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                <div class="mb-3">
                    <label>Jenis Pelanggan</label>
                    <select class="form-control select2" name="jenispelanggan_id" id="edit_jenispelanggan">
                        @foreach ($jenispelanggans as $j)
                            <option value="{{ $j->id }}">{{ $j->jenis_pelanggan }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Produk</label>
                    <select class="form-control select2" name="produk_id" id="edit_produk">
                        @foreach ($produks as $p)
                            <option value="{{ $p->id }}">{{ $p->nama_produk }}</option>
                        @endforeach
                    </select>
                </div>

                    <div class="mb-3">
                        <label>Harga Khusus</label>
                        <input
                            type="text"
                            class="form-control"
                            name="harga_khusus"
                            id="edit_harga_khusus"
                            placeholder="Masukkan harga khusus">
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

    $('.select2').select2();

    const table = $('#tabel_spg').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('specialpricegroup.load') }}",
        columns: [
            { data: 'jenis_pelanggan' },
            { data: 'nama_produk' },
            { data: 'harga_khusus' },
            { data: 'username' },
            { data: 'tanggal' },
            { data: 'action', orderable: false, searchable: false }
        ]
    });

    // ================= TAMBAH =================
    $('#bt_simpan_tambah').click(function () {
        $.ajax({
            url: "{{ route('specialpricegroup.store') }}",
            method: "POST",
            data: new FormData($('#form_tambah_spg')[0]),
            processData: false,
            contentType: false,
            success: function (res) {
                if (res === 'Duplicated') {
                    Swal.fire('Error', 'Data sudah ada', 'error');
                } else {
                    $('#modalTambah').modal('hide');
                    $('#form_tambah_spg')[0].reset();
                    $('.select2').val(null).trigger('change');
                    table.ajax.reload();
                    Swal.fire('Success', 'Data tersimpan', 'success');
                }
            },
            error: function () {
                Swal.fire('Error', 'Gagal menyimpan data', 'error');
            }
        });
    });

    // ================= OPEN EDIT =================
    $(document).on('click', '.modal_edit', function () {
        $('#id_spg').val($(this).data('id'));

        $('#edit_jenispelanggan')
            .val($(this).data('jenispelanggan_id'))
            .trigger('change');

        $('#edit_produk')
            .val($(this).data('produk_id'))
            .trigger('change');

        $('#edit_harga_khusus').val($(this).data('harga'));

        $('#modalEdit').modal('show');
    });


    // ================= UPDATE =================
    $('#bt_simpan_edit').click(function () {
        $.ajax({
            url: "{{ route('specialpricegroup.update') }}",
            method: "POST",
            data: new FormData($('#form_edit_spg')[0]),
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

    // ================= DELETE (SWEETALERT) =================
    $(document).on('click', '.btn_hapus', function () {

        const id     = $(this).data('id');
        const jenis  = $(this).data('jenis');
        const produk = $(this).data('produk');
        const harga  = $(this).data('harga');

        Swal.fire({
            title: 'Yakin hapus data?',
            html: `
                <b>${jenis}</b> - <b>${produk}</b><br>
                Harga: <b>Rp ${Number(harga).toLocaleString('id-ID')}</b>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {

                $.ajax({
                    url: "{{ route('specialpricegroup.delete') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        hapus_spg_id: id
                    },
                    success: function () {
                        table.ajax.reload();
                        Swal.fire('Berhasil', 'Data berhasil dihapus', 'success');
                    },
                    error: function () {
                        Swal.fire('Error', 'Gagal menghapus data', 'error');
                    }
                });

            }
        });
    });
});
</script>
@endpush
