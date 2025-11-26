@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Pelunasan Angsuran Penjualan</h4>
        </div>

        <div class="card-body">

            {{-- Filter --}}
            <div class="row mb-3">
                <div class="col-md-3">
                    <input id="filter_nota" class="form-control" placeholder="No Nota">
                </div>
                <div class="col-md-3">
                    <input id="filter_nama" class="form-control" placeholder="Nama Pelanggan">
                </div>
                <div class="col-md-3">
                    <select id="filter_bayar" class="form-select">
                        <option value="semua">Semua Pembayaran</option>
                        <option value="Cash">Cash</option>
                        <option value="Transfer">Transfer</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button id="btnFilter" class="btn btn-success w-100">Filter</button>
                </div>
            </div>

            {{-- DATA TABLE --}}
            <table class="table table-bordered table-striped" id="tabelAngsuran">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>No Nota</th>
                        <th>Nama</th>
                        <th>Tanggal</th>
                        <th>Sisa Tagihan</th>
                        <th>Total</th>
                        <th>Pembayaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>

        </div>
    </div>
</div>

<div class="modal fade" id="modalDetail">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Detail Transaksi</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Qty</th>
                            <th>Harga</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody id="detailBody"></tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="modalBayar">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Bayar Angsuran</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <p>Sisa Tagihan: <strong id="sisaTagihanText"></strong></p>

                <input type="hidden" id="bayarID">

                <div class="form-group mb-2">
                    <label>Nominal</label>
                    <input type="number" class="form-control" id="nominalBayar">
                </div>

                <div class="form-group">
                    <label>Metode</label>
                    <select id="metodeBayar" class="form-control">
                        <option value="Cash">Cash</option>
                        <option value="Transfer">Transfer</option>
                    </select>
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-success" id="btnSimpanBayar">Simpan</button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="modalDelete">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Hapus Angsuran</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="deleteID">

                <div class="form-group">
                    <label>Alasan</label>
                    <textarea id="deleteReason" class="form-control" rows="3"></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-danger" id="btnDelete">Hapus</button>
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        loadTable();

        function loadTable() {
            $('#tabelAngsuran').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                ajax: {
                    url: "{{ route('angsuran.data') }}",
                    data: {
                        nonota: $('#filter_nota').val(),
                        nama: $('#filter_nama').val(),
                        pembayaran: $('#filter_bayar').val(),
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false
                    },
                    {
                        data: 'id'
                    },
                    {
                        data: 'nama_pelanggan'
                    },
                    {
                        data: 'tanggal'
                    },
                    {
                        data: 'sisa_tagihan'
                    },
                    {
                        data: 'total_harga'
                    },
                    {
                        data: 'metode_pembayaran'
                    },
                    {
                        data: 'aksi',
                        orderable: false
                    }
                ]
            });
        }

        $('#btnFilter').click(function() {
            loadTable();
        });

        // open modal bayar
        $(document).on('click', '.bayarBtn', function() {
            let id = $(this).data('id');
            // buka modal (akan aku buatkan setelah ini)
        });
    });

    $(document).on('click', '.detailBtn', function() {
        const id = $(this).data('id');

        $.get(`/angsuran-penjualan/detail/${id}`, function(res) {
            let rows = '';
            res.data.details.forEach(d => {
                rows += `
                <tr>
                    <td>${d.produk.nama}</td>
                    <td>${d.qty}</td>
                    <td>${formatRupiah(d.harga)}</td>
                    <td>${formatRupiah(d.subtotal)}</td>
                </tr>
            `;
            });

            $("#detailBody").html(rows);
            $("#modalDetail").modal('show');
        });
    });

    $(document).on('click', '.bayarBtn', function() {
        let id = $(this).data('id');
        let sisa = $(this).data('sisa');

        $("#bayarID").val(id);
        $("#sisaTagihanText").text(formatRupiah(sisa));
        $("#nominalBayar").val('');
        $("#modalBayar").modal('show');
    });

    $("#btnSimpanBayar").click(function() {
        let id = $("#bayarID").val();

        $.post(`/angsuran-penjualan/bayar/${id}`, {
                nominal: $("#nominalBayar").val(),
                metode: $("#metodeBayar").val(),
                _token: $('meta[name="csrf-token"]').attr('content')
            })
            .done(() => {
                $("#modalBayar").modal('hide');
                $('#tabelAngsuran').DataTable().ajax.reload();

                Swal.fire("Berhasil", "Pembayaran berhasil!", "success");
            })
            .fail(err => {
                Swal.fire("Error", err.responseJSON.message, "error");
            });
    });

    $(document).on('click', '.deleteBtn', function() {
        $("#deleteID").val($(this).data('id'));
        $("#modalDelete").modal('show');
    });

    $("#btnDelete").click(function() {
        let id = $("#deleteID").val();

        $.post(`/angsuran-penjualan/hapus/${id}`, {
                alasan: $("#deleteReason").val(),
                _token: $('meta[name="csrf-token"]').attr('content')
            })
            .done(() => {
                $("#modalDelete").modal('hide');
                $('#tabelAngsuran').DataTable().ajax.reload();

                Swal.fire("Berhasil", "Angsuran berhasil dihapus!", "success");
            })
            .fail(() => {
                Swal.fire("Error", "Gagal menghapus!", "error");
            });
    });

    function formatRupiah(value) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
    }
</script>
@endpush
