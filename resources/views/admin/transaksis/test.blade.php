<script>
    // ================== HITUNG SUBTOTAL ITEM ==================
    $('#add_produk').on('select2:select', function(e) {
        const produkId = e.params.data.id;
        const pelangganId = $('#pelanggan').val();

        // reset input
        $('#add_harga').val(0);
        $('#add_diskon').val(0);
        $('#add_subtotal').val(0);
        $('#add_kuantitas').val(1);
        $('#add_panjang').val(0);
        $('#add_lebar').val(0);

        // ================== TANPA PELANGGAN (HARGA NORMAL) ==================
        if (!pelangganId) {
            $.get("{{ route('produk.hargaproduk') }}", {
                id: produkId
            }, function(res) {
                hargaAktif = parseFloat(res.harga_jual);
                hitungLuasAktif = res.hitung_luas;
                satuanAktif = res.satuan.toLowerCase();
                rangePrices = [];

                applyHitungLuas(hitungLuasAktif);
                $('#add_harga').val(hargaAktif);

                hitungSubtotal();
            });
        }
        // ================== DENGAN PELANGGAN (SPECIAL PRICE) ==================
        else {
            $.get("{{ route('produk.hargaprodukkhusus') }}", {
                produkid: produkId,
                pelanggan: pelangganId
            }, function(res) {

                console.log(res);
                hargaAktif = parseFloat(res.harga_jual);
                hitungLuasAktif = res.hitung_luas;
                satuanAktif = res.satuan.toLowerCase();
                rangePrices = res.range_prices || [];

                // default fallback price
                rangePrices.push({
                    nilai_awal: 0,
                    nilai_akhir: 0,
                    harga_khusus: hargaAktif
                });

                applyHitungLuas(hitungLuasAktif);
                $('#add_harga').val(hargaAktif);

                hitungSubtotal();
            });
        }
    });

    function applyHitungLuas(hitungLuas) {
        if (hitungLuas == 1) {
            $('#add_panjang, #add_lebar').prop('disabled', false);
        } else {
            $('#add_panjang, #add_lebar').val(0).prop('disabled', true);
        }
    }

    $('#add_panjang, #add_lebar, #add_kuantitas, #add_diskon').on('input', hitungSubtotal);

    function hitungSubtotal() {
        let harga = hargaAktif;
        let panjang = parseFloat($('#add_panjang').val()) || 0;
        let lebar = parseFloat($('#add_lebar').val()) || 0;
        let qty = parseFloat($('#add_kuantitas').val()) || 1;
        let diskon = parseFloat($('#add_diskon').val()) || 0;

        let luas = 1;

        if (hitungLuasAktif == 1) {
            if (satuanAktif === 'cm' || satuanAktif === 'centimeter') {
                panjang /= 100;
                lebar /= 100;
            }
            luas = panjang * lebar;
        }

        // ================== SPECIAL PRICE BY RANGE ==================
        if (rangePrices.length > 0) {
            rangePrices.forEach(r => {
                if (
                    luas >= r.nilai_awal &&
                    (r.nilai_akhir == 0 || luas <= r.nilai_akhir)
                ) {
                    harga = parseFloat(r.harga_khusus);
                }
            });
        }

        let subtotal = harga * luas * qty;
        subtotal -= subtotal * (diskon / 100);

        subtotalNumeric = subtotal;

        $('#add_harga').val(harga);
        $('#add_subtotal').val(
            'Rp ' + subtotal.toLocaleString('id-ID')
        );
    }
</script>
