<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cabang;
use App\Models\MCabangBarang;
use App\Models\MInventarisKantor;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class CabangDinamisController extends Controller
{
    /**
     * 🔹 Daftar Barang per Cabang
     */
    public function barang($slug)
    {
        $cabang = Cabang::where('slug', $slug)->firstOrFail();

        $datas = \App\Models\MBahanBakus::leftJoin('stok_bahan_bakus', function ($join) use ($cabang) {
                $join->on('bahanbakus.id', '=', 'stok_bahan_bakus.bahanbaku_id');
                $join->where('stok_bahan_bakus.cabang_id', $cabang->id);
            })
            ->leftJoin('kategories', 'kategories.id', '=', 'bahanbakus.kategori_id')
            ->select(
                'bahanbakus.*',                               
                'bahanbakus.id as id_bahanbaku',

                'stok_bahan_bakus.banyak_stok',
                'stok_bahan_bakus.satuan as satuan_stok',    

                'kategories.Nama_Kategori as nama_kategori'  
            )
            ->orderBy('stok_bahan_bakus.banyak_stok', 'DESC')
            ->get();

        $kategori = \App\Models\MKategories::all();

        return view('admin.inventaris.templateinventaris.barang', [
            'title'    => 'Daftar Barang - ' . $cabang->nama,
            'cabang'   => $cabang,
            'datas'    => $datas,
            'kategori' => $kategori
        ]);
    }


    public function barangStore(Request $request, $slug)
    {
        $cabang = Cabang::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'kategori_id' => 'required|integer',
            'nama_bahan'  => 'required|string|max:255',
            'harga'       => 'required|numeric',
            'satuan'      => 'required|string|max:50',
            'batas_stok'  => 'nullable|numeric',
            'keterangan'  => 'nullable|string',
            'stok'        => 'required|numeric|min:0'
        ]);

        $bahan = \App\Models\MBahanBakus::create([
            'kategori_id' => $validated['kategori_id'],
            'nama_bahan'  => $validated['nama_bahan'],
            'harga'       => $validated['harga'],
            'satuan'      => $validated['satuan'],
            'batas_stok'  => $validated['batas_stok'] ?? 0,
            'keterangan'  => $validated['keterangan'] ?? '',
            'hitung_luas' => 0
        ]);

        \DB::table('stok_bahan_bakus')->insert([
            'bahanbaku_id' => $bahan->id,
            'cabang_id'    => $cabang->id,
            'banyak_stok'  => $validated['stok'],
            'satuan'       => $validated['satuan'],
        ]);

        return redirect()->back()->with('success', 'Bahan baku berhasil ditambahkan.');
    }

    public function barangUpdate(Request $request, $slug, $id)
    {
        $cabang = Cabang::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'kategori_id' => 'required|integer',
            'nama_bahan'  => 'required|string|max:255',
            'harga'       => 'required|numeric',
            'satuan'      => 'required|string|max:50',
            'stok'        => 'required|numeric',
            'batas_stok'  => 'nullable|numeric',
            'keterangan'  => 'nullable|string',
        ]);

        \App\Models\MBahanBakus::where('id', $id)->update([
            'kategori_id' => $validated['kategori_id'],
            'nama_bahan'  => $validated['nama_bahan'],
            'harga'       => $validated['harga'],
            'satuan'      => $validated['satuan'],
            'batas_stok'  => $validated['batas_stok'] ?? 0,
            'keterangan'  => $validated['keterangan'] ?? ''
        ]);

        \DB::table('stok_bahan_bakus')
            ->where('bahanbaku_id', $id)
            ->where('cabang_id', $cabang->id)
            ->update([
                'banyak_stok' => $validated['stok'],
                'satuan'      => $validated['satuan']
            ]);

        return redirect()->back()->with('success', 'Bahan baku berhasil diperbarui.');
    }

    public function barangDestroy($slug, $id)
    {
        $cabang = Cabang::where('slug', $slug)->firstOrFail();

        \DB::table('stok_bahan_bakus')
            ->where('bahanbaku_id', $id)
            ->where('cabang_id', $cabang->id)
            ->delete();

        \App\Models\MBahanBakus::where('id', $id)->delete();

        return redirect()->back()->with('success', 'Bahan baku berhasil dihapus.');
    }


    /**
     * 🔹 Stok Barang per Cabang
     */
    public function stok($slug)
    {
        $cabang = Cabang::where('slug', $slug)->firstOrFail();

        $datas = \App\Models\MBahanBakus::leftJoin('stok_bahan_bakus', function ($join) use ($cabang) {
                $join->on('bahanbakus.id', '=', 'stok_bahan_bakus.bahanbaku_id');
                $join->where('stok_bahan_bakus.cabang_id', $cabang->id);
            })
            ->select(
                'bahanbakus.*',
                'stok_bahan_bakus.id as stok_id',
                \DB::raw('COALESCE(stok_bahan_bakus.banyak_stok, 0) as banyak_stok'),
                'stok_bahan_bakus.satuan as satuan_stok'
            )
            ->where('banyak_stok', '>', 0)
            ->orderBy('banyak_stok', 'DESC')
            ->get();


        return view("admin.inventaris.templateinventaris.stok", [
            'title'   => 'Stok Barang - ' . $cabang->nama,
            'cabang'  => $cabang,
            'datas'   => $datas,
        ]);

    }

 /* ===========================================================
🔹 3. INVENTARIS KANTOR 
=========================================================== */

