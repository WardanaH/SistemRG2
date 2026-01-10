<div class="modal fade" id="modalAcc-{{ $transaksi->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('bantuan.acc', $transaksi->id) }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Konfirmasi Produksi: {{ $transaksi->nomor_nota }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Dari Cabang:</strong> {{ $transaksi->cabangAsal->nama }}</p>
                    <p><strong>Keterangan Pelanggan:</strong> {{ $transaksi->nama_pelanggan }} ({{ $transaksi->hp_pelanggan }})</p>

                    <hr>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Ukuran/Qty</th>
                                <th>Finishing</th>
                                <th style="width: 200px;">Input No. SPK</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaksi->subBantuan as $sub)
                            <tr>
                                <td>{{ $sub->produk->nama_produk }}</td>
                                <td>
                                    @if($sub->panjang > 0)
                                        {{ $sub->panjang }}x{{ $sub->lebar }} cm ({{ $sub->banyak }} pcs)
                                    @else
                                        {{ $sub->banyak }} {{ $sub->satuan }}
                                    @endif
                                </td>
                                <td>{{ $sub->finishing }}</td>
                                <td>
                                    <input type="text" name="no_spk[{{ $sub->id }}]" class="form-control form-control-sm" placeholder="Contoh: SPK-B001" required>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" name="tindakan" value="acc" class="btn btn-success">Terima & Potong Stok</button>
                </div>
            </div>
        </form>
    </div>
</div>
