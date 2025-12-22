@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between">
            <h4 class="mb-0">Pelunasan Angsuran Penjualan</h4>
        </div>

        <div class="card-body">

            {{-- FILTER --}}
            <div class="row mb-3 g-2 align-items-end">

                <div class="col-md-2">
                    <input id="filter_nota" class="form-control" placeholder="No Nota">
                </div>

                <div class="col-md-2">
                    <input id="filter_nama" class="form-control" placeholder="Nama Pelanggan">
                </div>

                <div class="col-md-2">
                    <select id="filter_bayar" class="select2">
                        <option value="semua">Semua Pembayaran</option>
                        <option value="Cash">Cash</option>
                        <option value="Transfer">Transfer</option>
                    </select>
                </div>

                @if(Auth::user()->hasRole(['owner','direktur']))
                <div class="col-md-2">
                    <select id="filter_cabang" class="select2">
                        <option value="semua">Semua Cabang</option>
                        @foreach($cabangs as $c)
                        <option value="{{ $c->id }}">{{ $c->nama }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- tombol filter --}}
                <div class="col-md-2">
                    <button id="btnFilter" class="btn btn-success w-100">Filter</button>
                </div>

            </div>

            {{-- DATATABLE --}}
            <div style="overflow-x:auto;">
                <table class="table table-bordered" id="tabelAngsuran">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>No Nota</th>
                            <th>Nama</th>
                            <th>Pembayaran</th>
                            <th>Dihapus</th>
                            <th>Nominal</th>
                            <th>Alasan</th>
                            <th>Cabang</th>
                            <th>Pembuat <br>Nota Angsuran</th>
                        </tr>
                    </thead>
                </table>
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    loadTable();

    function loadTable() {
        $('#tabelAngsuran').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            searching: false,
            ajax: {
                url: "{{ route('angsuran.deleted.data') }}",
                data: function(d) {
                    d.nonota = $("#filter_nota").val();
                    d.nama = $("#filter_nama").val();
                    d.pembayaran = $("#filter_bayar").val();
                    d.cabang = $("#filter_cabang").val();
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    orderable: false
                },
                {
                    data: 'nomor_nota'
                },
                {
                    data: 'nama_pelanggan'
                },
                {
                    data: 'metode_pembayaran'
                },
                {
                    data: 'deleted_at'
                },
                {
                    data: 'nominal_angsuran',
                    render: data => formatRupiah(data)
                },
                {
                    data: 'reason_on_delete'
                },
                {
                    data: 'cabang'
                },
                {
                    data: 'dibuat_oleh'
                }
            ]
        });
    }

    $("#btnFilter").on('click', function(e) {
        e.preventDefault();
        loadTable();
    });

    // UTILITY
    function formatRupiah(value) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
    }

    // function formatRupiah(angka) {
    //     return "Rp " + angka.toLocaleString('id-ID');
    // }
</script>
<style>
    #filter_cabang {
        max-width: 100%;
    }
</style>

@endpush
