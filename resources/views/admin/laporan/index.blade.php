@extends('layouts.app')

@section('title', 'Laporan Keuangan Penjualan')

@push('styles')
<link href="{{ asset('vendor/chartist/css/chartist.min.css') }}" rel="stylesheet">
<style>
    /* Warna Grafik: Hijau untuk Pemasukan, Biru untuk Piutang */
    .ct-series-a .ct-line, .ct-series-a .ct-point { stroke: #2ecc71; }
    .ct-series-b .ct-line, .ct-series-b .ct-point { stroke: #4d7cff; }

    .stat-card { transition: all 0.3s; border: none; }
    .stat-card:hover { transform: translateY(-5px); }
    .badge-pills { border-radius: 50px; padding: 5px 12px; font-weight: 600; }

    .chartist-tooltip {
        background: rgba(0, 0, 0, 0.8);
        color: white;
        border-radius: 4px;
        padding: 5px 10px;
    }
</style>
@endpush

@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>Laporan Penjualan & Piutang</h4>
            <p class="mb-0">Periode: <span id="labelTanggal" class="font-weight-bold">{{ date('d-m-Y') }}</span></p>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end d-flex">
        <div style="width: 280px;">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-primary text-white"><i class="fa fa-calendar"></i></span>
                </div>
                <input type="text" class="form-control" name="periode" id="periode">
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card stat-card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="text-success mb-3"><i class="fa fa-shopping-cart mr-2"></i>Ringkasan Penjualan</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between small mb-1">
                                <span>Sudah Dibayar (Omzet Tunai)</span>
                                <span id="Pembayaran_Penjualan" class="font-weight-bold">Rp 0</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div id="Progress_Pembayaran_Penjualan" class="progress-bar bg-success" style="width: 0%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between small mb-1">
                                <span>Piutang (Belum Bayar)</span>
                                <span id="Piutang_Penjualan" class="font-weight-bold text-warning">Rp 0</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div id="Progress_Piutang_Penjualan" class="progress-bar bg-warning" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 text-right d-flex align-items-center justify-content-end">
                        <div>
                            <span class="font-weight-normal h4">Total Penjualan:</span><br>
                            <span id="Total_Penjualan" class="font-weight-bold h2 text-primary">Rp 0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-light border-0"><strong><i class="fa fa-money-bill-wave mr-2 text-warning"></i>Pencairan Piutang (Cash)</strong></div>
            <div class="card-body py-4">
                <div class="d-flex justify-content-between align-items-center">
                    <span>Total Masuk Tunai</span>
                    <h3 id="c_Kas_Masuk" class="text-success mb-0">Rp 0</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-light border-0"><strong><i class="fa fa-university mr-2 text-info"></i>Pencairan Piutang (Transfer)</strong></div>
            <div class="card-body py-4">
                <div class="d-flex justify-content-between align-items-center">
                    <span>Total Masuk Bank</span>
                    <h3 id="t_Kas_Masuk" class="text-primary mb-0">Rp 0</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header border-0 bg-white">
                <h4 class="card-title">Tren Penjualan & Piutang {{ date('Y') }}</h4>
            </div>
            <div class="card-body">
                <div id="salesChart" style="height: 400px;"></div>
                <div class="text-center mt-4 pb-2">
                    <span class="mx-3"><i class="fa fa-circle text-success small mr-1"></i> Pemasukan</span>
                    <span class="mx-3"><i class="fa fa-circle text-primary small mr-1"></i> Piutang</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('vendor/chartist/js/chartist.min.js') }}"></script>
<script src="{{ asset('vendor/chartist-plugin-tooltips/js/chartist-plugin-tooltip.min.js') }}"></script>
<script src="{{ asset('vendor/moment/moment.min.js') }}"></script>

<script>
    Number.prototype.format = function(n, x, s, c) {
        var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
            num = this.toFixed(Math.max(0, ~~n));
        return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
    };

    function setData(res) {
        let totalJual = parseFloat(res.Pembayaran_Penjualan) + parseFloat(res.Piutang_Penjualan);
        let perPenjualan = totalJual > 0 ? (res.Pembayaran_Penjualan / totalJual) * 100 : 0;
        let perPiutang = totalJual > 0 ? (res.Piutang_Penjualan / totalJual) * 100 : 0;

        $('#Pembayaran_Penjualan').text('Rp ' + res.Pembayaran_Penjualan.format(0, 3, '.', ','));
        $('#Piutang_Penjualan').text('Rp ' + res.Piutang_Penjualan.format(0, 3, '.', ','));
        $('#Total_Penjualan').text('Rp ' + totalJual.format(0, 3, '.', ','));
        $('#Progress_Pembayaran_Penjualan').css('width', perPenjualan + '%');
        $('#Progress_Piutang_Penjualan').css('width', perPiutang + '%');

        $('#c_Kas_Masuk').text('Rp ' + res.c_Pencairan_Piutang.format(0, 3, '.', ','));
        $('#t_Kas_Masuk').text('Rp ' + res.t_Pencairan_Piutang.format(0, 3, '.', ','));
    }

    function setChart(res) {
        $('#salesChart').empty();
        new Chartist.Line('#salesChart', {
            labels: res.datachartMonth,
            series: [
                { name: 'Pemasukan', data: res.datachartPemasukan },
                { name: 'Piutang', data: res.datachartPiutang }
            ]
        }, {
            fullWidth: true,
            axisY: {
                offset: 100,
                labelInterpolationFnc: function(value) {
                    if (value >= 1000000) return 'Rp ' + (value / 1000000).toFixed(1) + 'jt';
                    if (value >= 1000) return 'Rp ' + (value / 1000) + 'rb';
                    return 'Rp ' + value;
                }
            },
            chartPadding: { right: 40, left: 10 },
            plugins: [Chartist.plugins.tooltip()]
        });
    }

    function fetchLaporan(start, end) {
        $.ajax({
            url: "{{ route('filter.laporan') }}",
            type: "GET",
            data: { startDate: start, endDate: end },
            success: function(res) {
                setData(res);
                setChart(res);
            }
        });
    }

    $(document).ready(function() {
        const today = moment().format('YYYY-MM-DD');
        fetchLaporan(today, today);

        $('#periode').daterangepicker({
            startDate: moment(),
            endDate: moment(),
            locale: { format: 'DD/MM/YYYY' },
            drops: 'down',
            opens: 'left'
        }, function(start, end) {
            let s = start.format('YYYY-MM-DD');
            let e = end.format('YYYY-MM-DD');
            $('#labelTanggal').text(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
            fetchLaporan(s, e);
        });
    });
</script>
@endpush
