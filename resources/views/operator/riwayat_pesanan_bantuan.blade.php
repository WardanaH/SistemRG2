@extends('operator.layout.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h4 class="card-title mb-0"><i class="fa fa-history"></i> Riwayat Bantuan Produksi (Selesai)</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="tableRiwayat">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Asal Cabang</th>
                                    <th class="text-center">No SPK</th>
                                    <th class="text-center">Nama Produk</th>
                                    <th class="text-center">Ukuran / Qty</th>
                                    <th class="text-center">Finishing</th>
                                    <th class="text-center">Tgl Selesai</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($subTransaksiData as $sub)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-info text-dark">
                                            {{ $sub->transaksiUtama->cabangAsal->nama ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="text-center fw-bold">{{ $sub->no_spk ?? '-' }}</td>
                                    <td class="text-center">
                                        {{ $sub->produk->nama_produk }}
                                        <br>
                                        <small class="text-muted">{{ $sub->keterangan }}</small>
                                    </td>
                                    <td class="text-center">
                                        @if($sub->panjang > 0)
                                            {{ $sub->panjang }}x{{ $sub->lebar }} cm
                                            <br>
                                            <strong>({{ $sub->banyak }} pcs)</strong>
                                        @else
                                            <strong>{{ $sub->banyak }} {{ $sub->satuan }}</strong>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $sub->finishing }}</td>
                                    <td class="text-center">
                                        {{ $sub->updated_at->format('d M Y') }}
                                        <br>
                                        <small>{{ $sub->updated_at->format('H:i') }}</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success"><i class="fa fa-check-circle"></i> Selesai</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        Belum ada riwayat bantuan produksi yang selesai.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Jika Anda menggunakan DataTables, bisa diaktifkan di sini
    $(document).ready(function() {
        // $('#tableRiwayat').DataTable();
    });
</script>
@endpush
