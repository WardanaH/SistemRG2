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
                        <th>Hitung Luas</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="produkBody"></tbody>
            </table>
        </div>
    </div>
</div>

{{-- =============== MODAL TAMBAH =============== --}}
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
                            <select name="kategori" class="select2">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategories as $k)
                                <option value="{{ $k->id }}">{{ $k->Nama_Kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6"><input name="nama_produk" class="form-control" placeholder="Nama Produk"></div>
                        <div class="col-md-6">
                            <select name="satuan" class="select2">
                                <option value="">-- Satuan --</option>
                                <option value="CENTIMETER">Centimeter</option>
                                <option value="METER">Meter</option>
                                <option value="PCS">Pcs</option>
                                <option value="PAKET">Paket</option>
                            </select>
                        </div>
                        <div class="col-md-6"><input name="harga_beli" type="number" class="form-control" placeholder="Harga Beli"></div>
                        <div class="col-md-6"><input name="harga_jual" type="number" class="form-control" placeholder="Harga Jual"></div>
                        <div class="col-md-12"><textarea name="keterangan" class="form-control" placeholder="Keterangan"></textarea></div>
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

{{-- =============== MODAL EDIT =============== --}}
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
                            <select name="edit_kategori" id="edit_kategori" class="select2">
                                @foreach($kategories as $k)
                                <option value="{{ $k->id }}">{{ $k->Nama_Kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6"><input name="edit_nama_produk" id="edit_nama_produk" class="form-control" placeholder="Nama Produk"></div>
                        <div class="col-md-6">
                            <select name="edit_satuan" id="edit_satuan" class="select2">
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

{{-- =============== SCRIPT =============== --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {

    const addModal = new bootstrap.Modal(document.getElementById('addModal'));
    const editModal = new bootstrap.Modal(document.getElementById('editModal'));

    $('#openAddModal').on('click', () => addModal.show());

    loadProduk();

    function loadProduk() {
        $.get("{{ route('loadproduk') }}", function(res) {
            let rows = '';
            if (res.data.length === 0) {
                rows = `<tr><td colspan="8" class="text-muted">Belum ada data produk.</td></tr>`;
            } else {
                res.data.forEach(p => {
                    const warna = p.hitung_luas ? 'text-success fw-bold' : 'text-danger fw-bold';
                    const status = p.hitung_luas ? 'Ya' : 'Tidak';
                    rows += `
                        <tr>
                            <td>${p.nama_produk}</td>
                            <td>${p.satuan}</td>
                            <td>Rp ${parseInt(p.harga_beli).toLocaleString('id-ID')}</td>
                            <td>Rp ${parseInt(p.harga_jual).toLocaleString('id-ID')}</td>
                            <td>${p.kategori?.Nama_Kategori ?? '-'}</td>
                            <td class="${warna}">${status}</td>
                            <td>${p.keterangan ?? '-'}</td>
                            <td>
                                <button class="btn btn-warning btn-sm editBtn" data-id="${p.id}">Edit</button>
                                <button class="btn btn-danger btn-sm deleteBtn" data-id="${p.id}">Hapus</button>
                            </td>
                        </tr>`;
                });
            }
            $('#produkBody').html(rows);
        });
    }

    // ================= TAMBAH PRODUK =================
    $('#formAdd').on('submit', function(e) {
        e.preventDefault();

        $.post("{{ route('storeproduk') }}", $(this).serialize(), function(res) {
            if (res === "Success") {

                Swal.fire({
                    icon: 'success',
                    title: 'Produk berhasil ditambahkan!',
                    showConfirmButton: false,
                    timer: 1500
                });

                $('#formAdd')[0].reset();
                addModal.hide();
                loadProduk();
            } else {

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal menambahkan produk!'
                });

            }
        });
    });

    // ================= EDIT PRODUK =================
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
            }
        });
    });

    $('#formEdit').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: "{{ route('updateproduk') }}",
            method: "POST",
            data: $(this).serialize(),
            success: function(res) {

                if (res === "Success") {

                    Swal.fire({
                        icon: 'success',
                        title: 'Produk berhasil diperbarui!',
                        showConfirmButton: false,
                        timer: 1500
                    });

                    $('#formEdit')[0].reset();
                    editModal.hide();
                    loadProduk();

                } else {

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal memperbarui produk!'
                    });

                }
            },
            error: function(xhr) {

                if (xhr.status === 403) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Anda tidak memiliki izin untuk mengedit produk.'
                    });

                } else if (xhr.status === 419) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Session expired, silakan refresh halaman.'
                    });

                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi kesalahan: ' + xhr.status
                    });
                }
            }
        });
    });

    // ================= HAPUS PRODUK =================
    $(document).on('click', '.deleteBtn', function() {

        const id = $(this).data('id');

        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: "Produk akan hilang secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {

            if (result.isConfirmed) {

                $.ajax({
                    url: "{{ route('deleteproduk') }}",
                    method: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        hapus_produk_id: id
                    },
                    success: function(res) {
                        if (res === "Success") {

                            Swal.fire({
                                icon: 'success',
                                title: 'Produk berhasil dihapus!',
                                showConfirmButton: false,
                                timer: 1500
                            });

                            loadProduk();

                        } else {

                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal menghapus produk!'
                            });

                        }
                    },
                    error: function(xhr) {

                        if (xhr.status === 403) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Anda tidak memiliki izin untuk menghapus produk.'
                            });

                        } else if (xhr.status === 419) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Session expired, silakan refresh halaman.'
                            });

                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi kesalahan: ' + xhr.status
                            });
                        }
                    }
                });

            }

        });

    });

});
</script>

@endsection
