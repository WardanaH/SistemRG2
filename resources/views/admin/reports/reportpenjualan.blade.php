@extends('layouts.app')

@section('content')
<div class="container my-5" style="max-width: 900px; background: white; padding: 30px; border: 1px solid #ccc;">
    <div class="text-center mb-4">
        <h3><strong>RESTU GURU PROMOSINDO</strong></h3>
        <p>Cabang: {{ $transaksi->cabang->nama ?? '-' }}</p>
        <p>Alamat: {{ $transaksi->cabang->alamat ?? '-' }} | Telp: {{ $transaksi->cabang->telepon ?? '-' }}</p>
        <hr>
        <h4>Nota Penjualan #{{ $transaksi->nomor_nota }}</h4>
        <small>Tanggal: {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d-m-Y') }}</small>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <strong>Dari:</strong> {{ $transaksi->user->nama ?? '-' }}<br>
            <small>{{ $transaksi->user->username ?? '' }}</small>
        </div>
        <div class="col-md-6 text-end">
            <strong>Kepada:</strong> {{ $transaksi->nama_pelanggan ?? '-' }}<br>
            <small>{{ $transaksi->hp_pelanggan ?? '-' }}</small>
        </div>
    </div>

    <table class="table table-bordered">
        <thead class="bg-light">
            <tr>
                <th>Produk</th>
                <th>Harga</th>
                <th>P</th>
                <th>L</th>
                <th>Kuantitas</th>
                <th>Finishing</th>
                <th>Keterangan</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($subtransaksis as $sub)
            <tr>
                <td>{{ $sub->produk->nama ?? '-' }}</td>
                <td>Rp {{ number_format($sub->harga_satuan, 0, ',', '.') }}</td>
                <td>{{ $sub->panjang ?: '-' }}</td>
                <td>{{ $sub->lebar ?: '-' }}</td>
                <td>{{ $sub->banyak }}</td>
                <td>{{ $sub->finishing }}</td>
                <td>{{ $sub->keterangan }}</td>
                <td>Rp {{ number_format($sub->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="row mt-4">
        <div class="col-md-6">
            <p><strong>Metode Pembayaran:</strong> {{ $transaksi->metode_pembayaran ?? '-' }}</p>
            <p><strong>Pajak:</strong> {{ $transaksi->pajak }}%</p>
            <p><strong>Diskon:</strong> {{ $transaksi->diskon }}%</p>
        </div>
        <div class="col-md-6 text-end">
            <p><strong>Total:</strong> Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</p>
            <p><strong>Bayar:</strong> Rp {{ number_format($transaksi->jumlah_pembayaran, 0, ',', '.') }}</p>
            <p><strong>Sisa:</strong> Rp {{ number_format($transaksi->sisa_tagihan, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="text-center mt-4">
        <p><em>"Harap cek kembali pesanan Anda sebelum meninggalkan tempat."</em></p>
    </div>
</div>
@endsection