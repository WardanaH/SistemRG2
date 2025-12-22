@extends('layouts.app')

@push('styles')
<style>
        p{
            color: #333;
        }

        small{
            color: #333;
        }

        .dari{
            color: #333;
        }

        .kepada{
            color: #333;
        }
</style>
@endpush

@section('content')
<div class="container my-5" style="max-width: 900px; background: white; padding: 30px; border: 1px solid #ccc;">
    <div class="text-center mb-4">
        <h3 style="color: #333;"><strong>RESTU GURU PROMOSINDO</strong></h3>
        <p style="color: #555;">Cabang: {{ $transaksi->cabang->nama ?? '-' ?? '-' }}</p>
        <p style="color: #555;">Alamat: {{ $transaksi->cabang->alamat ?? '-' }} | Telp: {{ $transaksi->cabang->telepon ?? '-' }}</p>
        <hr>
        <h4 style="color: #333;">Nota Angsuran Penjualan No. Nota: #{{ $transaksi->nomor_nota }}</h4>
        <small style="color: #333;">Tanggal: {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d-m-Y') }}</small>
    </div>

    <div class="row mb-3">
        <div class="col-md-4">
            <strong style="color: #333;">Dari:</strong><br>
            {{ $transaksi->user->username ?? '-' }}<br>
            <small style="color: #777;">{{ $transaksi->display_name ?? '' }}</small>
        </div>

        <div class="col-md-4 text-center">
            <strong style="color: #333;">Kepada:</strong><br>
            {{ $transaksi->nama_pelanggan ?? '-' }}<br>
            <small style="color: #777;">{{ $transaksi->hp_pelanggan ?? '-' }}</small>
        </div>

        <div class="col-md-4 text-right">
            <strong style="color: #333;">Metode Pembayaran:</strong><br>
            {{ $transaksi->metode_pembayaran ?? '-' }}
        </div>
    </div>

    <table class="table table-bordered">
        <thead class="bg-light">
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nominal</th>
                <th>Sisa Angsuran</th>
                <th>Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($angsurans as $index => $angsuran)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($angsuran->tanggal_angsuran)->format('d-m-Y') }}</td>
                <td>Rp {{ number_format($angsuran->nominal_angsuran, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($angsuran->sisa_angsuran, 0, ',', '.') }}</td>
                <td>{{ $angsuran->metode_pembayaran }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="row mt-4">
        <div class="col-md-6">
            <p><strong>Pajak:</strong> {{ $transaksi->pajak }}%</p>
            <p><strong>Diskon:</strong> {{ $transaksi->diskon }}%</p>
        </div>
        <div class="col-md-6 text-right">
            <p><strong>Total:</strong> Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</p>
            <p><strong>Terbayar:</strong> Rp {{ number_format($transaksi->jumlah_pembayaran, 0, ',', '.') }}</p>
            <p><strong>Sisa Tagihan:</strong> Rp {{ number_format($transaksi->sisa_tagihan, 0, ',', '.') }}</p>
        </div>
    </div>

    @if ($transaksi->sisa_tagihan == 0)
        <div class="text-center mt-4">
            <h5 style="color: green;"><strong>LUNAS</strong></h5>
        </div>
    @endif

    <div class="text-center mt-4">
        <p><em>"Harap cek kembali angsuran Anda."</em></p>
    </div>
</div>
@endsection
