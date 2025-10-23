@extends('layouts.app')
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
                            <select name="tambah_jenis_pelanggan" class="form-select">
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
                            <select name="tambah_statuspelanggan" class="form-select">
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
                    {{-- Mirip dengan modal tambah, tapi menggunakan id edit_* --}}
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
                            <select name="edit_statuspelanggan" id="edit_statuspelanggan" class="form-select">
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
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">

<script>
    $(function() {
        const addModal = new bootstrap.Modal(document.getElementById('addModal'));
        const editModal = new bootstrap.Modal(document.getElementById('editModal'));

        // Inisialisasi DataTable
        const table = $('#tabel_pelanggan').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('pelanggan.data') }}",
            columns: [{
                    data: 'nama_perusahaan',
                    name: 'nama_perusahaan'
                },
                {
                    data: 'nama_pemilik',
                    name: 'nama_pemilik'
                },
                {
                    data: 'jenis_pelanggan',
                    name: 'jenis_pelanggan'
                },
                {
                    data: 'telpon_pelanggan',
                    name: 'telpon_pelanggan'
                },
                {
                    data: 'email_pelanggan',
                    name: 'email_pelanggan'
                },
                {
                    data: 'limit_pelanggan',
                    name: 'limit_pelanggan'
                },
                {
                    data: 'status_pelanggan',
                    name: 'status_pelanggan',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        // Buka modal tambah
        $('#openAddModal').click(() => addModal.show());

        // Tambah data pelanggan
        $('#formAdd').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('pelanggan.store') }}",
                type: "POST",
                data: $(this).serialize(),
                success: res => {
                    if (res === "Success") {
                        addModal.hide();
                        table.ajax.reload();
                        alert('✅ Pelanggan berhasil ditambahkan!');
                        this.reset();
                    } else {
                        alert('❌ Gagal menambah pelanggan!');
                    }
                },
                error: err => {
                    console.error(err);
                    alert('❌ Terjadi kesalahan server.');
                }
            });
        });

        // Klik tombol edit
        $(document).on('click', '.editBtn', function() {
            const id = $(this).data('id');

            // Ambil detail pelanggan dari route baru
            $.get(`/pelanggan/${id}/detail`, function(p) {
                $('#edit_pelanggan_id').val(id);
                $('#edit_namaperusahaan').val(p.nama_perusahaan);
                $('#edit_namapemilik').val(p.nama_pemilik);
                $('#edit_teleponpelanggan').val(p.telpon_pelanggan);
                $('#edit_emailpelanggan').val(p.email_pelanggan);
                $('#edit_limittagihan').val(p.limit_pelanggan);
                $('#edit_statuspelanggan').val(p.status_pelanggan ? 1 : 0);
                $('#edit_keterangan').val(p.keterangan_pelanggan);
                editModal.show();
            }).fail(() => alert('❌ Gagal mengambil data pelanggan!'));
        });

        // Update data pelanggan
        $('#formEdit').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('pelanggan.update') }}",
                type: "POST",
                data: $(this).serialize(),
                success: res => {
                    if (res === "Success") {
                        editModal.hide();
                        table.ajax.reload();
                        alert('✅ Data pelanggan diperbarui!');
                    } else {
                        alert('❌ Gagal update pelanggan!');
                    }
                },
                error: err => {
                    console.error(err);
                    alert('❌ Terjadi kesalahan server.');
                }
            });
        });

        // Hapus data pelanggan
        $(document).on('click', '.deleteBtn', function() {
            const id = $(this).data('id');
            if (confirm('Yakin ingin menghapus pelanggan ini?')) {
                $.post("{{ route('pelanggan.destroy') }}", {
                    _token: "{{ csrf_token() }}",
                    hapus_pelanggan_id: id
                }, function(res) {
                    if (res === "Success") {
                        alert('✅ Pelanggan berhasil dihapus!');
                        table.ajax.reload();
                    } else {
                        alert('❌ Gagal menghapus pelanggan!');
                    }
                }).fail(() => alert('❌ Terjadi kesalahan server.'));
            }
        });
    });
</script>
@endpush
@endsection
