@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Permintaan Bantuan Produksi</h2>
        <span class="badge bg-info p-2" style="color: black;">Cabang: {{ Auth::user()->cabang->nama }}</span>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Nota</th>
                        <th>Asal Cabang</th>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Status Persetujuan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($permintaan as $p)
                    <tr>
                        <td><strong>{{ $p->nomor_nota }}</strong></td>
                        <td><span class="badge bg-info text-dark">{{ $p->cabangAsal->nama }}</span></td>
                        <td>{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}</td>
                        <td>{{ $p->nama_pelanggan }}</td>
                        <td>
                            <span class="badge bg-warning text-dark">Pending</span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalAcc-{{ $p->id }}">
                                Lihat Detail & ACC
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@foreach($permintaan as $p)
    @include('admin.bantuan.modal_acc', ['transaksi' => $p])
@endforeach

@endsection