private function getCabang($slug)
{
    return Cabang::where('slug', $slug)->firstOrFail();
}

public function inventaris($slug)
{
    $cabang = $this->getCabang($slug);

    $data = MInventarisKantor::where('cabang_id', $cabang->id)->get();

    return view("admin.inventaris.templateinventaris.inventariskantor", [
        'title'  => 'Inventaris Kantor - ' . $cabang->nama,
        'data'   => $data,
        'cabang' => $cabang
    ]);
}

/* ===========================================================
🔹 STORE INVENTARIS
=========================================================== */
public function inventarisStore(Request $req, $slug)
{
    $cabang = $this->getCabang($slug);

    $req->validate([
        'nama_barang' => 'required|string|max:255',
        'kode_barang' => 'required|string|max:255|unique:inventaris_kantors,kode_barang',
        'jumlah'      => 'required|numeric|min:1',
        'kondisi'     => 'required|string|max:255',
        'lokasi'      => 'nullable|string|max:255',
        'tanggal_input' => 'required|date',
    ]);

    // buat data inventaris
    $inventaris = MInventarisKantor::create([
        'cabang_id'     => $cabang->id,
        'nama_barang'   => $req->nama_barang,
        'kode_barang'   => $req->kode_barang,
        'jumlah'        => $req->jumlah,
        'kondisi'       => $req->kondisi,
        'lokasi'        => $req->lokasi,
        'tanggal_input' => $req->tanggal_input,
    ]);

    // generate isi QR
    $qrText = "Inventaris: {$req->nama_barang}\nKode: {$req->kode_barang}\nCabang: {$cabang->nama}";

    // generate file SVG QR
    $svgData = QrCode::format('svg')
        ->size(250)
        ->generate($qrText);

    $qrFileName = 'qr_' . time() . '_' . rand(100,999) . '.svg';
    $qrPath = 'qr_inventaris/' . $qrFileName;

    // simpan ke storage/public
    Storage::disk('public')->put($qrPath, $svgData);

    // update kolom qr_code
    $inventaris->update([
        'qr_code' => $qrPath
    ]);

    return back()->with('success', 'Inventaris berhasil ditambahkan!');
}

/* ===========================================================
🔹 UPDATE INVENTARIS
=========================================================== */
public function inventarisUpdate(Request $req, $slug, $id)
{
    $cabang = $this->getCabang($slug);

    $req->validate([
        'nama_barang' => 'required|string|max:255',
        'kode_barang' => 'nullable|string|max:255',
        'jumlah'      => 'required|numeric|min:1',
        'kondisi'     => 'required|string|max:255',
        'lokasi'      => 'nullable|string|max:255',
        'tanggal_input' => 'required|date',
    ]);

    // FIX: kolom id tidak pakai spasi
    $inv = MInventarisKantor::where('id', $id)
            ->where('cabang_id', $cabang->id)
            ->firstOrFail();

    $inv->update([
        'nama_barang'   => $req->nama_barang,
        'kode_barang'   => $req->kode_barang,
        'jumlah'        => $req->jumlah,
        'kondisi'       => $req->kondisi,
        'lokasi'        => $req->lokasi,
        'tanggal_input' => $req->tanggal_input,
    ]);

    return back()->with('success', 'Inventaris berhasil diperbarui!');
}

