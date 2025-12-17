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
                <table class="table table-bordered table-striped styletable" id="tabelAngsuran">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>No Nota</th>
                            <th>Nama</th>
                            <th>Pembayaran</th>
                            <th>Tanggal</th>
                            <th>Sisa Tagihan</th>
                            <th>Total</th>
                            <th>Cabang</th>
                            <th>Dibuat Oleh</th>
                            <th style="width:160px">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>

        </div>
    </div>
</div>

{{-- ===================== MODAL BAYAR ===================== --}}
<div class="modal fade" id="modalBayar">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Bayar Angsuran</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <p>
                    Nomor Nota:
                    <strong id="nomorNotaText">RG-{{ now()->timestamp }}</strong>
                    <input type="hidden" id="nomorNotaInput">
                </p>

                <p>
                    Sisa Tagihan:
                    <strong id="sisaTagihanText" data-raw="0">Rp 0</strong>
                </p>

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

{{-- ===================== MODAL DELETE ===================== --}}
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
                searching: false,
                ajax: {
                    url: "{{ route('angsuran.data') }}",
                    data: {
                        nonota: $("#filter_nota").val(),
                        nama: $("#filter_nama").val(),
                        pembayaran: $("#filter_bayar").val(),
                        cabang: $("#filter_cabang").val(),
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
                        data: 'tanggal'
                    },
                    {
                        data: 'sisa_tagihan',
                        render: data => formatRupiah(data)
                    },
                    {
                        data: 'total_harga',
                        render: data => formatRupiah(data)
                    },
                    {
                        data: 'cabang.nama'
                    },
                    {
                        data: 'user.username'
                    },
                    {
                        data: 'aksi',
                        orderable: false
                    },
                ]
            });
        }

        $("#btnFilter").click(loadTable);

        // ================= DETAIL =================
        document.addEventListener('click', async function(e) {
            const btn = e.target.closest('.btn-detail');
            if (!btn) return;

            const id = btn.dataset.id;
            const row = btn.closest('tr');
            const colCount = row.children.length;

            // Jika sudah terbuka → tutup
            if (row.nextElementSibling && row.nextElementSibling.classList.contains('detail-row')) {
                row.nextElementSibling.remove();
                return;
            }

            // Tutup detail row lain
            document.querySelectorAll('.detail-row').forEach(r => r.remove());

            try {
                const res = await fetch(`{{ route('angsuran.showdetail') }}?id=${id}`);
                const data = await res.json();

                const transaksi = data.detail;
                const angsurans = data.angsuran;

                let html = `
                    <tr class="detail-row">
                        <td colspan="${colCount}" class="p-0">
                            <table class="table table-sm mb-0 table-bordered bg-light">

                                <!-- PRODUK DIBELI -->
                                <thead class="table-success text-center">
                                    <tr><th colspan="6">Produk Dibeli</th></tr>
                                    <tr>
                                        <th>Produk</th>
                                        <th>P</th>
                                        <th>L</th>
                                        <th>Qty</th>
                                        <th>Harga</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                `;

                // Produk
                transaksi.sub_transaksi.forEach(p => {
                    html += `
                        <tr>
                            <td>${p.produk?.nama_produk ?? '-'}</td>
                            <td>${p.panjang}</td>
                            <td>${p.lebar}</td>
                            <td>${p.banyak}</td>
                            <td>Rp ${parseFloat(p.harga_satuan).toLocaleString('id-ID')}</td>
                            <td>Rp ${parseFloat(p.subtotal).toLocaleString('id-ID')}</td>
                        </tr>
                    `;
                });

                html += `
                        </tbody>

                        <!-- RIWAYAT ANGSURAN -->
                        <thead class="table-primary text-center">
                            <tr><th colspan="6">Riwayat Pembayaran Angsuran</th></tr>
                            <tr>
                                <th>Nomor Nota Angsuran</th>
                                <th>Tanggal</th>
                                <th>Metode</th>
                                <th>Nominal</th>
                                <th>Pembuat Nota Angsuran</th>
                                <th>aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                if (angsurans.length === 0) {
                    html += `<tr><td colspan="6" class="text-center text-muted">Belum ada pembayaran</td></tr>`;
                } else {
                    angsurans.forEach(a => {
                        // console.log(a);
                        html += `
                            <tr>
                                <td>${a.nomor_nota}</td>
                                <td>${a.tanggal_angsuran}</td>
                                // <td>${a.metode_pembayaran}</td>
                                <td>Rp ${parseFloat(a.nominal_angsuran).toLocaleString('id-ID')}</td>
                                <td>${a.user?.name ?? a.user?.username ?? '-'}</td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-danger btn-delete-angsuran"
                                            data-id="${a.id}"
                                            data-nominal="${a.nominal_angsuran}">
                                            <i class="fa fa-trash"></i>
                                        </button>

                                        <button class="btn btn-warning btn-update-angsuran"
                                            data-id="${a.id}"
                                            data-nominal="${a.nominal_angsuran}">
                                            <i class="fa fa-edit"></i>
                                        </button>

                                        <button class="btn btn-success btn-print-pdf"
                                            data-id="${a.id}"
                                            data-nominal="${a.nominal_angsuran}">
                                            <i class="fa fa-print"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        `;
                    });
                }

                html += `
                        </tbody>
                        </table>
                    </td>
                </tr>
                `;

                row.insertAdjacentHTML('afterend', html);

            } catch (err) {
                console.error(err);
                alert("Gagal memuat detail angsuran!");
            }
        });


        // ================= BAYAR =================
        // 1. BUKA MODAL BAYAR
        $(document).on('click', '.bayarBtn', function() {

            let id = $(this).data('id');
            let rawSisa = $(this).data('sisa');

            // Generate nomor nota baru
            let nomorNota = "RG-" + Math.floor(Date.now() / 1000);
            $("#nomorNotaText").text(nomorNota);
            $("#nomorNotaInput").val(nomorNota);

            if (typeof rawSisa === "string") {
                rawSisa = rawSisa.replace(/[^\d.-]/g, "");
            }

            let sisa = parseFloat(rawSisa) || 0;

            $("#bayarID").val(id);

            $("#sisaTagihanText")
                .text(formatRupiah(sisa))
                .data('raw', sisa);

            $("#nominalBayar").val("");

            $("#modalBayar").modal('show');
        });

        // 2. SIMPAN PEMBAYARAN ANGSURAN
        $("#btnSimpanBayar").click(function() {

            let id = $("#bayarID").val();
            let nominal = parseFloat($("#nominalBayar").val()) || 0;
            let metode = $("#metodeBayar").val();
            let sisa = parseFloat($("#sisaTagihanText").data('raw')) || 0;

            if (nominal <= 0) {
                Swal.fire("Error!", "Nominal tidak boleh kosong!", "error");
                return;
            }

            if (nominal > sisa) {
                Swal.fire("Error!", "Nominal lebih besar dari sisa tagihan!", "error");
                return;
            }

            $.ajax({
                method: "POST",
                url: `/angsuran-penjualan/bayar/${id}`,
                data: {
                    nominal: nominal,
                    metode: metode,
                    nomor_nota: $("#nomorNotaInput").val(),
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    if (res.msg === "success") {
                        Swal.fire("Berhasil!", "Pembayaran angsuran berhasil!", "success");
                        $("#modalBayar").modal('hide');
                        $('#tabelAngsuran').DataTable().ajax.reload();
                    } else {
                        Swal.fire("Error!", "Gagal menyimpan angsuran!", "error");
                    }
                },
                error: function() {
                    Swal.fire("Error!", "Terjadi kesalahan server!", "error");
                }
            });
        });

        // ================= DELETE =================
        $(document).on('click', '.btn-delete-angsuran', function() {
            $("#deleteID").val($(this).data('id'));
            $("#modalDelete").modal('show');
        });

        $("#btnDelete").click(function() {
            const id = $("#deleteID").val();
            const alasan = $("#deleteReason").val();

            console.log('[DELETE] ID:', id);
            console.log('[DELETE] Alasan:', alasan);

            $.ajax({
                url: `/angsuran-penjualan/hapus/${id}`,
                type: 'DELETE',
                data: {
                    alasan: alasan,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    console.log('[DELETE] SUCCESS:', res);
                    $("#modalDelete").modal('hide');
                    $('#tabelAngsuran').DataTable().ajax.reload(null, false);
                    Swal.fire("Berhasil", "Angsuran berhasil dihapus!", "success");
                },
                error: function(xhr) {
                    console.error('[DELETE] ERROR STATUS:', xhr.status);
                    console.error('[DELETE] RESPONSE:', xhr.responseText);

                    Swal.fire("Error", xhr.responseJSON?.message || "Gagal menghapus angsuran!", "error");
                }
            });
        });

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
