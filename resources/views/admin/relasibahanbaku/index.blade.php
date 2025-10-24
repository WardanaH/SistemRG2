@extends('layouts.app')
@section('content')

<div class="container-fluid">
  <div class="card shadow">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <h4 class="mb-0">🧩 Relasi Produk & Bahan Baku</h4>
      <button id="openAddModal" class="btn btn-light btn-sm">+ Tambah Relasi</button>
    </div>

    <div class="card-body">
      <table class="table table-bordered table-striped text-center align-middle" id="tabel_relasi">
        <thead class="table-primary">
          <tr>
            <th>Nama Produk</th>
            <th>Bahan Baku</th>
            <th>Qty per Transaksi</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody id="relasiBody">
          <tr><td colspan="4" class="text-muted">Memuat data...</td></tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

{{-- =================== MODAL TAMBAH =================== --}}
<div id="addModal" class="modal fade" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="formAdd">@csrf
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Tambah Relasi Produk - Bahan Baku</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Produk</label>
            <select name="tambah_r_produk" class="form-select" required>
              <option value="">-- Pilih Produk --</option>
              @foreach($produks as $p)
                <option value="{{ encrypt($p->id) }}">{{ $p->nama_produk }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Bahan Baku</label>
            <select name="tambah_r_bahan_baku" class="form-select" required>
              <option value="">-- Pilih Bahan Baku --</option>
              @foreach($bahanbakus as $b)
                <option value="{{ encrypt($b->id) }}">{{ $b->nama_bahan }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Qty per Transaksi</label>
            <input name="tambah_qty_p_trans" type="number" min="0" class="form-control" placeholder="Masukkan jumlah" required>
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
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="formEdit">@csrf
        <input type="hidden" name="relasi_id" id="edit_relasi_id">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title">Edit Relasi Produk - Bahan Baku</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Produk</label>
            <select name="edit_r_produk" id="edit_r_produk" class="form-select" required>
              @foreach($produks as $p)
                <option value="{{ $p->id }}">{{ $p->nama_produk }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Bahan Baku</label>
            <select name="edit_r_bahan_baku" id="edit_r_bahan_baku" class="form-select" required>
              @foreach($bahanbakus as $b)
                <option value="{{ $b->id }}">{{ $b->nama_bahan }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Qty per Transaksi</label>
            <input name="edit_qty_p_trans" id="edit_qty_p_trans" type="number" min="0" class="form-control" required>
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
<script>
$(function() {
  const addModal = new bootstrap.Modal(document.getElementById('addModal'));
  const editModal = new bootstrap.Modal(document.getElementById('editModal'));

  // === Buka Modal Tambah ===
  $('#openAddModal').on('click', () => addModal.show());

  // === Load Data Relasi ===
  loadRelasi();
  function loadRelasi() {
    $.get("{{ route('loadrelasibahanbaku') }}", function(res) {
      let rows = '';
      if (!res.data || res.data.length === 0) {
        rows = `<tr><td colspan="4" class="text-muted">Belum ada relasi produk dan bahan baku.</td></tr>`;
      } else {
        res.data.forEach(r => {
          rows += `
            <tr>
              <td>${r.produk?.nama_produk ?? '-'}</td>
              <td>${r.bahanbaku?.nama_bahan ?? '-'}</td>
              <td>${r.qtypertrx}</td>
              <td>
                <button class="btn btn-success btn-sm editBtn"
                        data-id="${r.encrypted_id}"
                        data-produk="${r.produk_id}"
                        data-bahanbaku="${r.bahanbaku_id}"
                        data-qty="${r.qtypertrx}">Edit</button>
                <button class="btn btn-danger btn-sm deleteBtn" data-id="${r.encrypted_id}">Hapus</button>
              </td>
            </tr>`;
        });
      }
      $('#relasiBody').html(rows);
    }).fail(() => {
      $('#relasiBody').html(`<tr><td colspan="4" class="text-danger">Gagal memuat data.</td></tr>`);
    });
  }

  // === Simpan Tambah ===
  $('#formAdd').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
      url: "{{ route('storerelasibahanbaku') }}",
      method: "POST",
      data: $(this).serialize(),
      success: function(res) {
        if (res === "Success") {
          alert('✅ Relasi berhasil ditambahkan!');
          $('#formAdd')[0].reset();
          addModal.hide();
          loadRelasi();
        } else {
          alert('❌ Gagal menambahkan relasi!');
        }
      },
      error: function(xhr) {
        console.error(xhr.responseText);
        alert('Terjadi kesalahan pada server.');
      }
    });
  });

  // === Klik Edit ===
  $(document).on('click', '.editBtn', function() {
    $('#edit_relasi_id').val($(this).data('id'));
    $('#edit_r_produk').val($(this).data('produk'));
    $('#edit_r_bahan_baku').val($(this).data('bahanbaku'));
    $('#edit_qty_p_trans').val($(this).data('qty'));
    editModal.show();
  });

  // === Simpan Edit ===
  $('#formEdit').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
      url: "{{ route('updaterelasibahanbaku') }}",
      method: "POST",
      data: $(this).serialize(),
      success: function(res) {
        if (res === "Success") {
          alert('✅ Relasi berhasil diperbarui!');
          $('#formEdit')[0].reset();
          editModal.hide();
          loadRelasi();
        } else {
          alert('❌ Gagal memperbarui relasi!');
        }
      },
      error: function(xhr) {
        console.error(xhr.responseText);
        alert('Terjadi kesalahan pada server.');
      }
    });
  });

  // === Hapus Relasi ===
  $(document).on('click', '.deleteBtn', function() {
    const id = $(this).data('id');
    if (confirm('Yakin ingin menghapus relasi ini?')) {
      $.post("{{ route('deleterelasibahanbaku') }}", {
        _token: '{{ csrf_token() }}',
        hapus_relasi_id: id
      }, function(res) {
        if (res === "Success") {
          alert('🗑️ Relasi berhasil dihapus!');
          loadRelasi();
        } else {
          alert('❌ Gagal menghapus relasi!');
        }
      }).fail(() => alert('Terjadi kesalahan pada server.'));
    }
  });
});
</script>
@endsection
