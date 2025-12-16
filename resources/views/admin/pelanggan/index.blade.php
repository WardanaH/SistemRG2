@extends('layouts.app')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
    /* FIX TEKS MODAL TRANSPARAN */
    .modal-content,
    .modal-body,
    .modal-header,
    .modal-footer,
    .modal-title,
    label,
    input,
    textarea,
    select {
        color: #000 !important;
        opacity: 1 !important;
    }

    /* Select2 agar tidak transparan & tidak menghilang */
    .select2-container .select2-selection--single {
        height: 38px !important;
        padding: 6px 10px !important;
        border: 1px solid #ced4da !important;
        border-radius: 5px !important;
    }

    .select2-selection__rendered {
        color: #000 !important;
    }

    .select2-dropdown {
        z-index: 999999 !important;
    }

    .select2-results__option {
    color: #000 !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">👥 Manajemen Pelanggan</h4>
            <button id="openAddModal" class="btn btn-light btn-sm">+ Tambah Pelanggan</button>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped align-middle text-center w-100" id="tabel_pelanggan">
                <thead class="table-primary">
                    <tr>
                        <th>Nama Perusahaan</th>
                        <th>Nama Pemilik</th>
                        <th>Jenis Pelanggan</th>
                        <th>Telepon</th>
                        <th>Email</th>
                        <th>Limit</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

{{-- =================== MODAL TAMBAH =================== --}}
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formAdd">@csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Tambah Pelanggan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label>Jenis Pelanggan</label>
                            <select class="select2" name="tambah_jenis_pelanggan">
                                @foreach($jenispelanggans as $j)
                                <option value="{{ encrypt($j->id) }}">{{ $j->jenis_pelanggan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Nama Pemilik</label>
                            <input name="tambah_namapemilik" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>No. KTP</label>
                            <input name="tambah_ktppelanggan" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>HP</label>
                            <input name="tambah_hppelanggan" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>Nama Perusahaan</label>
                            <input name="tambah_namaperusahaan" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>Telepon</label>
                            <input name="tambah_teleponpelanggan" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>Email</label>
                            <input name="tambah_emailpelanggan" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>Tempo (hari)</label>
                            <input name="tambah_tempotagihan" class="form-control" type="number">
                        </div>
                        <div class="col-md-6">
                            <label>Limit Tagihan</label>
                            <input name="tambah_limittagihan" class="form-control" type="number">
                        </div>
                        <div class="col-md-6">
                            <label>No. Rekening</label>
                            <input name="tambah_rekpelanggan" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <label>Alamat</label>
                            <textarea name="tambah_alamatpelanggan" class="form-control"></textarea>
                        </div>
                        <div class="col-md-12">
                            <label>Keterangan</label>
                            <textarea name="tambah_keterangan" class="form-control"></textarea>
                        </div>
                        <div class="col-md-6 mt-2">
                            <label>Status</label>
                            <select name="tambah_statuspelanggan" class="select2">
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- =================== MODAL EDIT =================== --}}
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formEdit">@csrf
                <input type="hidden" name="pelanggan_id" id="edit_pelanggan_id">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Edit Pelanggan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label>Nama Perusahaan</label>
                            <input name="edit_namaperusahaan" id="edit_namaperusahaan" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>Nama Pemilik</label>
                            <input name="edit_namapemilik" id="edit_namapemilik" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>Telepon</label>
                            <input name="edit_teleponpelanggan" id="edit_teleponpelanggan" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>Email</label>
                            <input name="edit_emailpelanggan" id="edit_emailpelanggan" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>Limit</label>
                            <input name="edit_limittagihan" id="edit_limittagihan" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>Status</label>
                            <select name="edit_statuspelanggan" id="edit_statuspelanggan" class="select2">
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label>Keterangan</label>
                            <textarea name="edit_keterangan" id="edit_keterangan" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- =================== SCRIPT =================== --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(function() {

    const addModal = new bootstrap.Modal('#addModal');
    const editModal = new bootstrap.Modal('#editModal');

    // Datatable
    const table = $('#tabel_pelanggan').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        ajax: "{{ route('pelanggan.data') }}",
        columns: [
            { data: 'nama_perusahaan' },
            { data: 'nama_pemilik' },
            { data: 'jenis_pelanggan' },
            { data: 'telpon_pelanggan' },
            { data: 'email_pelanggan' },
            { data: 'limit_pelanggan' },
            { data: 'status_pelanggan', orderable:false, searchable:false },
            { data: 'action', orderable:false, searchable:false },
        ],
            columnDefs: [
        {
            targets: 7, // kolom 'action'
            render: function(data, type, row){
                return `
                    <button class="btn btn-warning btn-sm editBtn" data-id="${row.id}">Edit</button>
                    <button class="btn btn-danger btn-sm deleteBtn" data-id="${row.id}">Hapus</button>
                `;
            }
        }
    ]
    });

    // Buka modal tambah
    $('#openAddModal').click(() => addModal.show());

    // Submit Tambah
    $('#formAdd').submit(function(e){
        e.preventDefault();
        $.post("{{ route('pelanggan.store') }}", $(this).serialize(), res=>{
            if(res==="Success"){
                addModal.hide();
                table.ajax.reload();
                Swal.fire({
                    icon:'success',
                    title:'Berhasil!',
                    text:'Pelanggan berhasil ditambahkan.',
                    confirmButtonColor:'#0d6efd'
                });
                this.reset();
            } else {
                Swal.fire({
                    icon:'error',
                    title:'Gagal!',
                    text:'Tidak dapat menambah pelanggan.',
                    confirmButtonColor:'#d33'
                });
            }
        }).fail(()=>{
            Swal.fire({
                icon:'error',
                title:'Kesalahan Server!',
                text:'Terjadi kesalahan pada server.',
                confirmButtonColor:'#d33'
            });
        });
    });

    // Klik Edit
    $(document).on('click','.editBtn',function(){
        const id=$(this).data('id');
        $.get(`/pelanggan/${id}/detail`,p=>{
            $('#edit_pelanggan_id').val(id);
            $('#edit_namaperusahaan').val(p.nama_perusahaan);
            $('#edit_namapemilik').val(p.nama_pemilik);
            $('#edit_teleponpelanggan').val(p.telpon_pelanggan);
            $('#edit_emailpelanggan').val(p.email_pelanggan);
            $('#edit_limittagihan').val(p.limit_pelanggan);
            $('#edit_statuspelanggan').val(p.status_pelanggan?1:0);
            $('#edit_keterangan').val(p.keterangan_pelanggan);
            editModal.show();
        }).fail(()=>{
            Swal.fire({
                icon:'error',
                title:'Gagal!',
                text:'Tidak dapat mengambil data pelanggan.',
                confirmButtonColor:'#d33'
            });
        });
    });

    // Submit Edit
    $('#formEdit').submit(function(e){
        e.preventDefault();
        $.post("{{ route('pelanggan.update') }}", $(this).serialize(), res=>{
            if(res==="Success"){
                editModal.hide();
                table.ajax.reload();
                Swal.fire({
                    icon:'success',
                    title:'Berhasil!',
                    text:'Data pelanggan berhasil diperbarui.',
                    confirmButtonColor:'#198754'
                });
            } else {
                Swal.fire({
                    icon:'error',
                    title:'Gagal!',
                    text:'Update pelanggan gagal.',
                    confirmButtonColor:'#d33'
                });
            }
        }).fail(xhr=>{
            Swal.fire({
                icon:'error',
                title:'Kesalahan!',
                text: 'Error '+xhr.status,
                confirmButtonColor:'#d33'
            });
        });
    });

    // Delete SweetAlert
    $(document).on('click','.deleteBtn',function(){
        const id = $(this).data('id');

        Swal.fire({
            title: "Yakin hapus pelanggan ini?",
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Ya, hapus!",
            cancelButtonText: "Batal"
        }).then((result)=>{
            if(result.isConfirmed){
                $.post("{{ route('pelanggan.destroy') }}", {
                    _token:"{{ csrf_token() }}",
                    hapus_pelanggan_id:id
                },res=>{
                    if(res==="Success"){
                        Swal.fire({
                            icon:'success',
                            title:'Terhapus!',
                            text:'Pelanggan berhasil dihapus.',
                            confirmButtonColor:'#0d6efd'
                        });
                        table.ajax.reload();
                    } else {
                        Swal.fire({
                            icon:'error',
                            title:'Gagal!',
                            text:'Tidak dapat menghapus pelanggan.',
                            confirmButtonColor:'#d33'
                        });
                    }
                }).fail(xhr=>{
                    Swal.fire({
                        icon:'error',
                        title:'Kesalahan!',
                        text:'Error '+xhr.status,
                        confirmButtonColor:'#d33'
                    });
                });
            }
        });

    });
    // INITIAL SELECT2 GLOBAL
    $('.select2').select2({
        dropdownParent: $('#addModal')
        
    });

    // KETIKA MODAL TAMBAH DIBUKA
    $('#addModal').on('shown.bs.modal', function () {
        $('.select2').select2({
            dropdownParent: $('#addModal')
        });
    });

    // KETIKA MODAL EDIT DIBUKA
    $('#editModal').on('shown.bs.modal', function () {
        $('#edit_statuspelanggan').select2({
            dropdownParent: $('#editModal')
        });
    });
});
</script>
@endpush
@endsection
