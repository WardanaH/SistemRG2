@extends('layouts.app')
@section('content')

<div class="container-fluid mt-3">
    <div class="row">
        {{-- ================== FILTER PENCARIAN ================== --}}
        <div class="col-md-3">
            <form method="GET" action="{{ route('transaksiindex') }}">
                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fa fa-filter"></i> Filter Transaksi</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">No. Nota</label>
                            <input type="text" name="no" class="form-control" value="{{ request('no') }}" placeholder="Cari Nomor Nota">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Cabang</label>
                            <select name="cabang" class="select2">
                                <option value="">Semua Cabang</option>
                                @foreach ($cabangs as $c)
                                <option value="{{ $c->id }}" {{ request('cabang') == $c->id ? 'selected' : '' }}>
                                    {{ $c->nama }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="fa fa-search"></i> Filter
                        </button>
                        <a href="{{ route('transaksiindex') }}" class="btn btn-secondary btn-sm">Reset</a>
                    </div>
                </div>
            </form>
        </div>

        {{-- ================== DAFTAR TRANSAKSI ================== --}}
        <div class="col-md-9">
            <div class="card shadow">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fa fa-shopping-cart"></i> Transaksi Penjualan</h5>
                    <a href="{{ route('addtransaksiindex') }}" class="btn btn-light btn-sm">
                        <i class="fa fa-plus"></i> Tambah
                    </a>
                </div>

                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle">
                            <thead class="table-success">
                                <tr class="text-center">
                                    <th>No. Nota</th>
                                    <th>Nama</th>
                                    <th>Telp.</th>
                                    <th>Tanggal</th>
                                    <th>DP</th>
                                    <th>Pembayaran</th>
                                    <th>Diskon</th>
                                    <th>Pajak</th>
                                    <th>Sisa Tagihan</th>
                                    <th>Total</th>
                                    <th>Cabang</th>
                                    <th>Pembuat</th>
                                    <th>Desainer</th>
                                    <th>Tool</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($datas as $data)
                                <tr>
                                    <td>
                                        {{ $data->nomor_nota }}
                                        @if ($data->subTransaksi()->onlyTrashed()->count() > 0)
                                        <span class="badge bg-success ms-2">edited</span>
                                        @endif
                                    </td>
                                    <td>{{ $data->nama_pelanggan }}</td>
                                    <td>{{ $data->hp_pelanggan }}</td>
                                    <td>{{ \Carbon\Carbon::parse($data->created_at)->format('d-m-Y H:m:i') }}</td>
                                    <td>Rp {{ number_format($data->jumlah_pembayaran, 2, ',', '.') }}</td>
                                    <td>{{ $data->metode_pembayaran }}</td>
                                    <td>{{ $data->diskon }}%</td>
                                    <td>{{ $data->pajak }}%</td>
                                    <td class="{{ $data->sisa_tagihan != 0 ? 'bg-warning' : 'bg-light' }}">
                                        Rp {{ number_format($data->sisa_tagihan, 2, ',', '.') }}
                                    </td>
                                    <td class="bg-light">
                                        Rp {{ number_format($data->total_harga, 2, ',', '.') }}
                                    </td>
                                    <td>{{ $data->cabang->nama ?? '-' }}</td>
                                    <td>{{ $data->user->nama ?? '-' }}</td>
                                    <td>{{ $data->designer->nama ?? '-'}}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-primary btn-detail"
                                                data-id="{{ encrypt($data->id) }}"
                                                data-total="Rp {{ number_format($data->total_harga, 2, ',', '.') }}">
                                                <i class="fa fa-eye"></i>
                                            </button>

                                            <button type="button"
                                                class="btn btn-warning btn-angsuran"
                                                data-id="{{ encrypt($data->id) }}"
                                                data-sisa="{{ $data->sisa_tagihan }}"
                                                data-nonota="{{ $data->nomor_nota }}"
                                                data-pembayaran="{{ $data->jumlah_pembayaran }}">
                                                <i class="fa fa-money"></i>
                                            </button>

                                            <a href="" class="btn btn-success">
                                                <i class="fa fa-edit"></i>
                                            </a>

                                            <button type="button" class="btn btn-danger btn-delete" data-id="{{ $data->id }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>

                                        {{-- form delete disembunyikan --}}
                                        <form id="delete-form-{{ $data->id }}"
                                            action="{{ route('destroytransaksipenjualan', $data->id) }}"
                                            method="POST"
                                            style="display:none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="13" class="text-center text-muted">Belum ada transaksi</td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="table-secondary">
                                <tr>
                                    <td colspan="8" class="text-center fw-bold">Total</td>
                                    <td>Rp {{ number_format($datas->sum('sisa_tagihan'), 2, ',', '.') }}</td>
                                    <td>Rp {{ number_format($datas->sum('total_harga'), 2, ',', '.') }}</td>
                                    <td colspan="5"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="card-footer text-center">
                    {{ $datas->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('click', async function(e) {

        const btn = e.target.closest('.btn-detail');
        if (!btn) return;

        const id = btn.dataset.id;
        const total = btn.dataset.total;
        const row = btn.closest('tr');
        const table = row.closest('table');
        const colCount = row.children.length;

        // Hapus baris detail jika sudah terbuka
        if (row.nextElementSibling && row.nextElementSibling.classList.contains('detail-row')) {
            row.nextElementSibling.remove();
            return;
        }

        // Hapus detail lain yang terbuka
        document.querySelectorAll('.detail-row').forEach(r => r.remove());

        try {
            const res = await fetch(`{{ route('showsubtransaksi') }}?id=${id}`);
            const data = await res.json();

            const current = data.current || [];
            const deleted = data.deleted || [];

            let html = `
            <tr class="detail-row">
                <td colspan="${colCount}" class="p-0">
                    <table class="table table-sm mb-0 table-bordered bg-light">
                        <thead class="table-success text-center">
                            <tr><th colspan="9">Produk Dibeli</th></tr>
                            <tr>
                                <th>Nama Barang</th>
                                <th>Harga Satuan</th>
                                <th>P</th>
                                <th>L</th>
                                <th>Kuantitas</th>
                                <th>Finishing</th>
                                <th>Keterangan</th>
                                <th>Diskon (%)</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>`;

            if (current.length === 0) {
                html += `<tr><td colspan="9" class="text-center text-muted">Tidak ada data produk</td></tr>`;
            } else {
                current.forEach(item => {
                    html += `
                        <tr>
                            <td>${item.produk?.nama_produk || '-'}</td>
                            <td>Rp ${parseFloat(item.harga_satuan).toLocaleString('id-ID')}</td>
                            <td>${item.panjang}</td>
                            <td>${item.lebar}</td>
                            <td>${item.banyak}</td>
                            <td>${item.finishing || '-'}</td>
                            <td>${item.keterangan || '-'}</td>
                            <td>${item.diskon}%</td>
                            <td>Rp ${parseFloat(item.subtotal).toLocaleString('id-ID')}</td>
                        </tr>`;
                });
            }

            html += `
                        <tr>
                            <th colspan="8" class="text-end">Total:</th>
                            <th class="text-end">${total}</th>
                        </tr>
                    </tbody>
                    `;

            // Jika ada data deleted
            if (deleted.length > 0) {
                html += `
                            <thead class="table-danger text-center">
                                <tr><th colspan="9">Produk Sebelumnya (Terhapus)</th></tr>
                            </thead>
                            <tbody>
                        `;

                deleted.forEach(item => {
                    html += `
                            <tr>
                                <td>${item.nama_produk || '-'}</td>
                                <td>Rp ${parseFloat(item.harga_satuan).toLocaleString('id-ID')}</td>
                                <td>${item.panjang}</td>
                                <td>${item.lebar}</td>
                                <td>${item.banyak}</td>
                                <td>${item.finishing || '-'}</td>
                                <td>${item.keterangan || '-'}</td>
                                <td>${item.diskon}%</td>
                                <td>Rp ${parseFloat(item.subtotal).toLocaleString('id-ID')}</td>
                            </tr>`;
                });

                html += `</tbody>`;
            }

            html += `</table></td></tr>`;

            row.insertAdjacentHTML('afterend', html);

        } catch (err) {
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Gagal memuat detail transaksi.'
            });
        }


    });
</script>

<script>
    $(document).on('click', '.btn-angsuran', function() {

        let id = $(this).data('id'); // encrypt ID sama seperti lama
        let row = $(this).closest('tr');
        let colCount = row.find('td').length;

        // jika sudah terbuka → tutup
        if (row.next().hasClass('detail_click angsuran')) {
            $(".detail_click").remove();
            return;
        }

        $(".detail_click").remove();

        $.ajax({
            url: "{{ route('angsuran.showdetail.transaksi') }}", // route baru milik kamu
            type: "GET",
            data: {
                id: id
            },
            success: function(res) {

                if (!res.success) {
                    alert("Gagal mengambil data");
                    return;
                }

                let transaksi = res.detail;
                let angsuran = res.angsuran;

                // Tambahkan row detail angsuran
                row.after(`
                <tr class="detail_click angsuran">
                    <td colspan="${colCount}" style="padding:0;background:#fcfcfc">
                        <table class="table table-hover" style="margin:0;background:#fcfcfc">
                            <thead>
                                <tr style="background:#00a65a;color:white">
                                    <th colspan="8" class="text-center">Angsuran</th>
                                </tr>
                            </thead>

                            <thead>
                                <th>No Nota Angsuran</th>
                                <th>Tanggal</th>
                                <th>Nominal</th>
                                <th>Pembayaran</th>
                                <th>Cabang</th>
                                <th>User</th>
                                <th style="text-align:right">Tools</th>
                            </thead>

                            <tbody id="tbody-angsuran"></tbody>

                            <tfoot>
                                <tr>
                                    <th colspan="6" class="text-end">Sisa Tagihan:</th>
                                    <th class="text-end">Rp ${Number(transaksi.sisa_tagihan).toLocaleString('id-ID')}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </td>
                </tr>
            `);

                // Loop angsuran
                angsuran.forEach(v => {
                    $("#tbody-angsuran").append(`
                    <tr>
                        <td>#${v.nomor_nota}</td>
                        <td>${v.tanggal_angsuran}</td>
                        <td>Rp ${Number(v.nominal_angsuran).toLocaleString('id-ID')}</td>
                        <td>${v.metode_pembayaran}</td>
                        <td>${transaksi.cabang?.nama ?? '-'}</td>
                        <td>${transaksi.user?.username ?? '-'}</td>

                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-danger btn-delete-angsuran"
                                    data-id="${v.id}"
                                    data-nominal="${v.nominal_angsuran}">
                                    <i class="fa fa-trash"></i>
                                </button>

                                <button class="btn btn-success btn-print-angsuran"
                                    data-id="${v.id}">
                                    <i class="fa fa-print"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `);
                });

            },
            error: function(xhr) {
                console.error(xhr.responseText);
                alert("Terjadi kesalahan saat mengambil angsuran.");
            }
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;

                Swal.fire({
                    title: 'Hapus Transaksi?',
                    html: `
                        <p>Berikan alasan penghapusan transaksi ini:</p>
                        <textarea id="reasonInput" class="swal2-textarea" placeholder="Contoh: Data duplikat, salah input, dll"></textarea>
                    `,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Hapus Sekarang',
                    cancelButtonText: 'Batal',
                    preConfirm: () => {
                        const reason = document.getElementById('reasonInput').value.trim();
                        if (!reason) {
                            Swal.showValidationMessage('Alasan wajib diisi!');
                            return false;
                        }
                        return reason;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const reason = result.value;

                        // cari form delete
                        const form = document.getElementById('delete-form-' + id);

                        // tambahkan input hidden ke form
                        let input = form.querySelector('input[name="reason_on_delete"]');
                        if (!input) {
                            input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'reason_on_delete';
                            form.appendChild(input);
                        }
                        input.value = reason;

                        form.submit();
                    }
                });
            });
        });
    });
</script>


@endpush

@endsection
