@extends('layouts.app')

@section('content')
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fa fa-history"></i> Log Aktivitas Pengguna</h5>
                    <small>Memantau aktivitas user lain</small>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-bordered align-middle" id="tableLog">
                            <thead class="table-secondary text-center">
                                <tr>
                                    <th style="width: 5%">No</th>
                                    <th style="width: 15%">Waktu</th>
                                    <th style="width: 15%">User</th>
                                    <th style="width: 10%">Jenis</th>
                                    <th>Deskripsi Aktivitas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">
                                        {{ $log->created_at->format('d M Y') }}<br>
                                        <small class="text-muted">{{ $log->created_at->format('H:i:s') }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $log->user->name ?? $log->user->username ?? 'User Terhapus' }}</strong>
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $badgeClass = 'bg-secondary';
                                            $jenis = strtolower($log->jenis_log);

                                            if(str_contains($jenis, 'tambah') || str_contains($jenis, 'create')) {
                                                $badgeClass = 'bg-success';
                                            } elseif(str_contains($jenis, 'edit') || str_contains($jenis, 'update') || str_contains($jenis, 'perbaruan')) {
                                                $badgeClass = 'bg-warning text-dark';
                                            } elseif(str_contains($jenis, 'hapus') || str_contains($jenis, 'delete')) {
                                                $badgeClass = 'bg-danger';
                                            } elseif(str_contains($jenis, 'login')) {
                                                $badgeClass = 'bg-info text-dark';
                                            }
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ $log->jenis_log }}</span>
                                    </td>
                                    <td>
                                        {{ $log->log }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="fa fa-info-circle"></i> Belum ada data log aktivitas.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <small class="text-muted">* Log ini menampilkan aktivitas user selain akun Anda saat ini.</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Opsional: Aktifkan DataTables jika projectmu sudah install DataTables
        $('#tableLog').DataTable({
            "order": [[ 0, "desc" ]], // Urutkan dari yang terbaru (berdasarkan No/Iterasi)
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Indonesian.json"
            }
        });
    });
</script>
@endpush
