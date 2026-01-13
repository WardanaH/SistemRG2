@extends('layouts.app')

@push('styles')
<style>
    p,
    small {
        color: #333;
    }

    .dari,
    .kepada {
        color: #333;
    }

    .logorg {
        width: 50px;
        margin-bottom: 10px;
    }

    .status {
        position: absolute;
        left: 250px;
        bottom: 65px;
        width: 300px;
        opacity: 0.3;
        transform: rotate(-20deg);
    }
</style>
@endpush

@section('content')
<div class="container my-5"
    style="max-width: 900px; background: white; padding: 30px; border: 1px solid #ccc; position: relative;">

    {{-- HEADER --}}
    <div class="text-center mb-4">
        <img src="{{asset('/images/rg.png')}}" class="logorg">
        <h3 style="color: #333;"><strong>RESTU GURU PROMOSINDO</strong></h3>
        <p style="color: #555;">Cabang: {{ $transaksi->cabang->nama ?? '-' ?? '-' }}</p>
        <p style="color: #555;">Alamat: {{ $transaksi->cabang->alamat ?? '-' }} | Telp: {{ $transaksi->cabang->telepon ?? '-' }}</p>
        <hr>
        <h4 style="color: #333;">Nota Angsuran Detail.Penjualan #{{ $transaksi->nomor_nota }}</h4>
        <small style="color: #333;">Tanggal: {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d-m-Y') }}</small>
    </div>

    {{-- INFO --}}
    <div class="row mb-3">
        <div class="dari col-md-4 text-left">
            <strong style="color: #333;">Dari : {{ $transaksi->user->nama ?? '-' }}</strong><br>
            <small style="color: #777;">{{ $transaksi->user->username ?? '' }}</small>
        </div>
        <div class="kepada col-md-4 text-center">
            <strong style="color: #333;">Kepada: {{ $transaksi->nama_pelanggan ?? '-' }}</strong><br>
            <small style="color: #777;">{{ $transaksi->hp_pelanggan ?? '-' }}</small>
        </div>

        <div class="col-md-4 text-right">
            <strong><strong style="color: #333;">No. Pelunasan: #{{ $angsuran->id }}
        </div>
    </div>

    {{-- TABLE ANGSURAN (SATU DATA) --}}
    <table class="table table-bordered">
        <thead class="bg-light">
            <tr>
                <th>Tanggal</th>
                <th>Nominal</th>
                <th>Sisa Tagihan</th>
                <th>Metode Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ \Carbon\Carbon::parse($angsuran->tanggal_angsuran)->format('d-m-Y') }}</td>
                <td>Rp {{ number_format($angsuran->nominal_angsuran,0,',','.') }}</td>
                <td>Rp {{ number_format($angsuran->sisa_angsuran,0,',','.') }}</td>
                <td>{{ $angsuran->metode_pembayaran }}</td>
            </tr>
        </tbody>
    </table>

    {{-- RINGKASAN --}}
    <div class="row mt-4">
        <div class="col-md-6">
            <p>
                <strong style="color: #333;">
                    Total: Rp {{ number_format($transaksi->total_harga,0,',','.') }}
                </strong>
            </p>
            <p>
                <strong style="color: #333;">
                    Terbayar: Rp {{ number_format($transaksi->jumlah_pembayaran,0,',','.') }}
                </strong>
            </p>
        </div>

        <div class="col-md-6">
            <p>
                <strong style="color: #333;">
                    Sisa Tagihan: Rp {{ number_format($transaksi->sisa_tagihan,0,',','.') }}
                </strong>
            </p>
            <p>
                <strong style="color: #333;">
                    Metode: {{ $angsuran->metode_pembayaran }}
                </strong>
            </p>
        </div>
    </div>


    {{-- STATUS --}}
    @if ($transaksi->sisa_tagihan == 0)
    <div class="text-center mt-4">
        <img style="
        position: absolute;
        /* top: 270px; */
        left: 250px;
        bottom: 65px;
        width: 300px;
        height: 70px;
        z-index: 2;
        opacity: 0.3;
        transform: rotate(340deg);
        " src="{{asset('/images/brush_lunas.png')}}" class="status">
    </div>
    @endif

    <div class="text-center mt-4">
        <em>"Harap cek kembali angsuran Anda."</em>
    </div>

</div>
@endsection
