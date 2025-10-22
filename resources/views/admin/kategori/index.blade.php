@extends('layouts.app')
@section('content')

<div class="container-fluid">
  <div class="card shadow">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <h4 class="mb-0">📂 Manajemen Kategori</h4>
      <button id="openAddModal" class="btn btn-light btn-sm">+ Tambah Kategori</button>
    </div>

    <div class="card-body">
      <table class="table table-bordered table-striped text-center align-middle" id="tabel_kategori">
        <thead class="table-primary">
          <tr>
            <th>Nama Kategori</th>
            <th>Keterangan</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody id="kategoriBody"></tbody>
      </table>
    </div>
  </div>
</div>

{{-- Modal Tambah --}}
<div id="addModal" class="modal fade" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="formAdd">@csrf
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Tambah Kategori</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input name="tambah_nama_kategori" class="form-control mb-3" placeholder="Nama Kategori">
          <textarea name="tambah_keterangan" class="form-control" placeholder="Keterangan"></textarea>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Modal Edit --}}
<div id="editModal" class="modal fade" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="formEdit">@csrf
        <input type="hidden" name="kategori_id" id="edit_kategori_id">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title">Edit Kategori</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input name="edit_nama_kategori" id="edit_nama_kategori" class="form-control mb-3" placeholder="Nama Kategori">
          <textarea name="edit_keterangan" id="edit_keterangan" class="form-control" placeholder="Keterangan"></textarea>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(function(){
    const addModal = new bootstrap.Modal(document.getElementById('addModal'));
    const editModal = new bootstrap.Modal(document.getElementById('editModal'));
    loadKategories();

    function loadKategories(){
        $.get("{{ route('loadkategori') }}", function(res){
            let rows = '';
            res.data.forEach(k => {
                rows += `
                <tr>
                    <td>${k.Nama_Kategori}</td>
                    <td>${k.Keterangan ?? '-'}</td>
                    <td>
                        <button class="btn btn-success btn-sm editBtn" data-id="${k.id}">Edit</button>
                        <button class="btn btn-danger btn-sm deleteBtn" data-id="${k.id}">Hapus</button>
                    </td>
                </tr>`;
            });
            $('#kategoriBody').html(rows);
        });
    }

    $('#openAddModal').click(()=>addModal.show());

    $('#formAdd').on('submit', function(e){
        e.preventDefault();
        $.ajax({
            url: "{{ route('storekategori') }}",
            method: "POST",
            data: $(this).serialize(),
            success: function(res){
                if(res === "Success"){
                    alert('Kategori berhasil ditambahkan!');
                    $('#formAdd')[0].reset();
                    addModal.hide();
                    loadKategories();
                } else { alert('Gagal menambahkan kategori!'); }
            },
            error: function(){ alert('Terjadi kesalahan pada server!'); }
        });
    });

    $(document).on('click', '.editBtn', function(){
        const id = $(this).data('id');
        $.get("{{ route('loadkategori') }}", function(res){
            const k = res.data.find(x => x.id == id);
            if(k){
                $('#edit_kategori_id').val(k.id);
                $('#edit_nama_kategori').val(k.Nama_Kategori);
                $('#edit_keterangan').val(k.Keterangan);
                editModal.show();
            }
        });
    });

    $('#formEdit').on('submit', function(e){
        e.preventDefault();
        $.ajax({
            url: "{{ route('updatekategori') }}",
            method: "POST",
            data: $(this).serialize(),
            success: function(res){
                if(res === "Success"){
                    alert('Kategori berhasil diupdate!');
                    editModal.hide();
                    loadKategories();
                } else { alert('Gagal update kategori!'); }
            },
            error: function(){ alert('Terjadi kesalahan pada server!'); }
        });
    });

    $(document).on('click', '.deleteBtn', function(){
        const id = $(this).data('id');
        if(confirm('Yakin ingin menghapus kategori ini?')){
            $.post("{{ route('deletekategori') }}", {_token:'{{ csrf_token() }}', hapus_kategori_id:id}, function(res){
                if(res === "Success"){ alert('Kategori dihapus!'); loadKategories(); }
                else { alert('Gagal menghapus kategori!'); }
            });
        }
    });
});
</script>
@endsection
