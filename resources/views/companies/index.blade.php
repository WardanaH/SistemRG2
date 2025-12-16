@extends('companies.layout')

@section('content')

<h3>Daftar Perusahaan</h3>

<button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createCompanyModal">
    Tambah Perusahaan
</button>


<table class="table table-bordered">
    <thead class="table-light">
        <tr>
            <th>#</th>
            <th>Nama Perusahaan</th>
            <th>Email</th>
            <th>Deskripsi</th>
            <th width="200px">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($companies as $company)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $company->name }}</td>
            <td>{{ $company->email }}</td>
            <td>{{ $company->descriptions }}</td>
            <td>

                {{-- DETAIL --}}
                <a href="{{ route('companies.show', $company->id) }}" class="btn btn-info btn-sm">
                    Detail
                </a>

                {{-- EDIT --}}
                <button type="button"
                    class="btn btn-warning btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#editCompanyModal{{ $company->id }}">
                    Edit
                </button>

                {{-- DELETE --}}
                <form action="{{ route('companies.destroy', $company->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        onclick="return confirm('Yakin ingin menghapus perusahaan ini?')"
                        class="btn btn-danger btn-sm">
                        Hapus
                    </button>
                </form>

            </td>
        </tr>

        {{-- ========= MODAL EDIT PERUSAHAAN ========= --}}
        <div class="modal fade" id="editCompanyModal{{ $company->id }}" tabindex="-1"
            aria-labelledby="editCompanyModalLabel{{ $company->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCompanyModalLabel{{ $company->id }}">
                            Edit Perusahaan - {{ $company->name }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form action="{{ route('companies.update', $company->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Nama Perusahaan</label>
                                <input type="text" class="form-control"
                                    name="name" value="{{ $company->name }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email Perusahaan</label>
                                <input type="email" class="form-control"
                                    name="email" value="{{ $company->email }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea class="form-control"
                                    name="descriptions" rows="3" required>{{ $company->descriptions }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        @endforeach

        @if($companies->count() == 0)
        <tr>
            <td colspan="5" class="text-center">Belum ada perusahaan.</td>
        </tr>
        @endif
    </tbody>
</table>


{{-- ========= MODAL TAMBAH ========= --}}
<div class="modal fade" id="createCompanyModal" tabindex="-1" aria-labelledby="createCompanyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createCompanyModalLabel">Tambah Perusahaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('companies.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama Perusahaan</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email Perusahaan</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="descriptions" rows="3" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
