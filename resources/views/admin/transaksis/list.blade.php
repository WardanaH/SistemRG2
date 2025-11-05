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
                            <select name="cabang" class="form-select select2">
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
                                    <th>Tool</th>
                                    <th>Cabang</th>
                                    <th>Pembuat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($datas as $data)
                                <tr>
                                    <td>
                                        #{{ $data->nomor_nota }}
                                        @if ($data->subTransaksi()->onlyTrashed()->count() > 0)
                                        <span class="badge bg-success ms-2">edited</span>
                                        @endif
                                    </td>
                                    <td>{{ $data->nama_pelanggan }}</td>
                                    <td>{{ $data->hp_pelanggan }}</td>
                                    <td>{{ \Carbon\Carbon::parse($data->tanggal)->format('d-m-Y H:i:s') }}</td>
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

                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-primary"><i class="fa fa-eye"></i></button>
                                            <button type="button" class="btn btn-warning"><i class="fa fa-money"></i></button>
                                            <a href="#" class="btn btn-success"><i class="fa fa-edit"></i></a>
                                            <form action="{{ route('destroytransaksipenjualan', $data->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Hapus transaksi ini?')">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>

                                    <td>{{ $data->cabang->nama ?? '-' }}</td>
                                    <td>{{ $data->user->nama ?? '-' }}</td>
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
                                    <td colspan="3"></td>
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

@endsection