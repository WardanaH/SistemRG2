@extends('admin.inventaris.templateinventaris.layout_cabang.app')

@section('title', 'Dashboard')

@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>Hi, welcome back Inventory Cabang!</h4>
            <p class="mb-0">Dashboard Monitoring Stok Cabang</p>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Cabang</a></li>
        </ol>
    </div>
</div>

{{-- ================= STATISTIK ATAS ================= --}}
<div class="row">

    {{-- Total Stok Masuk Hari Ini --}}
    <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="card">
            <div class="stat-widget-one card-body">
                <div class="stat-icon d-inline-block">
                    <i class="ti-import text-warning border-warning"></i>
                </div>
                <div class="stat-content d-inline-block">
                    <div class="stat-text">Total Stok Masuk Hari Ini</div>
                    <div class="stat-digit">
                        {{ number_format($totalStokMasukHariIni ?? 0) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Total Pengiriman Hari Ini --}}
    <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="card">
            <div class="stat-widget-one card-body">
                <div class="stat-icon d-inline-block">
                    <i class="ti-truck text-success border-success"></i>
                </div>
                <div class="stat-content d-inline-block">
                    <div class="stat-text">Total Pengiriman Hari Ini</div>
                    <div class="stat-digit">
                        {{ $totalPengirimanHariIni ?? 0 }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ================= LINE CHART ================= --}}
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    Grafik Total Stok Masuk
                </h4>
            </div>
            <div class="card-body">
                {{-- PENTING: HEIGHT --}}
                <div class="ct-line-chart" style="height:350px;"></div>
            </div>
        </div>
    </div>
</div>

{{-- ================= TABEL & WIDGET BAWAAN ================= --}}
{{-- <div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">All Exam Result</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table student-data-table m-t-20">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Grade Point</th>
                                <th>Percent Form</th>
                                <th>Percent Upto</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Class Test</td>
                                <td>Mathmatics</td>
                                <td>4.00</td>
                                <td>95.00</td>
                                <td>20/04/2017</td>
                            </tr>
                            <tr>
                                <td>Class Test</td>
                                <td>English</td>
                                <td>4.00</td>
                                <td>95.00</td>
                                <td>20/04/2017</td>
                            </tr>
                            <tr>
                                <td>Class Test</td>
                                <td>Bangla</td>
                                <td>4.00</td>
                                <td>95.00</td>
                                <td>20/04/2017</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div> --}}
@endsection

{{-- ================= SCRIPT ================= --}}
@section('script')
<script>
    console.log('Dashboard chart script loaded');

    const labels = {!! $chartLabels !!};
    const series = {!! $chartSeries !!};

    console.log('Labels:', labels);
    console.log('Series:', series);

    if (!document.querySelector('.ct-line-chart')) {
        console.error('Elemen chart tidak ditemukan');
    }

    new Chartist.Line('.ct-line-chart', {
        labels: labels,
        series: [series]
    }, {
        low: 0,
        showArea: true,
        fullWidth: true,
        axisY: {
            onlyInteger: true
        },
        chartPadding: {
            top: 20,
            right: 30,
            bottom: 30,
            left: 20
        }
    });
</script>
@endsection


