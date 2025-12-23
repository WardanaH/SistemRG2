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

        .designer{
            color: #333;
        }
    </style>
@endpush

@section('content')
<div class="container my-5" style="max-width: 900px; background: white; padding: 30px; border: 1px solid #ccc;">
    <div class="text-center mb-4">
        <h3 style="color: #333;"><strong>RESTU GURU PROMOSINDO</strong></h3>
        <p style="color: #555;">Cabang: {{ $transaksi->cabang->nama ?? '-' }}</p>
        <p style="color: #555;">Alamat: {{ $transaksi->cabang->alamat ?? '-' }} | Telp: {{ $transaksi->cabang->telepon ?? '-' }}</p>
        <hr>
        <h4 style="color: #333;">Nota Penjualan #{{ $transaksi->nomor_nota }}</h4>
        <small style="color: #333;">Tanggal: {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d-m-Y') }}</small>
    </div>

    <div class="row mb-3">
        <div class="dari col-md-4 text-left">
            <strong style="color: #333;">Dari : {{ $transaksi->user->nama ?? '-' }}</strong><br>
            <small style="color: #777;">{{ $transaksi->user->username ?? '' }}</small>
        </div>
        <div class="kepada col-md-4 text-center">
            <strong style="color: #333;">Kepada: {{ $transaksi->nama_pelanggan ?? '-' }}</strong><br>
            <small style="color: #777;">{{ $transaksi->hp_pelanggan ?? '-' }}</small>
        </div>
        <div class="designer col-md-4 text-right">
            <strong style="color: #333;">Designer: {{ $transaksi->designer->nama ?? '-' }}</strong><br>
        </div>
    </div>

    <table class="table table-bordered">
        <thead class="bg-light" style="color: #333;">
            <tr>
                <th>Produk</th>
                <th>Harga</th>
                <th>P</th>
                <th>L</th>
                <th>Kuantitas</th>
                <th>Finishing</th>
                <th>Keterangan</th>
                <th>Subtotal</th>
                <th>No SPK</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($subtransaksis as $sub)
            <tr>
                <td>{{ $sub->produk->nama_produk ?? '-' }}</td>
                <td>Rp {{ number_format($sub->harga_satuan, 0, ',', '.') }}</td>
                <td>{{ $sub->panjang ?: '-' }}</td>
                <td>{{ $sub->lebar ?: '-' }}</td>
                <td>{{ $sub->banyak }}</td>
                <td>{{ $sub->finishing }}</td>
                <td>{{ $sub->keterangan }}</td>
                <td>Rp {{ number_format($sub->subtotal, 0, ',', '.') }}</td>
                <td>{{ $sub->no_spk ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="row mt-4">
        <div class="col-md-2"></div>
        <div class="col-md-6">
            <p><strong style="color: #333;">Metode Pembayaran: {{ $transaksi->metode_pembayaran ?? '-' }}</strong></p>
            <p><strong style="color: #333;">Pajak: {{ $transaksi->pajak }}%</strong></p>
            <p><strong style="color: #333;">Diskon: {{ $transaksi->diskon }}%</strong></p>
        </div>
        <div class="col-md-4">
            <p><strong style="color: #333;">Total: Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</strong></p>
            <p><strong style="color: #333;">Bayar: Rp {{ number_format($transaksi->jumlah_pembayaran, 0, ',', '.') }}</strong></p>
            <p><strong style="color: #333;">Sisa: Rp {{ number_format($transaksi->sisa_tagihan, 0, ',', '.') }}</strong></p>
        </div>
    </div>

    <div class="text-center mt-4">
        <p style="color: #555;"><em>"Harap cek kembali pesanan Anda sebelum meninggalkan tempat.</em></p>
    </div>
</div>
@endsection

