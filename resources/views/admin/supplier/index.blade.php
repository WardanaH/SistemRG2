@extends('layouts.app')
@section('content')

<div class="container-fluid">
  <div class="card shadow">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <h4 class="mb-0">📦 Manajemen Supplier</h4>
      <button id="openAddModal" class="btn btn-light btn-sm">+ Tambah Supplier</button>
    </div>

    <div class="card-body">
      <table class="table table-bordered table-striped text-center align-middle styletable" id="tabel_supplier">
        <thead class="table-primary">
          <tr>
            <th>Nama</th>
            <th>Pemilik</th>
            <th>Telepon</th>
            <th>Email</th>
            <th>Alamat</th>
            <th>Rekening</th>
            <th>Keterangan</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody id="supplierBody"></tbody>
      </table>
    </div>
  </div>
</div>

{{-- =================== MODAL TAMBAH =================== --}}
<div id="addModal" class="modal fade" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="formAdd">@csrf
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Tambah Supplier</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row g-2">
            <div class="col-md-6"><input name="tambah_nama_supplier" class="form-control" placeholder="Nama Supplier"></div>
            <div class="col-md-6"><input name="tambah_pemilik_supplier" class="form-control" placeholder="Pemilik"></div>
            <div class="col-md-6"><input name="tambah_telpon_supplier" class="form-control" placeholder="Telepon"></div>
            <div class="col-md-6"><input name="tambah_email_supplier" class="form-control" placeholder="Email"></div>
          </div>
          <textarea name="tambah_alamat_supplier" class="form-control mt-3" placeholder="Alamat"></textarea>
          <input name="tambah_rekening_suppliers" class="form-control mt-3" placeholder="No Rekening">
          <textarea name="tambah_keterangan_suppliers" class="form-control mt-3" placeholder="Keterangan"></textarea>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- =================== MODAL EDIT =================== --}}
<div id="editModal" class="modal fade" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="formEdit">@csrf
        <input type="hidden" name="supplier_id" id="edit_supplier_id">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title">Edit Supplier</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row g-2">
            <div class="col-md-6"><input name="edit_nama_supplier" id="edit_nama_supplier" class="form-control" placeholder="Nama Supplier"></div>
            <div class="col-md-6"><input name="edit_pemilik_supplier" id="edit_pemilik_supplier" class="form-control" placeholder="Pemilik"></div>
            <div class="col-md-6"><input name="edit_telpon_supplier" id="edit_telpon_supplier" class="form-control" placeholder="Telepon"></div>
            <div class="col-md-6"><input name="edit_email_supplier" id="edit_email_supplier" class="form-control" placeholder="Email"></div>
          </div>
          <textarea name="edit_alamat_supplier" id="edit_alamat_supplier" class="form-control mt-3" placeholder="Alamat"></textarea>
          <input name="edit_rekening_suppliers" id="edit_rekening_suppliers" class="form-control mt-3" placeholder="No Rekening">
          <textarea name="edit_keterangan_suppliers" id="edit_keterangan_suppliers" class="form-control mt-3" placeholder="Keterangan"></textarea>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- ================= SWEETALERT + SCRIPT ================== --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(function(){

    const addModal = new bootstrap.Modal(document.getElementById('addModal'));
    const editModal = new bootstrap.Modal(document.getElementById('editModal'));

    loadSuppliers();

    // ====================== LOAD SUPPLIER ======================
    function loadSuppliers() {
        $.get("{{ route('loadsupplier') }}", function(res){
            let rows = '';

            if(res.data.length === 0){
                rows = `<tr><td colspan="8" class="text-muted">Belum ada data supplier.</td></tr>`;
            } else {
                res.data.forEach(s => {
                    rows += `
                    <tr>
                      <td>${s.nama_supplier}</td>
                      <td>${s.pemilik_supplier}</td>
                      <td>${s.telpon_supplier}</td>
                      <td>${s.email_supplier ?? '-'}</td>
                      <td>${s.alamat_supplier}</td>
                      <td>${s.rekening_suppliers}</td>
                      <td>${s.keterangan_suppliers ?? '-'}</td>
                      <td>
                        <button class="btn btn-warning btn-sm editBtn" data-id="${s.id}">Edit</button>
                        <button class="btn btn-danger btn-sm deleteBtn" data-id="${s.id}">Hapus</button>
                      </td>
                    </tr>`;
                });
            }

            $('#supplierBody').html(rows);

        }).fail(() => {
            Swal.fire({ icon: 'error', title: 'Gagal memuat data!' })
        });
    }

    // ====================== TAMBAH SUPPLIER ======================
    $('#openAddModal').click(() => addModal.show());

    $('#formAdd').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            url: "{{ route('storesupplier') }}",
            method: "POST",
            data: $(this).serialize(),

            success: function(res){
                if(res === "Success"){
                    Swal.fire({
                        icon: 'success',
                        title: 'Supplier berhasil ditambahkan!',
                        timer: 1500,
                        showConfirmButton: false
                    });

                    $('#formAdd')[0].reset();
                    addModal.hide();
                    loadSuppliers();

                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal menambahkan supplier!' });
                }
            },

            error: function(){
                Swal.fire({ icon: 'error', title: 'Terjadi kesalahan pada server!' });
            }
        });
    });

    // ====================== EDIT OPEN FORM ======================
    $(document).on('click', '.editBtn', function(){
        const id = $(this).data('id');

        $.get("{{ route('loadsupplier') }}", function(res){
            const s = res.data.find(x => x.id == id);

            if(s){
                $('#edit_supplier_id').val(s.id);
                $('#edit_nama_supplier').val(s.nama_supplier);
                $('#edit_pemilik_supplier').val(s.pemilik_supplier);
                $('#edit_telpon_supplier').val(s.telpon_supplier);
                $('#edit_email_supplier').val(s.email_supplier);
                $('#edit_alamat_supplier').val(s.alamat_supplier);
                $('#edit_rekening_suppliers').val(s.rekening_suppliers);
                $('#edit_keterangan_suppliers').val(s.keterangan_suppliers);
                editModal.show();
            }
        });
    });

    // ====================== UPDATE ======================
    $('#formEdit').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            url: "{{ route('updatesupplier') }}",
            method: "POST",
            data: $(this).serialize(),

            success: function(res){
                if(res === "Success"){
                    Swal.fire({
                        icon: 'success',
                        title: 'Data supplier berhasil diupdate!',
                        timer: 1500,
                        showConfirmButton: false
                    });

                    $('#formEdit')[0].reset();
                    editModal.hide();
                    loadSuppliers();

                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal update supplier!' });
                }
            },

            error: function(){
                Swal.fire({ icon: 'error', title: 'Terjadi kesalahan pada server!' });
            }
        });

    });

    // ====================== HAPUS ======================
    $(document).on('click', '.deleteBtn', function(){

        const id = $(this).data('id');

        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: 'Data supplier akan dihapus permanen.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then(result => {

            if(result.isConfirmed){

                $.post("{{ route('deletesupplier') }}", {
                    _token: '{{ csrf_token() }}',
                    hapus_supplier_id: id
                }, function(res){

                    if(res === "Success"){
                        Swal.fire({
                            icon: 'success',
                            title: 'Supplier berhasil dihapus!',
                            timer: 1500,
                            showConfirmButton: false
                        });

                        loadSuppliers();

                    } else {
                        Swal.fire({ icon: 'error', title: 'Gagal menghapus supplier!' });
                    }

                }).fail(() => {
                    Swal.fire({ icon: 'error', title: 'Terjadi kesalahan server!' });
                });

            }

        });

    });

});
</script>

@endsection
