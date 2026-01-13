@extends('operator.layout.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Status Pesanan</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">No SPK</th>
                                    <th class="text-center">Nama Produk</th>
                                    <th class="text-center">Keterangan</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Tanggal Pesanan</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($subTransaksiData as $subTransaksi)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $subTransaksi->no_spk }}</td>
                                    <td class="text-center">{{ $subTransaksi->keterangan }}</td>
                                    <td class="text-center">{{ $subTransaksi->produk->nama_produk }}</td>
                                    <td class="text-center">{{ $subTransaksi->status_sub_transaksi }}</td>
                                    <td class="text-center">{{ $subTransaksi->created_at->format('d-m-Y') }}</td>
                                    <td class="text-center">
                                        <button
                                            class="btn btn-primary btn-ubah-status"
                                            data-id="{{ $subTransaksi->id }}"
                                            data-no="{{ $subTransaksi->no_spk }}"
                                            data-produk="{{ $subTransaksi->produk->nama_produk }}"
                                            data-status="{{ $subTransaksi->status_sub_transaksi }}">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                    </td>
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

@push('scripts')
<script>
    $(document).on('click', '.btn-ubah-status', function() {

        const id = $(this).data('id');
        const noSpk = $(this).data('no');
        const produk = $(this).data('produk');
        const status = $(this).data('status');

        Swal.fire({
            title: 'Ubah Status Pesanan',
            html: `
                <p><strong>No SPK:</strong> ${noSpk}</p>
                <p><strong>Produk:</strong> ${produk}</p>
            `,
            input: 'select',
            inputOptions: {
                proses: 'Proses',
                selesai: 'Selesai'
            },
            inputValue: status,
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#dc3545',
            preConfirm: (value) => {
                if (!value) {
                    Swal.showValidationMessage('Status wajib dipilih');
                }
                return value;
            }
        }).then((result) => {

            if (!result.isConfirmed) return;

            // SUBMIT VIA FORM DINAMIS (PUT)
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/operator/sub-transaksi/${id}`;

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
