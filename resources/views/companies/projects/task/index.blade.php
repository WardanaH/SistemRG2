@extends('companies.layout')

@section('content')

<h3>Daftar Tugas - {{ $company->name }}</h3>

<button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createTaskModal">Tambah Tugas</button>
<a href="{{ route('companies.show', $company->id) }}" class="btn btn-light me-2">Kembali</a>
<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Nama Tugas</th>
            <th>Deskripsi</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($tasks as $task)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $task->name }}</td>
            <td>{{ $task->description }}</td>
            <td>

                <!-- Edit Button (open modal) -->
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                    data-bs-target="#editTaskModal{{ $task->id }}">
                    <i class="bi bi-pencil"></i>
                </button>

                <!-- Hapus Button -->
                <form action="{{ route('task.destroy', [$company->id, $task->id]) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus tugas ini?')">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>

            </td>
        </tr>

        {{-- modal edit --}}
        <div class="modal fade" id="editTaskModal{{ $task->id }}" tabindex="-1" aria-labelledby="editTaskModalLabel{{ $task->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editTaskModalLabel{{ $task->id }}">Edit Tugas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('task.update', [$company->id, $task->id]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label">Nama Tugas</label>
                                <input type="text" class="form-control" name="name" value="{{ $task->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea class="form-control" name="description" rows="3" required>{{ $task->description }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </tbody>
</table>

{{-- modal --}}
<div class="modal fade" id="createTaskModal" tabindex="-1" aria-labelledby="createTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createTaskModalLabel">Tambah Tugas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('task.store', $company->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="company_id" value="{{ $company->id }}">
                    <div class="mb-3">
                        <label class="form-label">Nama Tugas</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="description" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection
