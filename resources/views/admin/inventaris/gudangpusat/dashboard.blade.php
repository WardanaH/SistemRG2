@extends('admin.inventaris.gudangpusat.layout.app')

@section('title', 'Dashboard')

@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>Hi, welcome back INVENTORY!</h4>
            <p class="mb-0">Pantau Aktivitas Hari Ini !</p>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Layout</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Blank</a></li>
        </ol>
    </div>
</div>

{{-- ===================== STATISTIK ATAS ===================== --}}
<div class="row">

    {{-- Total Bahan Baku --}}
    <div class="col-lg-4 col-md-6">
        <div class="card">
            <div class="stat-widget-one card-body">
                <div class="stat-icon d-inline-block">
                    <i class="ti-package text-primary border-primary"></i>
                </div>
                <div class="stat-content d-inline-block">
                    <div class="stat-text">Total Bahan Baku</div>
                    <div class="stat-digit">{{ $totalBahan }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Pengiriman Hari Ini --}}
    <div class="col-lg-4 col-md-6">
        <div class="card">
            <div class="stat-widget-one card-body">
                <div class="stat-icon d-inline-block">
                    <i class="ti-truck text-success border-success"></i>
                </div>
                <div class="stat-content d-inline-block">
                    <div class="stat-text">Pengiriman Hari Ini</div>
                    <div class="stat-digit">{{ $pengirimanHariIni }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Stok Masuk Hari Ini --}}
    <div class="col-lg-4 col-md-12">
        <div class="card">
            <div class="stat-widget-one card-body">
                <div class="stat-icon d-inline-block">
                    <i class="ti-import text-warning border-warning"></i>
                </div>
                <div class="stat-content d-inline-block">
                    <div class="stat-text">Stok Masuk Hari Ini</div>
                    <div class="stat-digit">
                        {{ number_format($stokMasukHariIni) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ================== STOK KRITIS + PIE CHART ================== --}}
<div class="row mt-4">

    {{-- ====== TABEL STOK KRITIS (LEBAR) ====== --}}
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header">
                <h4 class="card-title">Stok Kritis & Habis Cabang</h4>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th style="width:60px">No</th>
                                <th>Cabang</th>
                                <th>Nama Bahan</th>
                                <th style="width:100px">Stok</th>
                                <th style="width:110px">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($stokKritis as $item)
                                <tr>
                                    <td>
                                        {{ ($stokKritis->currentPage() - 1) * $stokKritis->perPage() + $loop->iteration }}
                                    </td>
                                    <td>{{ $item->nama_cabang }}</td>
                                    <td>{{ $item->nama_bahan }}</td>
                                    <td>{{ $item->banyak_stok }}</td>
                                    <td>
                                        @if ($item->banyak_stok == 0)
                                            <span class="badge badge-danger">HABIS</span>
                                        @else
                                            <span class="badge badge-warning">KRITIS</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        Tidak ada stok kritis
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-end mt-3">
                    <style>
                        .pagination .page-link {
                            color: #000 !important;
                        }
                        .pagination .page-item.active .page-link {
                            background-color: #0f58a7;
                            border-color: #0f58a7;
                            color: #fff !important;
                        }
                    </style>

                    {{ $stokKritis->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>

    {{-- ====== PIE CHART ====== --}}
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header bg-primary text-white">
                <strong>Distribusi Pengiriman per Cabang (Bulan Ini)</strong>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <canvas id="piePengirimanCabang" height="260"></canvas>
            </div>
        </div>
    </div>

</div>


{{-- ===================== BAGIAN BAWAH (TIDAK DIUBAH) ===================== --}}
{{-- <div class="row"> --}}
    {{-- Timeline --}}
    {{-- <div class="col-lg-6 col-xl-4 col-xxl-6 col-md-6">
        <div class="card">
            <div class="card-header"><h4 class="card-title">Timeline</h4></div>
            <div class="card-body">
                <div class="widget-timeline">
                    <ul class="timeline">
                        <li>
                            <div class="timeline-badge primary"></div>
                            <a class="timeline-panel text-muted" href="#">
                                <span>10 minutes ago</span>
                                <h6 class="m-t-5">Youtube goes live.</h6>
                            </a>
                        </li>
                        <li>
                            <div class="timeline-badge danger"></div>
                            <a class="timeline-panel text-muted" href="#">
                                <span>30 minutes ago</span>
                                <h6 class="m-t-5">Google acquires Youtube.</h6>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div> --}}

    {{-- Notice Board --}}
    {{-- <div class="col-xl-4 col-lg-6 col-xxl-6 col-md-6">
        <div class="card">
            <div class="card-header"><h4 class="card-title">Notice Board</h4></div>
            <div class="card-body">
                <div class="recent-comment m-t-15">
                    <div class="media">
                        <div class="media-left">
                            <a href="#"><img class="media-object mr-3" src="{{ asset('images/avatar/4.png') }}" alt="..."></a>
                        </div>
                        <div class="media-body">
                            <h4 class="media-heading text-primary">John Doe</h4>
                            <p>Cras sit amet nibh libero, in gravida nulla.</p>
                            <p class="comment-date">10 min ago</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    {{-- Todo --}}
    {{-- <div class="col-xl-4 col-xxl-6 col-lg-6 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-header"><h4 class="card-title">Todo</h4></div>
            <div class="card-body px-0">
                <div class="todo-list">
                    <div class="tdl-holder">
                        <div class="tdl-content widget-todo2 mr-4">
                            <ul id="todo_list">
                                <li><label><input type="checkbox"><i></i><span>Get up</span></label></li>
                                <li><label><input type="checkbox" checked><i></i><span>Stand up</span></label></li>
                                <li><label><input type="checkbox"><i></i><span>Do something else</span></label></li>
                            </ul>
                        </div>
                        <div class="px-4">
                            <input type="text" class="tdl-new form-control" placeholder="Write new item and hit 'Enter'...">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('piePengirimanCabang');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: {!! json_encode($pengirimanPerCabang->pluck('nama_cabang')) !!},
            datasets: [{
                data: {!! json_encode($pengirimanPerCabang->pluck('total')) !!}
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
});
</script>
@endpush
