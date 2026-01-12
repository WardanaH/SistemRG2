@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">Monitor Permintaan Bantuan (Keluar)</h2>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th>Tgl Request</th>
                            <th>Nota</th>
                            <th>Tujuan Cabang</th>
                            <th>Detail Item</th>
                            <th>Status Persetujuan</th>
                            <th>Status Pengerjaan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $d)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($d->tanggal)->format('d/m/Y') }}</td>
                            <td class="fw-bold">{{ $d->nomor_nota }}</td>
                            <td>{{ $d->cabangBantuan->nama }}</td>
                            <td>
                                <ul class="mb-0 ps-3 small">
                                    @foreach($d->subBantuan as $sub)
                                        <li>{{ $sub->produk->nama_produk ?? 'Item Hapus' }} ({{ $sub->banyak }})</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                @if($d->status_persetujuan_bantuan_transaksi == 'pending')
                                    <span class="badge bg-warning text-dark">Menunggu Konfirmasi</span>
                                @elseif($d->status_persetujuan_bantuan_transaksi == 'acc')
                                    <span class="badge bg-success">Disetujui (ACC)</span>
                                @else
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </td>
                            <td>
                                @if($d->status_bantuan_transaksi == 'selesai')
                                    <span class="badge bg-primary">SELESAI</span>
                                @else
                                    <span class="badge bg-secondary">Proses</span>
                                @endif
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
