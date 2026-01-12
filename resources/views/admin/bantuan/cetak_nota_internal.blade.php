<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota Internal - {{ $transaksi->nomor_nota }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .info { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        th { background-color: #eee; }
        .total { text-align: right; font-weight: bold; font-size: 14px; }
        .footer { margin-top: 40px; width: 100%; text-align: center; }
        .ttd { height: 60px; }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <h2>NOTA INTERNAL ANTAR CABANG</h2>
        <h3>{{ $transaksi->cabangBantuan->nama }} (Pengerja)</h3>
    </div>

    <div class="info">
        <table style="border: none;">
            <tr style="border: none;">
                <td style="border: none; width: 15%;"><strong>No Nota</strong></td>
                <td style="border: none; width: 35%;">: {{ $transaksi->nomor_nota }}</td>
                <td style="border: none; width: 15%;"><strong>Cabang Peminta</strong></td>
                <td style="border: none; width: 35%;">: {{ $transaksi->cabangAsal->nama }}</td>
            </tr>
            <tr style="border: none;">
                <td style="border: none;"><strong>Tanggal</strong></td>
                <td style="border: none;">: {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d M Y') }}</td>
                <td style="border: none;"><strong>Pelanggan Akhir</strong></td>
                <td style="border: none;">: {{ $transaksi->nama_pelanggan }}</td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th>Produk</th>
                <th>Ukuran</th>
                <th>Qty</th>
                <th>No SPK (Internal)</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi->subBantuan as $sub)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $sub->produk->nama_produk }}</td>
                <td>
                    @if($sub->panjang > 0) {{ $sub->panjang }}x{{ $sub->lebar }} cm @else - @endif
                </td>
                <td>{{ $sub->banyak }} {{ $sub->satuan }}</td>
                <td>{{ $sub->no_spk ?? '-' }}</td>
                <td>{{ ucfirst($sub->status_sub_transaksi) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p style="font-style: italic;">*Nota ini berfungsi sebagai bukti penyelesaian pekerjaan antar cabang.</p>

    <div class="footer">
        <table style="border: none;">
            <tr style="border: none;">
                <td style="border: none; text-align: center;">
                    <p>Diterima Oleh<br>(Cabang Peminta)</p>
                    <div class="ttd"></div>
                    <p>( .......................... )</p>
                </td>
                <td style="border: none; text-align: center;">
                    <p>Diserahkan Oleh<br>(Cabang Pengerja)</p>
                    <div class="ttd"></div>
                    <p>( {{ Auth::user()->nama }} )</p>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
