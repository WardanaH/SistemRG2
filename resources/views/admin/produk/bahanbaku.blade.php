@extends('layouts.app')
@section('content')

<div class="container-fluid">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">🧱 Manajemen Bahan Baku</h4>
            <button id="openAddModal" class="btn btn-light btn-sm">+ Tambah Bahan Baku</button>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped text-center align-middle" id="tabel_bahan">
                <thead class="table-primary">
                    <tr>
                        <th>Nama Bahan</th>
                        <th>Satuan</th>
                        <th>Harga</th>
                        <th>Batas Stok</th>
                        <th>Hitung Luas</th>
                        <th>Kategori</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="bahanBody"></tbody>
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
                    <h5 class="modal-title">Tambah Bahan Baku</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <select name="tambah_kategori_bb" class="form-select">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategories as $k)
                                <option value="{{ $k->id }}">{{ $k->Nama_Kategori }}</option>
                                @endforeach
                            </select>
                            <span style="color: red;">*</span>
                        </div>
                        <div class="col-md-6" style="display: flex; align-items: center;"><input name="tambah_nama_bahan" class="form-control" placeholder="Nama Bahan" required><span style="color: red;">*</span></div>
                        <div class="col-md-6">
                            <select name="tambah_satuan" class="form-select">
                                <option value="">-- Satuan --</option>
                                <option value="CENTIMETER">Centimeter</option>
                                <option value="METER">Meter</option>
                                <option value="PCS">Pcs</option>
                                <option value="PAKET">Paket</option>
                            </select>
                            <span style="color: red;">*</span>
                        </div>
                        <div class="col-md-6" style="display: flex; align-items: center;"><input name="tambah_harga" type="number" class="form-control" placeholder="Harga"><span style="color: red;">*</span></div>
                        <div class="col-md-6" style="display: flex; align-items: center;"><input name="tambah_batas_stok" type="number" class="form-control" placeholder="Batas Stok"><span style="color: red;">*</span></div>
                        <div class="col-md-12" style="display: flex; align-items: center;"><textarea name="tambah_keterangan" class="form-control" placeholder="Keterangan"></textarea><span style="color: red;">*</span></div>
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
                    <h5 class="modal-title">Edit Bahan Baku</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <select name="edit_kategori_bb" id="edit_kategori_bb" class="form-select">
                                @foreach($kategories as $k)
                                <option value="{{ $k->id }}">{{ $k->Nama_Kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6"><input name="edit_nama_bahan" id="edit_nama_bahan" class="form-control" placeholder="Nama Bahan"></div>
                        <div class="col-md-6">
                            <select name="edit_satuan" id="edit_satuan" class="form-select">
                                <option value="CENTIMETER">Centimeter</option>
                                <option value="METER">Meter</option>
                                <option value="PCS">Pcs</option>
                                <option value="PAKET">Paket</option>
                            </select>
                        </div>
                        <div class="col-md-6"><input name="edit_harga" id="edit_harga" type="number" class="form-control" placeholder="Harga"></div>
                        <div class="col-md-6"><input name="edit_batas_stok" id="edit_batas_stok" type="number" class="form-control" placeholder="Batas Stok"></div>
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
@endsection

{{-- SWEETALERT2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- =============== SCRIPT =============== --}}
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        const addModal = new bootstrap.Modal(document.getElementById('addModal'));
        const editModal = new bootstrap.Modal(document.getElementById('editModal'));

        $('#openAddModal').on('click', () => addModal.show());

        loadBahan();

        function loadBahan() {
            $.get("{{ route('bahanbaku.load') }}", function(res) {
                let rows = '';
                if (res.data.length === 0) {
                    rows = `<tr><td colspan="8" class="text-muted">Belum ada data bahan baku.</td></tr>`;
                } else {
                    res.data.forEach(b => {
                        const warna = b.hitung_luas ? 'text-success fw-bold' : 'text-danger fw-bold';
                        const status = b.hitung_luas ? 'Ya' : 'Tidak';
                        rows += `
                        <tr>
                            <td>${b.nama_bahan}</td>
                            <td>${b.satuan}</td>
                            <td>Rp ${parseInt(b.harga).toLocaleString('id-ID')}</td>
                            <td>${b.batas_stok}</td>
                            <td class="${warna}">${status}</td>
                            <td>${b.kategori?.Nama_Kategori ?? '-'}</td>
                            <td>${b.keterangan ?? '-'}</td>
                            <td>
                                <button class="btn btn-warning btn-sm editBtn" data-id="${b.id}">Edit</button>
                                <button class="btn btn-danger btn-sm deleteBtn" data-id="${b.id}">Hapus</button>
                            </td>
                        </tr>`;
                    });
                }
                $('#bahanBody').html(rows);
            });
        }

        // =========================
        // TAMBAH BAHAN
        // =========================
        $('#formAdd').on('submit', function(e) {
            e.preventDefault();

            $.post("{{ route('storebahanbaku') }}", $(this).serialize(), function(res) {
                if (res === "Success") {

                    Swal.fire({
                        icon: "success",
                        title: "Berhasil!",
                        text: "Bahan baku berhasil ditambahkan!",
                        timer: 1500,
                        showConfirmButton: false
                    });

                    $('#formAdd')[0].reset();
                    addModal.hide();
                    loadBahan();

                } else {
                    Swal.fire("Gagal!", "Gagal menambahkan bahan baku!", "error");
                }
            });
        });


        // =========================
        // EDIT BAHAN - klik tombol
        // =========================
        $(document).on('click', '.editBtn', function() {
            const id = $(this).data('id');

            $.get("{{ route('bahanbaku.load') }}", function(res) {
                const b = res.data.find(x => x.id == id);
                if (b) {
                    $('#edit_produk_id').val(b.id);
                    $('#edit_kategori_bb').val(b.kategori_id);
                    $('#edit_nama_bahan').val(b.nama_bahan);
                    $('#edit_satuan').val(b.satuan);
                    $('#edit_harga').val(b.harga);
                    $('#edit_batas_stok').val(b.batas_stok);
                    $('#edit_keterangan').val(b.keterangan);

                    editModal.show();
                }
            });
        });


        // =========================
        // UPDATE BAHAN
        // =========================
        $('#formEdit').on('submit', function(e) {
            e.preventDefault();

            $.post("{{ route('updatebahanbaku') }}", $(this).serialize(), function(res) {
                if (res === "Success") {

                    Swal.fire({
                        icon: "success",
                        title: "Berhasil!",
                        text: "Bahan baku berhasil diperbarui!",
                        timer: 1500,
                        showConfirmButton: false
                    });

                    $('#formEdit')[0].reset();
                    editModal.hide();
                    loadBahan();

                } else {
                    Swal.fire("Gagal!", "Gagal memperbarui bahan baku!", "error");
                }
            });
        });


        // =========================
        // HAPUS BAHAN
        // =========================
        $(document).on('click', '.deleteBtn', function() {

            const id = $(this).data('id');

            Swal.fire({
                title: "Yakin ingin menghapus?",
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal",
                reverseButtons: true
            }).then((result) => {

                if (result.isConfirmed) {
                    $.post("{{ route('deletebahanbaku') }}", {
                        _token: '{{ csrf_token() }}',
                        hapus_bahan_baku_id: id
                    }, function(res) {

                        if (res === "Success") {
                            Swal.fire("Berhasil!", "Bahan baku berhasil dihapus!", "success");
                            loadBahan();
                        } else {
                            Swal.fire("Gagal!", "Gagal menghapus bahan baku!", "error");
                        }

                    });
                }

            });

        });
    });
</script>
@endpush
