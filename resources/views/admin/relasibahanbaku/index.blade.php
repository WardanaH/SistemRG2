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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(function() {

  const addModal = new bootstrap.Modal(document.getElementById('addModal'));
  const editModal = new bootstrap.Modal(document.getElementById('editModal'));

  $('#openAddModal').on('click', () => addModal.show());

  loadRelasi();

  // ======================== LOAD DATA ========================
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
                <button class="btn btn-warning btn-sm editBtn"
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

  // ======================== TAMBAH ========================
  $('#formAdd').on('submit', function(e) {
    e.preventDefault();

    $.ajax({
      url: "{{ route('storerelasibahanbaku') }}",
      method: "POST",
      data: $(this).serialize(),
      success: function(res) {

        if (res === "Success") {
          Swal.fire({
            icon: 'success',
            title: 'Relasi berhasil ditambahkan!',
            showConfirmButton: false,
            timer: 1500
          });

          $('#formAdd')[0].reset();
          addModal.hide();
          loadRelasi();

        } else {
          Swal.fire({
            icon: 'error',
            title: 'Gagal menambahkan relasi!'
          });
        }

      },
      error: function(xhr) {
        Swal.fire({
          icon: 'error',
          title: 'Terjadi kesalahan pada server.'
        });
      }
    });

  });

  // ======================== EDIT (OPEN MODAL) ========================
  $(document).on('click', '.editBtn', function() {
    $('#edit_relasi_id').val($(this).data('id'));
    $('#edit_r_produk').val($(this).data('produk'));
    $('#edit_r_bahan_baku').val($(this).data('bahanbaku'));
    $('#edit_qty_p_trans').val($(this).data('qty'));

    editModal.show();
  });

  // ======================== UPDATE ========================
  $('#formEdit').on('submit', function(e) {
    e.preventDefault();

    $.ajax({
      url: "{{ route('updaterelasibahanbaku') }}",
      method: "POST",
      data: $(this).serialize(),
      success: function(res) {

        if (res === "Success") {
          Swal.fire({
            icon: 'success',
            title: 'Relasi berhasil diperbarui!',
            showConfirmButton: false,
            timer: 1500
          });

          $('#formEdit')[0].reset();
          editModal.hide();
          loadRelasi();

        } else {
          Swal.fire({
            icon: 'error',
            title: 'Gagal memperbarui relasi!'
          });
        }

      },
      error: function(xhr) {
        Swal.fire({
          icon: 'error',
          title: 'Terjadi kesalahan pada server.'
        });
      }
    });

  });

  // ======================== HAPUS ========================
  $(document).on('click', '.deleteBtn', function() {

    const id = $(this).data('id');

    Swal.fire({
      title: 'Yakin ingin menghapus?',
      text: 'Relasi akan hilang secara permanen.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Ya, Hapus',
      cancelButtonText: 'Batal',
      reverseButtons: true
    }).then((result) => {

      if (result.isConfirmed) {

        $.post("{{ route('deleterelasibahanbaku') }}", {
          _token: '{{ csrf_token() }}',
          hapus_relasi_id: id
        }, function(res) {

          if (res === "Success") {

            Swal.fire({
              icon: 'success',
              title: 'Relasi berhasil dihapus!',
              showConfirmButton: false,
              timer: 1500
            });

            loadRelasi();

          } else {

            Swal.fire({
              icon: 'error',
              title: 'Gagal menghapus relasi!'
            });

          }

        }).fail(() => {
          Swal.fire({
            icon: 'error',
            title: 'Terjadi kesalahan pada server.'
          });
        });

      }

    });

  });

});
</script>
@endsection
