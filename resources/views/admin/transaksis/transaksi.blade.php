@extends('layouts.app')
@section('content')

<style>
    /* ==========================================
   STYLE TAMBAHAN KHUSUS HALAMAN TRANSAKSI
   (Tidak mengubah sidebar, header, dsb)
========================================== */
    .transaksi-page .card {
        border: none;
        border-radius: 8px;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.05);
    }

    .transaksi-page .card-header {
        background-color: #4B28D2 !important;
        color: #fff !important;
        font-weight: 600;
        display: flex;
        align-items: center;
    }

    .transaksi-page .card-header i {
        color: #FFD54F !important;
        margin-right: 6px;
    }

    .transaksi-page .form-control,
    .transaksi-page .form-select,
    .transaksi-page textarea {
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 0.9rem;
        color: #333;
        transition: border-color 0.2s ease-in-out;
    }

    .transaksi-page .form-control:focus,
    .transaksi-page .form-select:focus {
        border-color: #6a4ff7;
        box-shadow: 0 0 3px rgba(106, 79, 247, 0.3);
    }

    .transaksi-page .btn-success {
        background-color: #28a745 !important;
        border: none;
        font-weight: 500;
        border-radius: 6px;
    }

    .transaksi-page .btn-primary {
        background-color: #4B28D2 !important;
        border: none;
        border-radius: 6px;
        font-weight: 500;
    }

    .transaksi-page .btn-success:hover {
        background-color: #218838 !important;
    }

    .transaksi-page .btn-primary:hover {
        background-color: #3a1fb0 !important;
    }

    .transaksi-page .table th {
        background-color: #f8f9fc !important;
        color: #212121 !important;
        font-weight: 600;
        text-align: center;
    }

    .transaksi-page .table td {
        color: #2b2b2b !important;
        vertical-align: middle;
    }

    .transaksi-page .table tbody tr:nth-child(even) td {
        background-color: #fafafa !important;
    }

    .transaksi-page .table tbody tr td[colspan] {
        text-align: center;
        color: #555 !important;
        font-style: italic;
    }

    .transaksi-page input[type="radio"] {
        accent-color: #4B28D2;
        transform: scale(1.1);
        margin-right: 4px;
    }

    /* =======================================
   FIX WARNA TEKS & KONTRAS BUTTON
======================================= */

    /* Pertegas semua teks di form dan tabel */
    .transaksi-page .form-control,
    .transaksi-page .form-select,
    .transaksi-page .table td,
    .transaksi-page .table th {
        color: #1f1f1f !important;
        opacity: 1 !important;
    }

    /* Label dan teks muted jangan abu transparan */
    .transaksi-page label,
    .transaksi-page p,
    .transaksi-page small,
    .transaksi-page span {
        color: #1f1f1f !important;
        opacity: 1 !important;
    }

    /* TOMBOL TAMBAH ITEM dan SIMPAN TRANSAKSI */
    .transaksi-page .btn-success,
    .transaksi-page .btn-primary {
        color: #fff !important;
        opacity: 1 !important;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .transaksi-page .btn-success:hover,
    .transaksi-page .btn-primary:hover {
        opacity: 0.9 !important;
    }

    /* PERBAIKI RADIO BUTTON (Lunas / DP) */
    .transaksi-page .form-check-inline {
        margin-right: 1rem;
    }

    .transaksi-page input[type="radio"] {
        margin-right: 5px;
        accent-color: #4B28D2;
        transform: scale(1.2);
    }

    /* =====================================
   FIX SELECT2 AGAR SESUAI TEMPLATE FOCUS
===================================== */
    .transaksi-page .select2-container .select2-selection--single {
        height: 38px !important;
        border: 1px solid #dcdcdc !important;
        border-radius: 6px !important;
        background-color: #fff !important;
        padding: 4px 10px !important;
        display: flex !important;
        align-items: center !important;
        font-size: 0.9rem !important;
        color: #333 !important;
        transition: all 0.2s ease-in-out;
    }

    /* Fokus (saat diklik) */
    .transaksi-page .select2-container--default .select2-selection--single:focus,
    .transaksi-page .select2-container--default .select2-selection--single:hover {
        border-color: #6a4ff7 !important;
        box-shadow: 0 0 3px rgba(106, 79, 247, 0.3);
    }

    /* Panah dropdown */
    .transaksi-page .select2-container--default .select2-selection__arrow b {
        border-color: #4B28D2 transparent transparent transparent !important;
    }

    /* Teks di dalam */
    .transaksi-page .select2-selection__rendered {
        color: #333 !important;
        font-weight: 500 !important;
    }

    /* Background hasil dropdown */
    .transaksi-page .select2-container--default .select2-results__option {
        color: #222 !important;
        font-size: 0.9rem !important;
        padding: 6px 12px !important;
    }

    /* Hover item dropdown */
    .transaksi-page .select2-container--default .select2-results__option--highlighted {
        background-color: #4B28D2 !important;
        color: #fff !important;
    }

    /* Background dropdown list */
    .transaksi-page .select2-dropdown {
        border: 1px solid #dcdcdc !important;
        border-radius: 6px !important;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.05);
    }

    .btn-disabled {
        opacity: 0.5 !important;
        cursor: not-allowed !important;
        pointer-events: none !important;
    }

    /* Teks label dan form di dalam modal */
    #modal_add label,
    #modal_add .form-control,
    #modal_add .form-select,
    #modal_add .select2-selection__rendered,
    #modal_add .select2-results__option,
    #modal_add .select2-selection__placeholder {
        color: #1f1f1f !important;
        opacity: 1 !important;
    }

    /* Teks placeholder agar tidak terlalu pucat */
    #modal_add ::placeholder {
        color: #444 !important;
        opacity: 1 !important;
    }

    /* Pertegas hasil dropdown Select2 */
    #modal_add .select2-dropdown,
    #modal_add .select2-results__option {
        background-color: #fff !important;
        color: #222 !important;
        font-weight: 500 !important;
    }

    /* Input readonly (seperti harga & subtotal) */
    #modal_add input[readonly] {
        background-color: #f9f9f9 !important;
        color: #1f1f1f !important;
        opacity: 1 !important;
    }

    /* Warna item normal di dropdown */
    #modal_add .select2-container--default .select2-results__option {
        background-color: #fff !important;
        color: #222 !important;
        font-size: 0.9rem !important;
        padding: 6px 10px !important;
        transition: all 0.15s ease-in-out;
    }

    /* Warna item saat di-hover */
    #modal_add .select2-container--default .select2-results__option--highlighted {
        background-color: #4B28D2 !important;
        /* warna ungu sesuai tema */
        color: #fff !important;
        font-weight: 500 !important;
        border-radius: 3px !important;
    }
