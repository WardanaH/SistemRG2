@extends('layouts.app')

@section('title', 'Daftar Jenis Pelanggan')

@section('content')
<div class="container py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">📋 Daftar Jenis Pelanggan</h3>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-circle"></i> Tambah Jenis Pelanggan
        </button>
    </div>

    {{-- Notifikasi lama tetap, nanti diambil oleh SweetAlert --}}
    @if(session('success'))
        <div id="success-message" data-message="{{ session('success') }}"></div>
    @endif

    @if($errors->any())
        <div id="error-message" data-message="{{ $errors->first() }}"></div>
    @endif

    {{-- Table Data --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-primary">
                    <tr>
                        <th width="60" class="text-center">#</th>
                        <th>Jenis Pelanggan</th>
                        <th width="180" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jenispelanggans as $index => $jp)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $jp->jenis_pelanggan }}</td>
                        <td class="text-center">

                            {{-- Tombol Edit --}}
                            <button class="btn btn-info btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEdit{{ $jp->id }}">
                                ✏️ Edit
                            </button>

                            {{-- Tombol Hapus (SweetAlert) --}}
                            <form action="{{ route('jenispelanggan.destroy') }}"
                                  method="POST"
                                  class="d-inline form-delete">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="hapus_jenispelanggan_id"
                                       value="{{ encrypt($jp->id) }}">

                                <button type="submit" class="btn btn-danger btn-sm">
                                    🗑️ Hapus
                                </button>
                            </form>
                        </td>
                    </tr>

                    {{-- Modal Edit --}}
                    <div class="modal fade" id="modalEdit{{ $jp->id }}"
                        tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <form method="POST" action="{{ route('jenispelanggan.update') }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="jenispelanggan_id"
                                    value="{{ encrypt($jp->id) }}">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Jenis Pelanggan</h5>
                                        <button type="button"
                                            class="btn-close"
                                            data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Nama Jenis Pelanggan</label>
                                            <input type="text" name="edit_jenispelanggan"
                                                class="form-control"
                                                value="{{ $jp->jenis_pelanggan }}" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button"
                                            class="btn btn-secondary"
                                            data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">
                                            Simpan Perubahan
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">
                            Belum ada data jenis pelanggan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('jenispelanggan.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Jenis Pelanggan</h5>
                    <button type="button" class="btn-close"
                        data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Jenis Pelanggan</label>
                        <input type="text" name="tambah_jenispelanggan"
                               class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    // ==== SweetAlert success ====
    const successMsg = document.getElementById("success-message");
    if (successMsg) {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: successMsg.dataset.message,
        });
    }

    // ==== SweetAlert error ====
    const errorMsg = document.getElementById("error-message");
    if (errorMsg) {
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: errorMsg.dataset.message,
        });
    }

    // ==== Konfirmasi hapus ====
    document.querySelectorAll('.form-delete').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });

        });
    });

});
</script>
@endpush
