@extends('layouts.app')
@section('content')

<div class="container-fluid">
  <div class="card shadow">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <h4 class="mb-0">🛒 Manajemen Produk</h4>
      <button id="openAddModal" class="btn btn-light btn-sm">+ Tambah Produk</button>
    </div>

    <div class="card-body">
      <table class="table table-bordered table-striped text-center align-middle" id="tabel_produk">
        <thead class="table-primary">
          <tr>
            <th>Nama Produk</th>
            <th>Satuan</th>
            <th>Harga Beli</th>
            <th>Harga Jual</th>
            <th>Kategori</th>
            <th>Keterangan</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody id="produkBody"></tbody>
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
          <h5 class="modal-title">Tambah Produk</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row g-2">
            <div class="col-md-6">
              <select name="tambah_kategori" class="form-select">
                <option value="">-- Pilih Kategori --</option>
                @foreach($kategories as $k)
                  <option value="{{ $k->id }}">{{ $k->Nama_Kategori }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6"><input name="tambah_nama_produk" class="form-control" placeholder="Nama Produk"></div>
            <div class="col-md-6">
              <select name="tambah_satuan" class="form-select">
                <option value="">-- Satuan --</option>
                <option value="CENTIMETER">Centimeter</option>
                <option value="METER">Meter</option>
                <option value="PCS">Pcs</option>
                <option value="PAKET">Paket</option>
              </select>
            </div>
            <div class="col-md-6"><input name="tambah_harga_beli" type="number" class="form-control" placeholder="Harga Beli"></div>
            <div class="col-md-6"><input name="tambah_harga_jual" type="number" class="form-control" placeholder="Harga Jual"></div>
            <div class="col-md-12"><textarea name="tambah_keterangan" class="form-control" placeholder="Keterangan"></textarea></div>
          </div>
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
        <input type="hidden" name="produk_id" id="edit_produk_id">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title">Edit Produk</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row g-2">
            <div class="col-md-6">
              <select name="edit_kategori" id="edit_kategori" class="form-select">
                @foreach($kategories as $k)
                  <option value="{{ $k->id }}">{{ $k->Nama_Kategori }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6"><input name="edit_nama_produk" id="edit_nama_produk" class="form-control" placeholder="Nama Produk"></div>
            <div class="col-md-6">
              <select name="edit_satuan" id="edit_satuan" class="form-select">
                <option value="CENTIMETER">Centimeter</option>
                <option value="METER">Meter</option>
                <option value="PCS">Pcs</option>
                <option value="PAKET">Paket</option>
              </select>
            </div>
            <div class="col-md-6"><input name="edit_harga_beli" id="edit_harga_beli" type="number" class="form-control" placeholder="Harga Beli"></div>
            <div class="col-md-6"><input name="edit_harga_jual" id="edit_harga_jual" type="number" class="form-control" placeholder="Harga Jual"></div>
            <div class="col-md-12"><textarea name="edit_keterangan" id="edit_keterangan" class="form-control" placeholder="Keterangan"></textarea></div>
          </div>
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
$(document).ready(function() {
    const addModal = new bootstrap.Modal(document.getElementById('addModal'));
    const editModal = new bootstrap.Modal(document.getElementById('editModal'));

    // === Buka Modal Tambah ===
    $('#openAddModal').on('click', function() {
        addModal.show();
    });

    // === Load Data Produk ===
    loadProduk();

    function loadProduk() {
        $.get("{{ route('loadproduk') }}", function(res) {
            let rows = '';
            if (res.data.length === 0) {
                rows = `<tr><td colspan="7" class="text-muted">Belum ada data produk.</td></tr>`;
            } else {
                res.data.forEach(p => {
                    rows += `
                    <tr>
                      <td>${p.nama_produk}</td>
                      <td>${p.satuan}</td>
                      <td>Rp ${parseInt(p.harga_beli).toLocaleString('id-ID')}</td>
                      <td>Rp ${parseInt(p.harga_jual).toLocaleString('id-ID')}</td>
                      <td>${p.kategori?.Nama_Kategori ?? '-'}</td>
                      <td>${p.keterangan ?? '-'}</td>
                      <td>
                        <button class="btn btn-success btn-sm editBtn" data-id="${p.id}">Edit</button>
                        <button class="btn btn-danger btn-sm deleteBtn" data-id="${p.id}">Hapus</button>
                      </td>
                    </tr>`;
                });
            }
            $('#produkBody').html(rows);
        });
    }

    // === Tambah Produk ===
    $('#formAdd').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('storeproduk') }}",
            method: "POST",
            data: $(this).serialize(),
            success: function(res) {
                if (res === "Success") {
                    alert('Produk berhasil ditambahkan!');
                    $('#formAdd')[0].reset();
                    addModal.hide();
                    loadProduk();
                } else {
                    alert('Gagal menambahkan produk!');
                }
            },
            error: function(err) {
                console.error(err);
                alert('Terjadi kesalahan server!');
            }
        });
    });

    // === Edit Produk ===
    $(document).on('click', '.editBtn', function() {
        const id = $(this).data('id');
        $.get("{{ route('loadproduk') }}", function(res) {
            const p = res.data.find(x => x.id == id);
            if (p) {
                $('#edit_produk_id').val(p.id);
                $('#edit_kategori').val(p.kategori_id);
                $('#edit_nama_produk').val(p.nama_produk);
                $('#edit_satuan').val(p.satuan);
                $('#edit_harga_beli').val(p.harga_beli);
                $('#edit_harga_jual').val(p.harga_jual);
                $('#edit_keterangan').val(p.keterangan);
                editModal.show();
            } else {
                alert('Produk tidak ditemukan.');
            }
        });
    });

    // === Simpan Edit ===
    $('#formEdit').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('updateproduk') }}",
            method: "POST",
            data: $(this).serialize(),
            success: function(res) {
                if (res === "Success") {
                    alert('Produk berhasil diperbarui!');
                    $('#formEdit')[0].reset();
                    editModal.hide();
                    loadProduk();
                } else {
                    alert('Gagal memperbarui produk!');
                }
            },
            error: function(err) {
                console.error(err);
                alert('Terjadi kesalahan server!');
            }
        });
    });

    // === Hapus Produk ===
    $(document).on('click', '.deleteBtn', function() {
        const id = $(this).data('id');
        if (confirm('Yakin ingin menghapus produk ini?')) {
            $.post("{{ route('deleteproduk') }}", {
                _token: '{{ csrf_token() }}',
                hapus_produk_id: id
            }, function(res) {
                if (res === "Success") {
                    alert('Produk berhasil dihapus!');
                    loadProduk();
                } else {
                    alert('Gagal menghapus produk!');
                }
            });
        }
    });
});
</script>

@endsection
