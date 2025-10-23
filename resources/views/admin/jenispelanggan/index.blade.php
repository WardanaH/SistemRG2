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

    {{-- Notifikasi --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ $errors->first() }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
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
                            <button class="btn btn-info btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEdit{{ $jp->id }}">
                                ✏️ Edit
                            </button>
                            <button class="btn btn-danger btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#modalHapus{{ $jp->id }}">
                                🗑️ Hapus
                            </button>
                        </td>
                    </tr>

                    {{-- Modal Edit --}}
                    <div class="modal fade" id="modalEdit{{ $jp->id }}" tabindex="-1" aria-labelledby="modalEditLabel{{ $jp->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <form method="POST" action="{{ route('jenispelanggan.update') }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="jenispelanggan_id" value="{{ encrypt($jp->id) }}">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Jenis Pelanggan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Nama Jenis Pelanggan</label>
                                            <input type="text" name="edit_jenispelanggan"
                                                value="{{ $jp->jenis_pelanggan }}"
                                                class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Modal Hapus --}}
                    <div class="modal fade" id="modalHapus{{ $jp->id }}" tabindex="-1" aria-labelledby="modalHapusLabel{{ $jp->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <form method="POST" action="{{ route('jenispelanggan.destroy') }}">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="hapus_jenispelanggan_id" value="{{ encrypt($jp->id) }}">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title text-danger">Hapus Jenis Pelanggan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        Apakah kamu yakin ingin menghapus
                                        <strong>{{ $jp->jenis_pelanggan }}</strong>?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-danger">Hapus</button>
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
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('jenispelanggan.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Jenis Pelanggan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Jenis Pelanggan</label>
                        <input type="text" name="tambah_jenispelanggan" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
