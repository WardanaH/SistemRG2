@extends('layouts.app')
@section('content')

<div class="container-fluid">
  <div class="card shadow">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <h4 class="mb-0">📦 Manajemen Supplier</h4>
      <button id="openAddModal" class="btn btn-light btn-sm">+ Tambah Supplier</button>
    </div>

    <div class="card-body">
      <table class="table table-bordered table-striped text-center align-middle" id="tabel_supplier">
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

{{-- =================== SCRIPT =================== --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(function(){
    const addModal = new bootstrap.Modal(document.getElementById('addModal'));
    const editModal = new bootstrap.Modal(document.getElementById('editModal'));

    loadSuppliers();

    // ======= LOAD DATA SUPPLIER =======
    function loadSuppliers(){
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
                        <button class="btn btn-success btn-sm editBtn" data-id="${s.id}">Edit</button>
                        <button class="btn btn-danger btn-sm deleteBtn" data-id="${s.id}">Hapus</button>
                      </td>
                    </tr>`;
                });
            }
            $('#supplierBody').html(rows);
        });
    }

    // ======= TAMBAH SUPPLIER =======
    $('#openAddModal').click(()=>addModal.show());
    $('#formAdd').on('submit', function(e){
        e.preventDefault();
        $.ajax({
            url: "{{ route('storesupplier') }}",
            method: "POST",
            data: $(this).serialize(),
            success: function(res){
                if(res === "Success"){
                    alert('Supplier berhasil ditambahkan!');
                    $('#formAdd')[0].reset();
                    addModal.hide();
                    loadSuppliers();
                } else {
                    alert('Gagal menambahkan supplier!');
                }
            },
            error: function(err){
                console.error(err);
                alert('Terjadi kesalahan pada server!');
            }
        });
    });

    // ======= EDIT SUPPLIER =======
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

    $('#formEdit').on('submit', function(e){
        e.preventDefault();
        $.ajax({
            url: "{{ route('updatesupplier') }}",
            method: "POST",
            data: $(this).serialize(),
            success: function(res){
                if(res === "Success"){
                    alert('Data supplier berhasil diupdate!');
                    $('#formEdit')[0].reset();
                    editModal.hide();
                    loadSuppliers();
                } else {
                    alert('Gagal update supplier!');
                }
            },
            error: function(err){
                console.error(err);
                alert('Terjadi kesalahan pada server!');
            }
        });
    });

    // ======= HAPUS SUPPLIER =======
    $(document).on('click', '.deleteBtn', function(){
        const id = $(this).data('id');
        if(confirm('Yakin ingin menghapus supplier ini?')){
            $.post("{{ route('deletesupplier') }}", {
                _token: '{{ csrf_token() }}',
                hapus_supplier_id: id
            }, function(res){
                if(res === "Success"){
                    alert('Supplier berhasil dihapus!');
                    loadSuppliers();
                } else {
                    alert('Gagal menghapus supplier!');
                }
            });
        }
    });
});
</script>

@endsection
