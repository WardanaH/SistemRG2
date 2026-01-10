@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-success">Riwayat Membantu Cabang Lain</h2>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead class="table-success">
                        <tr>
                            <th>Tgl Transaksi</th>
                            <th>Nota Bantuan</th>
                            <th>Asal Cabang (Peminta)</th>
                            <th>Total Tagihan</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $d)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($d->tanggal)->format('d M Y') }}</td>
                            <td class="fw-bold">{{ $d->nomor_nota }}</td>
                            <td>{{ $d->cabangAsal->nama }}</td>
                            <td>Rp {{ number_format($d->total_harga, 0, ',', '.') }}</td>
                            <td>
                                @if($d->status_bantuan_transaksi == 'selesai')
                                    <span class="badge bg-success">Selesai Produksi</span>
                                @else
                                    <span class="badge bg-warning text-dark">Sedang Dikerjakan</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('bantuan.cetak_nota', $d->id) }}" target="_blank" class="btn btn-sm btn-outline-dark">
                                    <i class="fa fa-print"></i> Cetak Nota Internal
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
