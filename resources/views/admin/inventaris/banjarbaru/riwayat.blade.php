@extends('layouts.app')

@section('content')

<div class="page-header">
  <h3 class="page-title">Riwayat Pengiriman ke Cabang {{ ucfirst($cabangData->nama) }}</h3>
</div>

@if(session('success'))
  <div class="alert alert-success mt-2">{{ session('success') }}</div>
@endif
@if(session('error'))
  <div class="alert alert-danger mt-2">{{ session('error') }}</div>
@endif

<div class="card">
  <div class="card-body">

    @if($riwayat->isEmpty())
      <p>Tidak ada data pengiriman dari Gudang Pusat.</p>
    @else

      <div class="table-responsive">
        <table class="table table-bordered align-middle">

          <thead>
            <tr>
              <th>No</th>
              <th>Nama Barang</th>
              <th>Jumlah</th>
              <th>Tanggal Pengiriman</th>
              <th>Status Pengiriman</th>
              <th>Status Penerimaan</th>
              <th>Aksi</th>
            </tr>
          </thead>

          <tbody>
            @foreach($riwayat as $r)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $r->barang->nama_barang }}</td>
              <td>{{ $r->jumlah }}</td>
              <td>{{ \Carbon\Carbon::parse($r->tanggal_pengiriman)->format('d M Y') }}</td>
              <td>{{ ucfirst($r->status_pengiriman) }}</td>
              <td>{{ $r->status_penerimaan ?? '-' }}</td>

              <td>
                @if($r->status_pengiriman == 'Dikirim' && $r->status_penerimaan == null)

                  <form action="{{ route('cabang.riwayat.terima', ['slug' => $cabangData->slug, 'id' => $r->id_pengiriman]) }}"
                        method="POST">

                    @csrf
                    @method('PUT')

                    <button type="submit" class="btn btn-success btn-sm">
                      Telah Diterima
                    </button>

                  </form>

                @else
                  <span class="text-muted">-</span>
                @endif
              </td>

            </tr>
            @endforeach
          </tbody>

        </table>
      </div>

    @endif

  </div>
</div>

@endsection
