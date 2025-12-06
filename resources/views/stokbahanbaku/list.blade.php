@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">📦 Daftar Stok Bahan Baku per Cabang</h4>
        </div>

        <div class="card-body">
            {{-- 🔍 Filter Bahan dan Cabang --}}
            <form action="{{ route('stokbahanbaku.index') }}" method="GET" class="row g-2 mb-4 align-items-end">
                <div class="col-md-4">
                    <label for="bahanbaku_id" class="form-label fw-semibold">Pilih Bahan Baku</label>
                    <select class="select2" id="bahanbaku_id" name="bahanbaku_id">
                        <option value="" {{ $bahanbaku_id == '' ? 'selected' : '' }}>Semua Bahan Baku</option>
                        @foreach ($bahanbakus as $bahanbaku)
                            <option value="{{ encrypt($bahanbaku->id) }}"
                                {{ strlen($bahanbaku_id) > 0 && decrypt($bahanbaku_id) == $bahanbaku->id ? 'selected' : '' }}>
                                {{ $bahanbaku->nama_bahan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @if (Auth::user()->roles->first()->name == 'owner')
                <div class="col-md-4">
                    <label for="cabang_id" class="form-label fw-semibold">Pilih Cabang</label>
                    <select class="select2" id="cabang_id" name="cabang_id">
                        <option value="" {{ $cabang_id == '' ? 'selected' : '' }}>Semua Cabang</option>
                        @foreach ($cabangs as $cabang)
                            <option value="{{ encrypt($cabang->id) }}"
                                {{ strlen($cabang_id) > 0 && decrypt($cabang_id) == $cabang->id ? 'selected' : '' }}>
                                {{ $cabang->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="col-md-4 d-flex">
                    <button type="submit" 
                        class="btn btn-primary"
                        style="width: auto !important; margin-right: 12px !important;">
                        🔍 Tampilkan
                    </button>

                    <a href="{{ route('stokbahanbaku.index') }}" 
                        class="btn btn-secondary"
                        style="width: auto !important;">
                        🔄 Reset
                    </a>
                </div>

            </form>

            {{-- 🔹 Table --}}
            <table class="table table-bordered align-middle styletable">
                <thead class="table-primary text-center">
                    <tr>
                        <th>#</th>
                        <th>Nama Bahan</th>
                        <th>Satuan</th>
                        <th>Batas Stok</th>
                        <th>Stok Saat Ini</th>
                        <th>Cabang</th>
                        @if (Auth::user()->roles->first()->name != 'owner')
                            <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($datas as $b)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $b->nama_bahan }}</td>
                        <td>{{ $b->satuan }}</td>
                        <td>{{ $b->batas_stok }}</td>
                        <td>{{ $b->banyak_stok ?? 0 }}</td>
                        <td>{{ $b->nama_cabang ?? (Auth::user()->cabangs->nama ?? '-') }}</td>

                        {{-- Hanya tampilkan aksi jika bukan owner --}}
                        @if (Auth::user()->roles->first()->name != 'owner')
                        <td class="text-center">
                            <div class="d-flex justify-content-center align-items-center gap-1">
                                {{-- Form update --}}
                                <form action="{{ route('stokbahanbaku.update', encrypt($b->id)) }}" method="POST" class="d-flex align-items-center">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="bahanbaku_id" value="{{ encrypt($b->id) }}">
                                    <input type="hidden" name="stok_id" value="{{ $b->stok_id ? encrypt($b->stok_id) : '' }}">
                                    <input type="number" name="banyak_stok"
                                        value="{{ $b->banyak_stok ?? 0 }}"
                                        min="0"
                                        class="form-control form-control-sm"
                                        style="width: 70px;">
                                    <button type="submit" class="btn btn-sm btn-success ms-1">💾</button>
                                </form>

                                {{-- Form delete --}}
                                @if($b->stok_id)
                                <form action="{{ route('stokbahanbaku.destroy') }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus stok bahan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="delid" value="{{ encrypt($b->stok_id) }}">
                                    <button type="submit" class="btn btn-sm btn-danger ms-1">🗑️</button>
                                </form>
                                @endif
                            </div>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ Auth::user()->roles->first()->name == 'owner' ? 6 : 7 }}"
                            class="text-center text-muted">Tidak ada data stok bahan baku</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
