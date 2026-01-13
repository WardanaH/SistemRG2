@extends('operator.layout.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card border-primary">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title mb-0"><i class="fa fa-handshake-o"></i> Daftar Bantuan Produksi (Masuk)</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Asal Cabang</th> <th class="text-center">No SPK</th>
                                    <th class="text-center">Nama Produk</th>
                                    <th class="text-center">Ukuran / Qty</th>
                                    <th class="text-center">Finishing</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Aksi</th>
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
                                    <td class="text-center fw-bold">{{ $sub->no_spk ?? '(Belum Ada)' }}</td>
                                    <td class="text-center">{{ $sub->produk->nama_produk }}</td>
                                    <td class="text-center">
                                        @if($sub->panjang > 0)
                                            {{ $sub->panjang }}x{{ $sub->lebar }} cm ({{ $sub->banyak }} pcs)
                                        @else
                                            {{ $sub->banyak }} {{ $sub->satuan }}
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $sub->finishing }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-warning text-dark">{{ ucfirst($sub->status_sub_transaksi) }}</span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-success btn-ubah-status"
                                            data-id="{{ $sub->id }}"
                                            data-no="{{ $sub->no_spk }}"
                                            data-produk="{{ $sub->produk->nama_produk }}"
                                            data-status="{{ $sub->status_sub_transaksi }}">
                                            <i class="fa fa-check"></i> Update
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">Tidak ada permintaan bantuan produksi saat ini.</td>
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
    $(document).on('click', '.btn-ubah-status', function() {
        const id = $(this).data('id');
        const noSpk = $(this).data('no');
        const produk = $(this).data('produk');
        const status = $(this).data('status');

        Swal.fire({
            title: 'Update Status Produksi',
            html: `
                <p><strong>SPK:</strong> ${noSpk}</p>
                <p><strong>Item:</strong> ${produk}</p>
                <hr>
                <p class="text-muted small">Barang ini adalah titipan produksi dari cabang lain.</p>
            `,
            input: 'select',
            inputOptions: {
                proses: 'Sedang Proses',
                selesai: 'Selesai (Siap Kirim)'
            },
            inputValue: status,
            showCancelButton: true,
            confirmButtonText: 'Simpan Perubahan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            preConfirm: (value) => {
                if (!value) {
                    Swal.showValidationMessage('Status wajib dipilih');
                }
                return value;
            }
        }).then((result) => {
            if (!result.isConfirmed) return;

            // Buat form hidden untuk submit PUT request
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/operator/update-bantuan/${id}`; // URL Route Baru

            form.innerHTML = `
                @csrf
                @method('PUT')
                <input type="hidden" name="status_sub_transaksi" value="${result.value}">
            `;

            document.body.appendChild(form);
            form.submit();
        });
    });
</script>
@endpush
