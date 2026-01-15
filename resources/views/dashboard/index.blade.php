@extends('layouts.app')

@section('title', 'Dashboard')
@push('styles')
    {{-- CSS vendor dashboard --}}
    <link href="{{ asset('vendor/chartist/css/chartist.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/pg-calendar/css/pignose.calendar.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/owl-carousel/css/owl.carousel.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4>Hi, welcome back!</h4>
            <p class="mb-0">Your business dashboard template</p>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Layout</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Blank</a></li>
        </ol>
    </div>
</div>

{{-- Statistik atas --}}
<div class="row">
    {{-- Total Transaksi --}}
    <div class="col-lg-3 col-sm-6">
        <div class="card">
            <div class="stat-widget-one card-body">
                <div class="stat-icon d-inline-block">
                    <i class="ti-receipt text-primary border-primary"></i>
                </div>
                <div class="stat-content d-inline-block">
                    <div class="stat-text">Total Transaksi</div>
                    <div class="stat-digit">{{ $totalTransaksi }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Total Pemasukan --}}
    <div class="col-lg-3 col-sm-6">
        <div class="card">
            <div class="stat-widget-one card-body">
                <div class="stat-icon d-inline-block">
                    <i class="ti-money text-success border-success"></i>
                </div>
                <div class="stat-content d-inline-block">
                    <div class="stat-text">Total Pemasukan</div>
                    <div class="stat-digit">
                        Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Total Pengeluaran --}}
    <div class="col-lg-3 col-sm-6">
        <div class="card">
            <div class="stat-widget-one card-body">
                <div class="stat-icon d-inline-block">
                    <i class="ti-arrow-down text-danger border-danger"></i>
                </div>
                <div class="stat-content d-inline-block">
                    <div class="stat-text">Total Pengeluaran</div>
                    <div class="stat-digit">
                        Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Pendapatan Bersih --}}
    <div class="col-lg-3 col-sm-6">
        <div class="card">
            <div class="stat-widget-one card-body">
                <div class="stat-icon d-inline-block">
                    <i class="ti-stats-up text-info border-info"></i>
                </div>
                <div class="stat-content d-inline-block">
                    <div class="stat-text">Pendapatan Bersih</div>
                    <div class="stat-digit">
                        Rp {{ number_format($pendapatanBersih ?? 0, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Chart bar & pie --}}
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Fee Collections and Expenses</h4>
            </div>
            <div class="card-body">
                <div class="ct-bar-chart mt-5"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <div class="ct-pie-chart"></div>
            </div>
        </div>
    </div>
</div>

{{-- Table & Timeline --}}
<div class="row">
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
                                <td>100</td>
                                <td>20/04/2017</td>
                            </tr>
                            <tr>
                                <td>Class Test</td>
                                <td>English</td>
                                <td>4.00</td>
                                <td>95.00</td>
                                <td>100</td>
                                <td>20/04/2017</td>
                            </tr>
                            <tr>
                                <td>Class Test</td>
                                <td>Bangla</td>
                                <td>4.00</td>
                                <td>95.00</td>
                                <td>100</td>
                                <td>20/04/2017</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Timeline --}}
    <div class="col-lg-6 col-xl-4 col-xxl-6 col-md-6">
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
    </div>

    {{-- Notice Board --}}
    <div class="col-xl-4 col-lg-6 col-xxl-6 col-md-6">
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
    </div>

    {{-- Todo List --}}
    <div class="col-xl-4 col-xxl-6 col-lg-6 col-md-12 col-sm-12">
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
    </div>
</div>

{{-- Calendar & Expense --}}
<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <div class="year-calendar"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">All Expense</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table student-data-table m-t-20">
                        <thead>
                            <tr>
                                <th>Expense Type</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Email</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Salary</td>
                                <td>$2000</td>
                                <td><span class="badge badge-primary">Paid</span></td>
                                <td>edumin@gmail.com</td>
                                <td>10/05/2017</td>
                            </tr>
                            <tr>
                                <td>Salary</td>
                                <td>$2000</td>
                                <td><span class="badge badge-warning">Pending</span></td>
                                <td>edumin@gmail.com</td>
                                <td>10/05/2017</td>
                            </tr>
                        </tbody>
                    </table>
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
<script src="{{ asset('vendor/pg-calendar/js/pignose.calendar.min.js') }}"></script>

<script src="{{ asset('vendor/owl-carousel/js/owl.carousel.min.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ================= BAR CHART ================= */
    new Chartist.Bar('.ct-bar-chart', {
        labels: @json($labels),
        series: [
            @json($pemasukanMingguan),
            @json($bersihMingguan),
            @json($pengeluaranMingguan)
        ]
    }, {
        height: '320px',
        seriesBarDistance: 12,
        axisY: {
            onlyInteger: true,
            offset: 60
        },
        plugins: [
            Chartist.plugins.tooltip()
        ]
    });
    /* ================= PIE CHART (DUMMY TEMPLATE) ================= */
    new Chartist.Pie('.ct-pie-chart', {
        labels: ['facebook', 'twitter', 'youtube', 'google plus'],
        series: [20, 10, 30, 40]
    }, {
        height: '300px',
        chartPadding: 30,
        labelOffset: 40,
        labelDirection: 'explode'
    });

});
</script>

{{-- <script>
document.addEventListener('DOMContentLoaded', function () {
    setTimeout(function () {
        var script = document.createElement('script');
        script.src = "{{ asset('js/dashboard/dashboard-2.js') }}";
        script.defer = true;
        document.body.appendChild(script);
    }, 150);
});
</script> --}}
@endpush

