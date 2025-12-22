@extends('companies.layout')

@section('content')

<h3>Daftar Tugas Proyek - {{ $project->name }} </h3>
<a href="{{ route('companies.show', $project->m_company_id) }}" class="btn btn-light me-2">Kembali</a>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Nama Tugas</th>
            <th>Status Progress</th>
            <th>Progress</th>
            <th>Bukti</th>
            <th>Notes</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($progress as $p)
        <tr data-row-id="{{ $p->id }}">
            <td>{{ $loop->iteration }}</td>

            <td>{{ $p->mTask->name }}</td>

            <td>
                <span class="badge bg-warning" data-badge-id="{{ $p->id }}">
                    {{ $p->status_progress }}
                </span>
            </td>

            {{-- SEMUA HANYA CHECKBOX --}}
            <td>
                <div class="form-check">
                    <input type="checkbox"
                        class="form-check-input progress-checkbox"
                        data-id="{{ $p->id }}"
                        id="chk-{{ $p->id }}"
                        {{ $p->status_progress === 'Selesai' ? 'checked' : '' }}>
                    <label class="form-check-label" for="chk-{{ $p->id }}">Selesai</label>
                </div>
            </td>

            {{-- UPLOAD BUKTI --}}
            <td data-upload-id="{{ $p->id }}">
                @if ($p->status_progress == 'Selesai')
                <form action="{{ route('progress.upload', $p->id) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="file_bukti" class="form-control mb-2">
                    <button class="btn btn-sm btn-success">Upload</button>
                </form>

                @if ($p->file_bukti)
                <a href="{{ asset('uploads/bukti/' . $p->file_bukti) }}"
                    target="_blank">Lihat Bukti</a>
                @endif
                @else
                <span class="text-muted">Selesai dulu</span>
                @endif
            </td>

            <td data-note-id="{{ $p->id }}">
                <form action="{{ route('progress.note', $p->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <input type="text"
                            class="form-control note-input"
                            data-id="{{ $p->id }}"
                            name="notes"
                            value="{{ $p->notes }}"
                            {{ $p->status_progress === 'Pending' ? 'disabled readonly' : '' }}>
                    </div>

                    <button type="submit"
                        class="btn btn-primary note-btn"
                        data-id="{{ $p->id }}"
                        {{ $p->status_progress === 'Pending' ? 'disabled' : '' }}>
                        Simpan
                    </button>
                </form>
            </td>

        </tr>
        @endforeach
    </tbody>
</table>

<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    document.addEventListener('DOMContentLoaded', () => {

        const baseUrl = "{{ url('/progress') }}";
        const token = document.querySelector('meta[name="csrf-token"]').content;

        async function updateStatus(id, status) {

            const res = await fetch(`${baseUrl}/${id}/update-status`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": token,
                    "Accept": "application/json"
                },
                body: JSON.stringify({
                    status_progress: status
                })
            });

            const data = await res.json();

            const badge = document.querySelector(`[data-badge-id="${id}"]`);
            badge.textContent = data.status;

            // ==== UPDATE WARNA BADGE ====
            badge.classList.remove("bg-info", "bg-success", "bg-warning");
            if (data.status === "Selesai") {
                badge.classList.add("bg-success");
            } else {
                badge.classList.add("bg-warning");
            }

            const uploadCell = document.querySelector(`[data-upload-id="${id}"]`);

            if (data.status === "Selesai") {
                uploadCell.innerHTML = `
                <form action="/progress/${id}/upload" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="${token}">
                    <input type="file" name="file_bukti" class="form-control mb-2">
                    <button class="btn btn-sm btn-success">Upload</button>
                </form>
            `;
            } else {
                uploadCell.innerHTML = `<span class="text-muted">Selesai dulu</span>`;
            }

            toggleNotes(id, data.status);
        }


        document.querySelectorAll('.progress-checkbox').forEach(el => {
            el.addEventListener('change', function() {
                updateStatus(this.dataset.id, this.checked ? "Selesai" : "Pending");
            });
        });

        function toggleNotes(id, status) {
            const noteInput = document.querySelector(`.note-input[data-id="${id}"]`);
            const noteBtn = document.querySelector(`.note-btn[data-id="${id}"]`);

            if (status === "Selesai") {
                noteInput.removeAttribute("disabled");
                noteInput.removeAttribute("readonly");
                noteBtn.removeAttribute("disabled");
            } else {
                noteInput.setAttribute("disabled", true);
                noteInput.setAttribute("readonly", true);
                noteBtn.setAttribute("disabled", true);
            }
        }

        // ==== ATUR KONDISI NOTES SAAT PAGE LOAD ====
        document.querySelectorAll('.note-input').forEach(input => {
            const id = input.dataset.id;
            const checkbox = document.querySelector(`.progress-checkbox[data-id="${id}"]`);
            toggleNotes(id, checkbox.checked ? "Selesai" : "Pending");
        });

    });
</script>

@endsection
