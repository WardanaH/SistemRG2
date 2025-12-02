@extends('layouts.app')

@section('content')

<div class="container">

    <h3 class="mb-4">Manajemen Cabang</h3>

    <!-- <button class="btn btn-primary mb-3" onclick="tambahCabang()">Tambah Cabang</button> -->

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama</th>
                <th>Slug</th>
                <th>Telepon</th>
                <th>Email</th>
                <th>Jenis</th>
                <th width="150">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cabangs as $c)
                <tr>
                    <td>{{ $c->kode }}</td>
                    <td>{{ $c->nama }}</td>
                    <td>{{ $c->slug }}</td>
                    <td>{{ $c->telepon }}</td>
                    <td>{{ $c->email }}</td>
                    <td>{{ $c->jenis }}</td>
                    <td>

                        {{-- BUTTON EDIT --}}
                        <button class="btn btn-warning btn-sm"
                            onclick="editCabang({{ $c }})">
                            Edit
                        </button>

                        {{-- FORM DELETE (PAKE ID AGAR DIPANGGIL SWEETALERT) --}}
                        <form id="form-delete-{{ $c->id }}"
                              action="{{ route('cabangs.destroy', $c->id) }}"
                              method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')

                            <button type="button" class="btn btn-danger btn-sm"
                                onclick="hapusCabang({{ $c->id }})">
                                Hapus
                            </button>
                        </form>

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>

{{-- ============== MODAL TAMBAH / EDIT ============== --}}
<div class="modal fade" id="modalCabang">
    <div class="modal-dialog">
        <div class="modal-content">

            <form id="formCabang" method="POST">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="titleModal"></h5>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">

                    <input type="hidden" id="idCabang">

                    <div class="form-group">
                        <label>Kode</label>
                        <input type="text" name="kode" id="kode" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" name="nama" id="nama" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Slug</label>
                        <input type="text" name="slug" id="slug" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Jenis</label>
                        <select name="jenis" id="jenis" class="form-control" required>
                            <option value="pusat">Pusat</option>
                            <option value="cabang">Cabang</option>
                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </div>

            </form>

        </div>
    </div>
</div>

@endsection

{{-- ====================== SCRIPTS ====================== --}}
@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // ========== TAMBAH ==========
    function tambahCabang() {
        $('#titleModal').text('Tambah Cabang');
        $('#formCabang').attr('action', '{{ route("cabangs.store") }}');

        $('#idCabang').val('');
        $('#kode').val('');
        $('#nama').val('');
        $('#slug').val('');
        $('#jenis').val('cabang');

        $('#modalCabang').modal('show');
    }

    // ========== EDIT ==========
    function editCabang(data) {
        $('#titleModal').text('Edit Cabang');
        $('#formCabang').attr('action', '/cabangs/update/' + data.id);

        $('#idCabang').val(data.id);
        $('#kode').val(data.kode);
        $('#nama').val(data.nama);
        $('#slug').val(data.slug);
        $('#jenis').val(data.jenis);

        $('#modalCabang').modal('show');
    }

    // ========== SWEETALERT DELETE ==========
    function hapusCabang(id) {
        Swal.fire({
            title: "Hapus Cabang?",
            text: "Data yang dihapus tidak dapat dikembalikan.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Ya, hapus!",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-delete-' + id).submit();
            }
        });
    }
</script>

@endsection
