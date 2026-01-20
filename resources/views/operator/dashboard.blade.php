@extends('operator.layout.app')

@section('content')

<style>
    .operator-dashboard-wrapper {
        min-height: calc(100vh - 120px); /* supaya footer TETAP terlihat */
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        padding-top: 20px;
    }

    .stat-card {
        border-radius: 16px;
        transition: .2s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }

    .stat-number {
        font-size: 40px;
        font-weight: 700;
        margin: 10px 0 20px;
    }
</style>

<div class="container-fluid operator-dashboard-wrapper">

    <h3 class="fw-bold mb-1">Dashboard Operator</h3>
    <p class="text-muted mb-4">Ringkasan tugas Anda.</p>
    <p>Selamat datang, {{ Auth::user()->name }}</p>
    <p>Anda login sebagai operator di cabang {{ Auth::user()->cabang->nama }}</p>
    <p>Anda login sebagai {{ Auth::user()->roles->pluck('name')->first() }}</p>

    <div class="row g-4">

        {{-- TUGAS SELESAI --}}
        <div class="col-xl-3 col-lg-4 col-md-6">
            <a href="{{ route('operator.pesanan') }}" class="text-decoration-none text-dark">
                <div class="card stat-card shadow-sm border-0 p-3">

                    <h6 class="text-success fw-semibold">Tugas Selesai</h6>

                    <div class="stat-number text-success">{{ $selesai }}</div>

                    <span class="badge bg-success px-3 py-2">
                        Lihat Status
                    </span>

                </div>
            </a>
        </div>

        {{-- TUGAS BELUM SELESAI --}}
        <div class="col-xl-3 col-lg-4 col-md-6">
            <a href="{{ route('operator.riwayat') }}" class="text-decoration-none text-dark">
                <div class="card stat-card shadow-sm border-0 p-3">

                    <h6 class="text-danger fw-semibold">Tugas Belum Selesai</h6>

                    <div class="stat-number text-danger">{{ $belum_selesai }}</div>

                    <span class="badge bg-danger px-3 py-2">
                        Lihat Riwayat
                    </span>

                </div>
            </a>
        </div>

    </div>

</div>

@endsection