</style>

<div class="container-fluid transaksi-page">
    <div class="row">

        @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        {{-- ======================= FORM PELANGGAN ======================= --}}
        <div class="col-md-3">
            <form id="formpelanggan">
                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">👤 Pelanggan</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <input type="text" id="namapelanggan" name="namapelanggan" class="form-control" placeholder="Nama Pelanggan">
                        </div>
                        <div class="mb-3">
                            <input type="text" id="nomorhandphone" name="nomorhandphone" class="form-control" placeholder="Nomor Handphone">
                        </div>
                        <div class="mb-3">
                            <select id="pelanggan" name="pelanggan" class="form-select select2">
                                <option value="">Pilih Pelanggan</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button type="button" id="submitpelanggan" class="btn btn-success btn-sm">
                            Submit <i class="fa fa-chevron-circle-right"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- ======================= FORM TRANSAKSI ======================= --}}
        <div class="col-md-9">
            <form action="{{ route('storetransaksipenjualan') }}" method="POST">
                @csrf
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fa fa-shopping-cart"></i> Transaksi Penjualan</h5>
                    </div>
                    <div class="card-body">

                        {{-- HEADER INFO --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>No. Nota:</strong> <span id="nonota">RG-{{ now()->timestamp }}</span></p>
                                <input type="hidden" name="nonota" value="RG-{{ now()->timestamp }}">
                                <p><strong>Tanggal:</strong>
                                    <input type="text" class="form-control form-control-sm" name="inputtanggal" readonly value="{{ $date }}">
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Kepada:</strong> <span id="kepadalabel">-</span></p>
                                <input type="hidden" name="inputnamapelanggan" id="namapelangganhidden">
                                <p><strong>No. HP:</strong> <span id="handphonelabel">-</span></p>
                                <input type="hidden" name="inputnomorpelanggan" id="nomorhandphonehidden">
                                <input type="hidden" name="inputpelanggan" id="pelangganhidden">
                                <label for="inputdesigner" class="form-label">Pilih Designer</label>
                                <select name="inputdesigner" id="inputdesigner" class="form-select select2" required disabled>
                                    <option value="">Pilih Designer</option>
                                    @foreach($designers as $d)
                                    <option value="{{ $d->id }}">{{ $d->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- TABEL ITEM --}}
                        <div class="table-responsive">
                            <table class="table table-bordered text-center" id="tableItem">
                                <thead class="table-success">
                                    <tr>
                                        <th>Nama Barang</th>
                                        <th>Harga</th>
                                        <th>P</th>
                                        <th>L</th>
                                        <th>Kuantitas</th>
                                        <th>Finishing</th>
                                        <th>Diskon %</th>
                                        <th>Subtotal</th>
                                        <th>No SPK</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>

                        <button type="button" id="btnAddItem" class="btn btn-success btn-sm mt-2" disabled data-bs-toggle="modal" data-bs-target="#modal_add">
                            <i class="fa fa-plus"></i> Tambah Item
                        </button>

                        {{-- TOTAL --}}
                        <div class="row mt-3">
                            <div class="col-md-6"></div>
                            <div class="col-md-3">
                                <label>Diskon (%)</label>
                                <input type="number" id="diskon" name="inputdiskon" class="form-control" value="0">
                            </div>
                            <div class="col-md-3">
                                <label>Total</label>
                                <input type="text" id="total" name="inputtotal" class="form-control text-end" readonly value="Rp 0">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6"></div>

                            <!-- Kolom Bayar -->
                            <div class="col-md-3 position-relative">
                                <label>Bayar</label>
                                <input type="text" id="bayardp" name="inputbayardp" class="form-control" value="0">

                                <!-- Radio di bawah input Bayar -->
                                <div class="d-flex justify-content-start align-items-center mt-2 ms-2">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="metode" id="metodelunas" value="lunas">
                                        <label class="form-check-label" for="metodelunas">Lunas</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="metode" id="metodedp" value="dp">
                                        <label class="form-check-label" for="metodedp">DP 50%</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Kolom Pembayaran -->
                            <div class="col-md-3">
                                <label>Pembayaran</label>
                                <select id="pembayaran" name="inputpembayaran" class="form-select">
                                    <option value="Cash">Cash</option>
                                    <option value="Transfer">Transfer</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6"></div>
                            <div class="col-md-3">
                                <label>Pajak (%)</label>
                                <input type="number" id="pajak" name="inputpajak" class="form-control" value="0">
                            </div>
                            <div class="col-md-3">
                                <label>Sisa</label>
                                <input type="text" id="sisa" name="inputsisa" class="form-control text-end" readonly value="Rp 0">
                            </div>
                        </div>

                        {{-- ITEM JSON --}}
                        <input type="hidden" name="items" id="itemsInput">

                        <hr>
                        <div class="text-end">
                            <button type="submit" id="submittransaksi" class="btn btn-primary btn-sm" disabled>
                                <i class="fa fa-check-circle"></i> Simpan Transaksi
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>

{{-- ======================= MODAL TAMBAH ITEM ======================= --}}
<div class="modal fade" id="modal_add" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Tambah Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label>Produk</label>
                            <select id="add_produk" class="form-select select2" style="width:100%;">
                                <option value="">-- Pilih Produk --</option>
                                @foreach ($produks as $produk)
                                <option value="{{ $produk->id }}" data-harga="{{ $produk->harga_jual }}">
                                    {{ $produk->nama_produk }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label>Harga</label>
                                <input id="add_harga" class="form-control" type="number" readonly>
                            </div>
                            <div class="col-md-3 mb-2">
                                <label>P</label>
                                <input id="add_panjang" class="form-control" type="number" value="0">
                            </div>
                            <div class="col-md-3 mb-2">
                                <label>L</label>
                                <input id="add_lebar" class="form-control" type="number" value="0">
                            </div>
                        </div>
                        <div class="mb-2">
                            <label>Kuantitas</label>
                            <input id="add_kuantitas" class="form-control" type="number" value="1">
                        </div>
                        <div class="mb-2">
                            <label>Finishing</label>
                            <select id="add_finishing" class="form-select">
                                <option value="Tanpa Finishing">Tanpa Finishing</option>
                                <option value="Mata Ayam">Mata Ayam</option>
                                <option value="Laminasi Glossy">Laminasi Glossy</option>
                                <option value="Laminasi Doff">Laminasi Doff</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label>Diskon (%)</label>
                            <input id="add_diskon" class="form-control" type="number" value="0">
                        </div>
                        <div class="mb-2">
                            <label>Subtotal</label>
                            <input id="add_subtotal" class="form-control" readonly>
                        </div>
                        <div class="mb-2">
                            <label>No SPK <span style="color: red;">*</span></label>
                            <input id="add_nospk" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label>Keterangan</label>
                            <textarea id="add_keterangan" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keluar</button>
                <button type="button" id="additem" class="btn btn-success">Simpan</button>
            </div>
        </div>
    </div>
</div>

{{-- ======================= SCRIPT ======================= --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(function() {
        $('#pelanggan').select2({
            theme: 'bootstrap-5',
            placeholder: 'Pilih Pelanggan',
            width: '100%'
        });

        $('#inputdesigner').select2({
            theme: 'bootstrap-5',
            placeholder: 'Pilih Designer',
            width: '100%'
        });

        let total = 0;
        const storeUrl = "{{ route('storetransaksipenjualan') }}";
        const form = $(`form[action='${storeUrl}']`);
        let items = [];

        // ================== LOAD PELANGGAN ==================
        $.get("{{ route('pelanggan.data') }}", function(res) {
            res.data.forEach(p => {
                $('#pelanggan').append(
                    `<option value="${p.id}" data-nama="${p.nama_pemilik}" data-hp="${p.hp_pelanggan}">
                    ${p.nama_pemilik} - ${p.hp_pelanggan}
                </option>`
                );
            });
        });

        // ================== SUBMIT DATA PELANGGAN ==================
        $('#submitpelanggan').click(function() {
            const idPelanggan = $('#pelanggan').val();
            let nama = $('#namapelanggan').val();
            let hp = $('#nomorhandphone').val();

            if (idPelanggan) {
                const opt = $('#pelanggan option:selected');
                nama = opt.data('nama');
                hp = opt.data('hp');
            }
            if (!nama || !hp) {
                return Swal.fire({
                    icon: 'warning',
                    title: 'Data belum lengkap!',
                    text: 'Isi nama dan nomor HP pelanggan!',
                });
            }


            $('#kepadalabel').text(nama);
            $('#handphonelabel').text(hp);
            $('#btnAddItem, #submittransaksi').prop('disabled', false);
            $('#inputdesigner').prop('disabled', false);

            $('#namapelangganhidden').val(nama);
            $('#nomorhandphonehidden').val(hp);
            $('#pelangganhidden').val(idPelanggan);
        });

        // ================== HITUNG SUBTOTAL ITEM ==================
        $('#add_produk').on('change', function() {
            const harga = $('option:selected', this).data('harga') || 0;
            $('#add_harga').val(harga);
            hitungSubtotal();
        });

        $('#add_panjang, #add_lebar, #add_kuantitas, #add_diskon').on('input', hitungSubtotal);

        function hitungSubtotal() {
            const harga = parseFloat($('#add_harga').val()) || 0;
            const panjang = parseFloat($('#add_panjang').val()) || 0;
            const lebar = parseFloat($('#add_lebar').val()) || 0;
            const qty = parseFloat($('#add_kuantitas').val()) || 1;
            const diskon = parseFloat($('#add_diskon').val()) || 0;

            let subtotal = harga * (panjang && lebar ? panjang * lebar : 1) * qty;
            subtotal -= subtotal * (diskon / 100);

            $('#add_subtotal').val('Rp ' + subtotal.toLocaleString('id-ID'));
        }

        // ================== FIX SELECT2 DI MODAL ==================
        $('#modal_add').on('shown.bs.modal', function() {
            $('#add_produk, #add_finishing').select2({
                dropdownParent: $('#modal_add')
            });
        });

        // ================== TAMBAH ITEM KE TABEL ==================
        $('#additem').click(function() {
            const produkId = $('#add_produk').val();
            const nama = $('#add_produk option:selected').text().trim();
            const harga = parseFloat($('#add_harga').val()) || 0;
            const panjang = parseFloat($('#add_panjang').val()) || 0;
            const lebar = parseFloat($('#add_lebar').val()) || 0;
            const qty = parseFloat($('#add_kuantitas').val()) || 1;
            const finishing = $('#add_finishing').val();
            const diskon = parseFloat($('#add_diskon').val()) || 0;
            const subtotal = harga * (panjang && lebar ? panjang * lebar : 1) * qty * (1 - diskon / 100);
            const no_spk = $('#add_nospk').val() || '-';
            const keterangan = $('#add_keterangan').val() || '-';

            const newItem = {
                produk_id: produkId,
                nama: nama,
                harga: harga,
                panjang: panjang,
                lebar: lebar,
                kuantitas: qty,
                finishing: finishing,
                diskon: diskon,
                subtotal: subtotal,
                no_spk: no_spk,
                keterangan: keterangan
            };
            items.push(newItem);

            // update hidden input untuk array items[]
            refreshHiddenInputs();

            // Tambahkan baris ke tabel
            $('#tableItem tbody').append(`
            <tr>
                <td>${nama}</td>
                <td>Rp ${harga.toLocaleString('id-ID')}</td>
                <td>${panjang}</td>
                <td>${lebar}</td>
                <td>${qty}</td>
                <td>${finishing}</td>
                <td>${diskon}%</td>
                <td>Rp ${subtotal.toLocaleString('id-ID')}</td>
                <td>${no_spk}</td>
                <td><button type="button" class="btn btn-danger btn-sm removeItem"><i class="fa fa-trash"></i></button></td>
            </tr>
        `);

            total += subtotal;
            updateTotal();
            $('#modal_add').modal('hide');
            $('#modal_add input, #add_keterangan').val('');
        });

        // ================== REFRESH INPUT ITEMS (Biar Laravel baca array) ==================
        function refreshHiddenInputs() {
            // hapus input lama
            form.find('input[name^="items["]').remove();

            // generate ulang input berdasarkan isi array items
            items.forEach((item, i) => {
                Object.entries(item).forEach(([key, val]) => {
                    form.append(`<input type="hidden" name="items[${i}][${key}]" value="${val}">`);
                });
            });
        }

        // ================== HAPUS ITEM DARI TABEL ==================
        $(document).on('click', '.removeItem', function() {
            const rowIndex = $(this).closest('tr').index();
            const val = parseFloat($(this).closest('tr').find('td').eq(7).text().replace(/[^\d]/g, ''));
            total -= val;

            // hapus dari array dan update input hidden
            items.splice(rowIndex, 1);
            refreshHiddenInputs();

            $(this).closest('tr').remove();
            updateTotal();
        });

        // ================== UPDATE TOTAL ==================
        $('#diskon, #pajak, #bayardp').on('input change', updateTotal);
        $('#metodelunas, #metodedp').on('change', updateTotal);

        function updateTotal() {
            const diskon = parseFloat($('#diskon').val()) || 0;
            const pajak = parseFloat($('#pajak').val()) || 0;
            let bayar = parseFloat($('#bayardp').val().replace(/[^0-9]/g, '')) || 0;

            let totalDiskon = total - (total * (diskon / 100));
            let totalFinal = totalDiskon + (totalDiskon * (pajak / 100));

            if ($('#metodedp').is(':checked')) {
                bayar = totalFinal / 2;
                $('#bayardp').val('Rp ' + bayar.toLocaleString('id-ID'));
            } else if ($('#metodelunas').is(':checked')) {
                bayar = totalFinal;
                $('#bayardp').val('Rp ' + bayar.toLocaleString('id-ID'));
            }

            const sisa = totalFinal - bayar;
            $('#total').val('Rp ' + totalFinal.toLocaleString('id-ID'));
            $('#sisa').val('Rp ' + sisa.toLocaleString('id-ID'));
        }

// ================== TOMBOL TAMBAH ITEM & SIMPAN TRANSAKSI ==================
const $btnAddItem = $('#btnAddItem');
const $submitTransaksi = $('#submittransaksi');

let adaItem = false; // jadi true setelah klik "Tambah Item"

// fungsi update status tombol
function updateTombolStatus() {
    const pelangganTerisi =
        ($('#namapelangganhidden').val() || '') !== '' &&
        ($('#nomorhandphonehidden').val() || '') !== '';

    // ===== TOMBOL TAMBAH ITEM =====
    if (pelangganTerisi) {
        $btnAddItem.prop('disabled', false)
            .removeClass('btn-secondary btn-disabled')
            .addClass('btn-success');
    } else {
        $btnAddItem.prop('disabled', true)
            .removeClass('btn-success')
            .addClass('btn-secondary btn-disabled');
    }

    // ===== TOMBOL SIMPAN TRANSAKSI =====
    // aturan lama: aktif setelah pelanggan terisi (WAJIB DIPERTAHANKAN UNTUK PAYLOAD)
    // aturan baru: harus ada item (tambahan)
    if (pelangganTerisi && adaItem) {
        $submitTransaksi.prop('disabled', false)
            .removeClass('btn-secondary btn-disabled')
            .addClass('btn-primary');
    } else {
        $submitTransaksi.prop('disabled', true)
            .removeClass('btn-primary')
            .addClass('btn-secondary btn-disabled');
    }
}

// panggil awal
updateTombolStatus();

$('#submitpelanggan').on('click', function () {
    updateTombolStatus();
});

$('#btnAddItem').on('click', function () {
    adaItem = true;
    updateTombolStatus();
});


        // ================== SUBMIT TRANSAKSI DAN CETAK REPORT ==================
        form.on('submit', function(e) {
            e.preventDefault();

            const formData = form.serialize();

            $.ajax({
                type: "POST",
                url: form.attr('action'),
                data: formData,
                success: function(response) {
                    Swal.fire({
                        title: 'Transaksi Berhasil!',
                        text: 'Apakah Anda ingin mencetak nota transaksi?',
                        icon: 'success',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Cetak!',
                        cancelButtonText: 'Tidak'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const reportUrl = "{{ route('transaksi.report', ':id') }}".replace(':id', response.id);
                            window.open(reportUrl, '_blank');
                        }
                        window.location.href = "{{ route('addtransaksiindex') }}";
                    });
                },
                error: function(xhr) {
                    console.log("STATUS:", xhr.status);
                    console.log("RESPONSE:", xhr.responseText);

                    Swal.fire('Gagal!', xhr.responseJSON?.message || xhr.responseText, 'error');
                }
            });
        });

    });
</script>



@endsection
