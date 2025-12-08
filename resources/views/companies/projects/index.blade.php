@extends('companies.layout')

@section('content')

<h3>Daftar Proyek — {{ $company->name }}</h3>

<div class="d-flex gap-2">
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createProjectModal">
        Tambah Proyek
    </button>

    <button type="button" class="btn btn-success mb-3">
        <a href="{{ route('task.index', $company->id) }}" class="text-white">Daftar Tugas {{ $company->name }}</a>
    </button>

    <a href="{{ route('companies.index') }}" class="btn btn-light me-2">Kembali</a>

</div>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Nama Proyek</th>
            <th>Deskripsi</th>
            <th>Nilai Proyek</th>
            <th>Status</th>
            <th>Paid Status</th>
            <th>File Bukti</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($projects as $project)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $project->name }}</td>
            <td>{{ $project->description }}</td>
            <td>Rp {{ number_format($project->value_projects, 0, ',', '.') }}</td>
            <td>
                <span class="badge bg-info text-black">{{ $project->status }}</span>
            </td>
            <td>
                <span class="badge bg-warning text-black">{{ $project->paid_status }}</span>
            </td>
            <td>

                {{-- Jika sudah LUNAS --}}
                @if($project->paid_status == 'Lunas' && $project->file_bukti)
                <a href="{{ asset('storage/'.$project->file_bukti) }}"
                    target="_blank" class="btn btn-sm btn-success">
                    Lihat Bukti
                </a>

                {{-- Jika SELESAI tapi belum ada bukti --}}
                @elseif($project->status == 'Selesai' && !$project->file_bukti)
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                    data-bs-target="#uploadProofModal{{ $project->id }}">
                    Upload Bukti
                </button>

                {{-- Jika ada bukti, tapi belum lunas --}}
                @elseif($project->file_bukti)
                <a href="{{ asset('storage/'.$project->file_bukti) }}"
                    target="_blank" class="btn btn-sm btn-success">
                    Lihat Bukti
                </a>

                @else
                <span class="text-muted">Belum Selesai</span>
                @endif

            </td>

            <td>

                {{-- Jika sudah LUNAS, disable semua tombol --}}
                @if($project->paid_status == 'Lunas')

                <a href="{{ route('projects.progress', $project->id) }}"
                    class="btn btn-sm btn-success">
                    Progress
                </a>
                <button class="btn btn-sm btn-secondary" disabled>Edit</button>
                <button class="btn btn-sm btn-secondary" disabled>Hapus</button>

                @else

                <!-- Progress -->
                <a href="{{ route('projects.progress', $project->id) }}"
                    class="btn btn-sm btn-success">
                    Progress
                </a>

                <!-- Edit -->
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                    data-bs-target="#editProjectModal{{ $project->id }}">
                    Edit
                </button>

                <!-- Hapus -->
                <form action="{{ route('projects.destroy', $project->id) }}"
                    method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
                        Hapus
                    </button>
                </form>

                @endif

            </td>

        </tr>

        <div class="modal fade" id="uploadProofModal{{ $project->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Upload Bukti Pembayaran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <form action="{{ route('projects.upload-proof', $project->id) }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">File Bukti (jpg, png, pdf)</label>
                                <input type="file" class="form-control" name="proof_file" required>
                            </div>

                            <button type="submit" class="btn btn-success">Upload</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        {{-- ======================== EDIT MODAL ======================== --}}
        <div class="modal fade" id="editProjectModal{{ $project->id }}" tabindex="-1"
            aria-labelledby="editProjectModalLabel{{ $project->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Proyek</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <form action="{{ route('projects.update', $project->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="company_id" value="{{ $company->id }}">

                            <div class="mb-3">
                                <label class="form-label">Nama Proyek</label>
                                <input type="text" class="form-control" name="name" value="{{ $project->name }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea class="form-control" name="description" rows="3" required>{{ $project->description }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nilai Proyek</label>
                                <input type="number" class="form-control" name="value_projects" value="{{ $project->value_projects }}" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        {{-- ====================== END EDIT MODAL ====================== --}}

        @empty
        <tr>
            <td colspan="7" class="text-center">Belum ada proyek</td>
        </tr>
        @endforelse
    </tbody>
</table>



{{-- ======================== MODAL TAMBAH ======================== --}}
<div class="modal fade" id="createProjectModal" tabindex="-1" aria-labelledby="createProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Proyek</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form action="{{ route('projects.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="company_id" value="{{ $company->id }}">

                    <div class="mb-3">
                        <label class="form-label">Nama Proyek</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="description" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nilai Proyek</label>
                        <input type="number" class="form-control" name="value_projects" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>

        </div>
    </div>
</div>

@endsection
