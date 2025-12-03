@extends('operator.layout.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Riwayat Pesanan</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No SPK</th>
                                    <th>Produk</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($subTransaksiData as $subTransaksi)
                                <tr>
                                    <td>{{ $subTransaksi->no_spk }}</td>
                                    <td>{{ $subTransaksi->produk->nama_produk }}</td>
                                    <td>{{ $subTransaksi->status_sub_transaksi }}</td>
                                    <td>{{ $subTransaksi->created_at->format('d-m-Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
