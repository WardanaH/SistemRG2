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
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($subTransaksiData as $subTransaksi)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $subTransaksi->no_spk }}</td>
                                    <td class="text-center">{{ $subTransaksi->produk->nama_produk }}</td>
                                    <td class="text-center">{{ $subTransaksi->status_sub_transaksi }}</td>
                                    <td class="text-center">
                                        <button
                                            class="btn btn-primary btn-detail"
                                            data-id="{{ $subTransaksi->id }}"
                                            data-no="{{ $subTransaksi->no_spk }}"
                                            data-produk="{{ $subTransaksi->produk->nama_produk }}"
                                            data-status="{{ $subTransaksi->status_sub_transaksi }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#detailModal">
                                            Detail
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

<!-- Modal Detail -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Detail Sub Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="updateStatusForm" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-body">
                    <p><strong>No SPK:</strong> <span id="modalNoSpk"></span></p>
                    <p><strong>Produk:</strong> <span id="modalProduk"></span></p>

                    <div class="mt-3">
                        <label>Status Pesanan</label>
                        <select class="form-control" name="status_sub_transaksi" id="modalStatus">
                            <option value="proses">Proses</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button class="btn btn-success" type="submit">Simpan Perubahan</button>
                </div>

            </form>

        </div>
    </div>
</div>


@endsection

@push('scripts')
<script>
    $(document).on('click', '.btn-detail', function() {

        let id = $(this).data('id');
        let no = $(this).data('no');
        let produk = $(this).data('produk');
        let status = $(this).data('status');

        // Isi data ke modal
        $('#modalNoSpk').text(no);
        $('#modalProduk').text(produk);
        $('#modalStatus').val(status);

        // Update action form
        $('#updateStatusForm').attr('action', '/operator/sub-transaksi/' + id);

    });
</script>

@endpush
