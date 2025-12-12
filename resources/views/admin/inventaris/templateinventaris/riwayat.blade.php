@extends('layouts.app')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center">
  <h3 class="page-title">
    📦 Riwayat Pengiriman ke {{ ucfirst($cabang->nama) }}
  </h3>
</div>

<div class="card mt-3">
  <div class="card-body">

    <h4 class="card-title">Daftar Riwayat Pengiriman</h4>

    @if($riwayat->isEmpty())
      <p class="text-muted mt-3">Tidak ada data pengiriman dari Gudang Pusat.</p>
    @else

    <div class="table-responsive mt-3">
      <table class="table table-striped table-bordered align-middle styletable">

        <thead class="table-light">
          <tr>
            <th width="5%">No</th>
            <th>Nama Barang</th>
            <th width="10%">Jumlah</th>
            <th width="10%">Satuan</th> {{-- ✅ KOLOM BARU --}}
            <th width="15%">Tanggal Kirim</th>
            <th width="15%">Status Pengiriman</th>
            <th width="15%">Tanggal Diterima</th>
            <th width="10%">Aksi</th>
          </tr>
        </thead>

        <tbody>
        @foreach($riwayat as $r)
          <tr>
            <td>{{ $loop->iteration }}</td>

            {{-- ✅ NAMA BARANG --}}
            <td>{{ $r->bahanbaku->nama_bahan ?? '-' }}</td>

            {{-- ✅ JUMLAH --}}
            <td>{{ $r->jumlah }}</td>

            {{-- ✅ SATUAN (DIPISAH) --}}
            <td>{{ $r->satuan }}</td>

            {{-- ✅ TANGGAL KIRIM --}}
            <td>
              {{ \Carbon\Carbon::parse($r->tanggal_pengiriman)->format('d M Y') }}
            </td>

            {{-- ✅ STATUS --}}
            <td>
              @if($r->status_pengiriman == 'Dikemas')
                <span class="badge bg-secondary">Dikemas</span>
              @elseif($r->status_pengiriman == 'Dikirim')
                <span class="badge bg-warning text-dark">Dikirim</span>
              @else
                <span class="badge bg-success">Diterima</span>
              @endif
            </td>

            {{-- ✅ TANGGAL DITERIMA --}}
            <td>
              {{ $r->tanggal_diterima
                  ? \Carbon\Carbon::parse($r->tanggal_diterima)->format('d M Y')
                  : '-' }}
            </td>

            {{-- ✅ AKSI TERIMA BARANG --}}
            <td class="text-center">
              @if($r->status_pengiriman == 'Dikirim')

                <form action="{{ route('cabang.riwayat.terima', [$cabang->slug, $r->id]) }}"
                      method="POST"
                      class="form-terima d-inline">
                  @csrf
                  @method('PUT')

                  <button type="button" class="btn btn-success btn-sm btn-terima">
                    Terima
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


@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// ✅ KONFIRMASI SEBELUM TERIMA BARANG
$(document).on('click', '.btn-terima', function(e) {
    e.preventDefault();

    let form = $(this).closest('form');

    Swal.fire({
        title: 'Terima Barang?',
        text: 'Pastikan barang sudah benar-benar diterima!',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Terima',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
});

// ✅ SWEETALERT BERHASIL
@if(session('success'))
Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
    text: "{{ session('success') }}",
    timer: 2000,
    showConfirmButton: false
});
@endif

// ✅ SWEETALERT ERROR
@if(session('error'))
Swal.fire({
    icon: 'error',
    title: 'Gagal!',
    text: "{{ session('error') }}",
});
@endif
</script>

@endpush
