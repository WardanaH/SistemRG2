@extends('layouts.app')
@section('content')

<div class="container-fluid mt-3">
    <div class="row">
        {{-- ================== FILTER PENCARIAN ================== --}}
        <div class="col-md-3">
            <form method="GET" action="{{ route('bantuan.list') }}">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fa fa-filter"></i> Filter Bantuan</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">No. Nota</label>
                            <input type="text" name="no" class="form-control" value="{{ request('no') }}" placeholder="Cari Nota Bantuan">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Cabang Tujuan</label>
                            <select name="cabang" class="select2 form-select">
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
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fa fa-search"></i> Filter
                        </button>
                        <a href="{{ route('bantuan.list') }}" class="btn btn-secondary btn-sm">Reset</a>
                    </div>
                </div>
            </form>
        </div>

        {{-- ================== DAFTAR TRANSAKSI BANTUAN ================== --}}
        <div class="col-md-9">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fa fa-handshake-o"></i> Daftar Bantuan Produksi</h5>
                    <a href="{{ route('bantuan') }}" class="btn btn-light btn-sm text-primary fw-bold">
                        <i class="fa fa-plus"></i> Buat Permintaan Baru
                    </a>
                </div>

                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle">
                            <thead class="table-primary text-center">
                                <tr>
                                    <th>No. Nota</th>
                                    <th>Dari Cabang</th>
                                    <th>Ke Cabang</th>
                                    <th>Tanggal</th>
                                    <th>Pelanggan</th>
                                    <th>Total Tagihan</th>
                                    <th>Status Transaksi</th>
                                    <th>Status Bantuan</th>
                                    <th>Status Produksi</th>
                                    <th>Tool</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($datas as $data)
                                <tr>
                                    <td>
                                        <strong>{{ $data->nomor_nota }}</strong>
                                        @if ($data->subBantuan()->onlyTrashed()->count() > 0)
                                            <span class="badge bg-danger ms-1" style="font-size: 0.6rem;">edited</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($data->cabang_id == Auth::user()->cabang_id)
                                            <span class="badge bg-success">Saya ({{ $data->cabangAsal->nama }})</span>
                                        @else
                                            {{ $data->cabangAsal->nama }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($data->bantuan_cabang_id == Auth::user()->cabang_id)
                                            <span class="badge bg-info text-dark">Saya ({{ $data->cabangBantuan->nama }})</span>
                                        @else
                                            {{ $data->cabangBantuan->nama }}
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($data->created_at)->format('d/m/Y') }}</td>
                                    <td>
                                        {{ $data->nama_pelanggan }} <br>
                                        <small class="text-muted">{{ $data->hp_pelanggan }}</small>
                                    </td>
                                    <td class="text-end fw-bold">
                                        Rp {{ number_format($data->total_harga, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        @if($data->status_transaksi == 'proses')
                                            <span class="badge bg-warning text-dark">proses</span>
                                        @elseif($data->status_transaksi == 'selesai')
                                            <span class="badge bg-success">selesai</span>
                                        @else
                                            <span class="badge bg-danger">Cancel</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($data->status_persetujuan_bantuan_transaksi == 'pending')
                                            <span class="badge bg-warning text-dark">Menunggu</span>
                                        @elseif($data->status_persetujuan_bantuan_transaksi == 'acc')
                                            <span class="badge bg-success">Disetujui</span>
                                        @else
                                            <span class="badge bg-danger">Ditolak</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($data->status_bantuan_transaksi == 'selesai')
                                            <span class="badge bg-primary">SELESAI</span>
                                        @else
                                            <span class="badge bg-secondary">Proses</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-info text-white btn-detail"
                                                data-id="{{ encrypt($data->id) }}">
                                                <i class="fa fa-eye"></i>
                                            </button>

                                            <a href="{{ route('bantuan.cetak_nota', $data->id) }}" class="btn btn-dark" target="_blank" title="Cetak Nota Internal">
                                                <i class="fa fa-print"></i>
                                            </a>

                                            @if($data->status_persetujuan_bantuan_transaksi == 'pending' && $data->cabang_id == Auth::user()->cabang_id)
                                            <button type="button" class="btn btn-danger btn-delete" data-id="{{ $data->id }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                            <form id="delete-form-{{ $data->id }}"
                                                action="{{ route('bantuan.destroy', $data->id) }}"
                                                method="POST" style="display:none;">
                                                @csrf @method('DELETE')
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">Belum ada data bantuan transaksi.</td>
                                </tr>
                                @endforelse
                            </tbody>
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
    // ================= SCRIPT DETAIL ROW (AJAX) =================
    document.addEventListener('click', async function(e) {
        const btn = e.target.closest('.btn-detail');
        if (!btn) return;

        const id = btn.dataset.id;
        const row = btn.closest('tr');
        const colCount = row.children.length;

        // Toggle tutup jika diklik lagi
        if (row.nextElementSibling && row.nextElementSibling.classList.contains('detail-row')) {
            row.nextElementSibling.remove();
            return;
        }

        // Tutup detail lain
        document.querySelectorAll('.detail-row').forEach(r => r.remove());

        try {
            // Panggil route AJAX khusus Bantuan
            const res = await fetch(`{{ route('bantuan.detail_ajax') }}?id=${id}`);
            const data = await res.json();
            const current = data.current || [];

            let html = `
            <tr class="detail-row">
                <td colspan="${colCount}" class="p-0">
                    <div class="p-3 bg-light border-bottom">
                        <h6 class="fw-bold text-primary mb-2">Rincian Item Bantuan:</h6>
                        <table class="table table-sm table-bordered bg-white mb-0">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>Produk</th>
                                    <th>Ukuran</th>
                                    <th>Qty</th>
                                    <th>Finishing</th>
                                    <th>No SPK (Internal)</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">`;

            if (current.length === 0) {
                html += `<tr><td colspan="6" class="text-muted">Tidak ada item</td></tr>`;
            } else {
                current.forEach(item => {
                    let ukuran = item.panjang > 0 ? `${item.panjang}x${item.lebar} cm` : '-';
                    html += `
                        <tr>
                            <td>${item.produk?.nama_produk || '-'}</td>
                            <td>${ukuran}</td>
                            <td>${item.banyak} ${item.satuan}</td>
                            <td>${item.finishing || '-'}</td>
                            <td class="fw-bold">${item.no_spk || '<span class="text-muted text-italic">(Belum diisi)</span>'}</td>
                            <td><span class="badge bg-secondary">${item.status_sub_transaksi}</span></td>
                        </tr>`;
                });
            }

            html += `       </tbody>
                        </table>
                    </div>
                </td>
            </tr>`;

            row.insertAdjacentHTML('afterend', html);

        } catch (err) {
            console.error(err);
            alert('Gagal memuat detail bantuan.');
        }
    });

    // ================= SCRIPT DELETE =================
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                if(confirm('Yakin ingin menghapus permintaan bantuan ini?')) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        });
    });
</script>
@endpush

@endsection
