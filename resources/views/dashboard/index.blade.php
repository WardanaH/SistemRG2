@extends('layouts.app')

@section('title', 'Dashboard Analistik')

@push('styles')
<link href="{{ asset('vendor/chartist/css/chartist.min.css') }}" rel="stylesheet">
{{-- Tambahkan CSS Tooltip --}}
<style>
    .chartist-tooltip {
        position: absolute;
        display: inline-block;
        opacity: 0;
        min-width: 50px;
        padding: 5px 10px;
        background: rgba(0, 0, 0, 0.7);
        color: #fff;
        font-family: sans-serif;
        font-weight: 700;
        text-align: center;
        border-radius: 4px;
        pointer-events: none;
        z-index: 1000;
        transition: opacity .2s linear;
    }

    .chartist-tooltip.tooltip-show {
        opacity: 1;
    }

    .chartist-tooltip:before {
        content: "";
        position: absolute;
        top: 100%;
        left: 50%;
        width: 0;
        height: 0;
        margin-left: -5px;
        border: 5px solid transparent;
        border-top-color: rgba(0, 0, 0, 0.7);
    }

    /* Agar area hover lebih luas dan mudah kena mouse */
    .ct-point {
        stroke-width: 10px;
        cursor: pointer;
    }
</style>

<link href="{{ asset('vendor/chartist/css/chartist.min.css') }}" rel="stylesheet">
<style>
    /* Mengatur warna chart agar lebih kontras */
    .ct-series-a .ct-line,
    .ct-series-a .ct-point {
        stroke: #4d7cff;
    }

    /* Pemasukan */
    .ct-series-b .ct-line,
    .ct-series-b .ct-point {
        stroke: #2ecc71;
    }

    /* Pendapatan Bersih */
    .ct-series-c .ct-line,
    .ct-series-c .ct-point {
        stroke: #f1556c;
    }

    /* Pengeluaran */
    .ct-series-d .ct-line,
    .ct-series-d .ct-point {
        stroke: #ff9800;
    }

    /* Total Transaksi */

    .chart-legend {
        list-style: none;
        padding: 0;
        display: flex;
        gap: 15px;
        justify-content: center;
    }

    .chart-legend li {
        display: flex;
        align-items: center;
        font-size: 12px;
    }

    .legend-box {
        width: 12px;
        height: 12px;
        border-radius: 3px;
        margin-right: 5px;
    }
</style>
@endpush

@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>Selamat Datang Kembali</h4>
            <p class="mb-0">{{ Auth::user()->nama ?? 'Nama Pengguna' }}</p>
        </div>
    </div>
</div>

{{-- Widget Baris Atas --}}
<div class="row">
    @php
    $stats = [
    ['Total Transaksi', $totalTransaksi, 'ti-receipt', 'primary', ''],
    ['Total Pemasukan', $totalPemasukan, 'ti-money', 'success', 'Rp '],
    ['Total Pengeluaran', '-', 'ti-arrow-down', 'danger', 'Rp '],
    ['Pendapatan Bersih', $pendapatanBersih, 'ti-stats-up', 'info', 'Rp ']
    ];
    @endphp

    @foreach($stats as $stat)
    <div class="col-lg-3 col-sm-6">
        <div class="card">
            <div class="stat-widget-one card-body">
                <div class="stat-icon d-inline-block">
                    <i class="{{ $stat[2] }} text-{{ $stat[3] }} border-{{ $stat[3] }}"></i>
                </div>
                <div class="stat-content d-inline-block">
                    <div class="stat-text">{{ $stat[0] }}</div>
                    <div class="stat-digit">
                        {{ $stat[4] }}{{ is_numeric($stat[1]) ? number_format($stat[1], 0, ',', '.') : $stat[1] }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="row">
    {{-- Line Chart Performa --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header border-0 pb-0">
                <h4 class="card-title">Tren Performa (12 Hari Terakhir)</h4>
            </div>
            <div class="card-body">
                <ul class="chart-legend mb-3">
                    <li><span class="legend-box" style="background: #4d7cff;"></span> Pemasukan</li>
                    <li><span class="legend-box" style="background: #2ecc71;"></span> Laba</li>
                    <li><span class="legend-box" style="background: #f1556c;"></span> Transaksi</li>
                    <!-- <li><span class="legend-box" style="background: #ff9800;"></span> Transaksi</li> -->
                </ul>
                <div id="line-chart-performa" style="height: 350px;"></div>
            </div>
        </div>
    </div>

    {{-- Pie Chart Terlaris --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header border-0 pb-0">
                <h4 class="card-title">Top 5 Produk (1 Tahun)</h4>
            </div>
            <div class="card-body">
                <div id="pie-chart-terlaris" style="height: 300px;"></div>
                <div class="mt-4">
                    @foreach($pieLabels as $index => $label)
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">{{ $label }}</span>
                        <span class="font-weight-bold small">{{ $pieSeries[$index] }} Qty</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('vendor/chartist/js/chartist.min.js') }}"></script>
<script src="{{ asset('vendor/chartist-plugin-tooltips/js/chartist-plugin-tooltip.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // LINE CHART
        new Chartist.Line('#line-chart-performa', {
            labels: @json($labels),
            series: [{
                    name: 'Pemasukan',
                    data: @json($pemasukanSeries)
                },
                {
                    name: 'Laba',
                    data: @json($bersihSeries)
                },
                {
                    name: 'Transaksi',
                    data: @json($transaksiSeries)
                }
            ]
        }, {
            fullWidth: true,
            chartPadding: {
                right: 40,
                left: 20 // Tambahkan padding kiri sedikit
            },
            axisY: {
                onlyInteger: true,
                offset: 80, // PERBAIKAN: Tambah jarak agar angka nominal tidak terpotong
                labelInterpolationFnc: function(value) {
                    // Opsional: Jika angka terlalu panjang (jutaan), bisa disingkat jadi 1M, 2M dll
                    if (value >= 1000000) return (value / 1000000) + 'M';
                    if (value >= 1000) return (value / 1000) + 'k';
                    return value;
                }
            },
            showArea: false,
            plugins: [
                Chartist.plugins.tooltip({
                    class: 'chartist-tooltip',
                    appendToBody: true,
                    transformTooltipTextFnc: function(value) {
                        return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    }
                })
            ]
        });

        // PIE CHART
        var pieData = {
            labels: @json($pieLabels),
            series: @json($pieSeries)
        };

        new Chartist.Pie('#pie-chart-terlaris', pieData, {
            donut: true,
            donutWidth: 40,
            showLabel: true,
            labelInterpolationFnc: function(value, idx) {
                // Menampilkan persentase atau angka di pie jika cukup luas
                return pieData.series[idx];
            }
        });
    });
</script>
@endpush