/* ===========================================================
🔹 DESTROY INVENTARIS
=========================================================== */
public function inventarisDestroy($slug, $id)
{
    $cabang = $this->getCabang($slug);

    // FIX: gunakan kolom "id", bukan id_inventaris
    $inv = MInventarisKantor::where('id', $id)
            ->where('cabang_id', $cabang->id)
            ->firstOrFail();

    // FIX: kolom QR yang benar "qr_code"
    if ($inv->qr_code && Storage::disk('public')->exists($inv->qr_code)) {
        Storage::disk('public')->delete($inv->qr_code);
    }

    $inv->delete();

    return back()->with('success', 'Inventaris berhasil dihapus!');
}


    /* ===========================================================
    🔹 4. RIWAYAT PENERIMAAN PENGIRIMAN BAHAN KE CABANG
    =========================================================== */
    public function riwayat($slug)
    {
        $cabang = Cabang::where('slug', $slug)->firstOrFail();

        $riwayat = \App\Models\MPengiriman::leftJoin('bahanbakus', 'pengirimans.id_barang', '=', 'bahanbakus.id')
            ->where('pengirimans.tujuan_pengiriman', $slug)
            ->select(
                'pengirimans.*',
                'bahanbakus.nama_bahan',
                'bahanbakus.satuan'
            )
            ->orderBy('pengirimans.id_pengiriman', 'DESC')
            ->get();

        return view("admin.inventaris.templateinventaris.riwayat", [
            'title' => 'Riwayat Pengiriman - ' . $cabang->nama,
            'riwayat' => $riwayat,
            'cabang' => $cabang
        ]);
    }

    /* ===========================================================
    🔹 5. CABANG MENERIMA BARANG
    =========================================================== */
    public function riwayatTerima($slug, $id)
    {
        $cabang = $this->getCabang($slug);

        // ambil data pengiriman
        $pengiriman = \App\Models\MPengiriman::with('barang')
                        ->where('id_pengiriman', $id)
                        ->where('tujuan_pengiriman', $slug)   // tujuan = slug
                        ->firstOrFail();

        // jika sudah diterima → stop
        if ($pengiriman->status_penerimaan === 'Diterima') {
            return back()->with('error', 'Barang ini sudah diterima sebelumnya.');
        }

        // cek apakah stok bahan di cabang sudah ada
        $stokCabang = \DB::table('stok_bahan_bakus')
            ->where('cabang_id', $cabang->id)
            ->where('bahanbaku_id', $pengiriman->id_barang)
            ->first();

        if ($stokCabang) {
            // jika sudah ada → update stok
            \DB::table('stok_bahan_bakus')
                ->where('cabang_id', $cabang->id)
                ->where('bahanbaku_id', $pengiriman->id_barang)
                ->update([
                    'banyak_stok' => $stokCabang->banyak_stok + $pengiriman->jumlah,
                    'satuan'      => $pengiriman->barang->satuan
                ]);
        } else {
            // jika belum ada → buat stok baru
            \DB::table('stok_bahan_bakus')->insert([
                'cabang_id'     => $cabang->id,
                'bahanbaku_id'  => $pengiriman->id_barang,
                'banyak_stok'   => $pengiriman->jumlah,
                'satuan'        => $pengiriman->barang->satuan,
            ]);
        }

        // update status penerimaan
        $pengiriman->status_penerimaan = 'Diterima';
        $pengiriman->save();

        return back()->with('success', 'Barang berhasil diterima oleh cabang!');
    }

    /* ===========================================================
    🔹 6. MANAJEMEN CABANG
    =========================================================== */
    public function manageCabang()
    {
        $cabangs = \App\Models\Cabang::where('jenis', 'cabang')
            ->orderBy('nama')
            ->get();
        return view('admin.inventaris.cabang.index', compact('cabangs'));
    }

    public function manageCabangStore(Request $request)
    {
        $request->validate([
            'kode' => 'required',
            'nama' => 'required',
            'slug' => 'required|unique:cabangs,slug',
            'jenis' => 'required'
        ]);

        Cabang::create($request->all());
        return back()->with('success', 'Cabang berhasil ditambahkan');
    }

    public function manageCabangUpdate(Request $request, $id)
    {
        $cabang = Cabang::findOrFail($id);

        $request->validate([
            'kode' => 'required',
            'nama' => 'required',
            'slug' => 'required|unique:cabangs,slug,' . $cabang->id,
            'jenis' => 'required'
        ]);

        $cabang->update($request->all());
        return back()->with('success', 'Cabang berhasil diperbarui');
    }

    public function manageCabangDelete($id)
    {
        Cabang::findOrFail($id)->delete();
        return back()->with('success', 'Cabang berhasil dihapus');
    }

}