<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cabang;
use App\Models\MBahanBakus;
use App\Models\MStokBahanBakus;
use App\Models\MPengiriman;

class PengirimanGudangController extends Controller
{
    /**
     * Ambil data gudang pusat (by kode GDG-UTM)
     */
    private function getGudang()
    {
        return Cabang::where('slug', 'gudangpusat')
            ->orWhere('jenis', 'pusat')
            ->orWhere('kode', 'GDG-UTM')
            ->firstOrFail();
    }

    /* ============================================================
       1. DAFTAR BAHAN GUDANG PUSAT (ambil semua bahan + stok gudang)
       ============================================================ */
    public function barang()
    {
        $gudang = $this->getGudang();

        // Ambil semua bahan + data stok khusus gudang (left join supaya semua bahan muncul).
        $datas = MBahanBakus::leftJoin('stok_bahan_bakus', function ($join) use ($gudang) {
                $join->on('bahanbakus.id', '=', 'stok_bahan_bakus.bahanbaku_id');
                $join->where('stok_bahan_bakus.cabang_id', $gudang->id);
            })
            ->leftJoin('kategories', 'kategories.id', '=', 'bahanbakus.kategori_id')
            ->select(
                'bahanbakus.*',
                'kategories.Nama_Kategori as nama_kategori',
                'stok_bahan_bakus.id as stok_id',
                \DB::raw('COALESCE(stok_bahan_bakus.banyak_stok, 0) as banyak_stok'),
                'stok_bahan_bakus.satuan as satuan_stok'
            )
            ->orderByDesc('banyak_stok')
            ->get();

        // Kategori dipakai di modal tambah/edit
        $kategori = \App\Models\MKategories::all();

        return view('admin.inventaris.gudangpusat.barang', [
            'title'   => 'Data Barang Gudang Pusat',
            'gudang'  => $gudang,
            'datas'   => $datas,
            'kategori'=> $kategori,
        ]);
    }

    /* ============================================================
       TAMBAH BAHAN + STOK AWAL (untuk gudang pusat)
       ============================================================ */
    public function storeBarang(Request $req)
    {
        $req->validate([
            'kategori_id' => 'nullable|integer',
            'nama_bahan'  => 'required|string',
            'harga'       => 'nullable|numeric',
            'satuan'      => 'required|string',
            'stok'        => 'required|numeric|min:0',
            'batas_stok'  => 'nullable|numeric',
            'keterangan'  => 'nullable|string',
        ]);

        $gudang = $this->getGudang();

        // simpan bahan (master)
        $bahan = MBahanBakus::create([
            'kategori_id' => $req->kategori_id ?? null,
            'nama_bahan'  => $req->nama_bahan,
            'harga'       => $req->harga ?? 0,
            'satuan'      => $req->satuan,
            'batas_stok'  => $req->batas_stok ?? 0,
            'keterangan'  => $req->keterangan ?? '',
            'hitung_luas' => 0
        ]);

        // simpan stok awal ke gudang
        MStokBahanBakus::create([
            'bahanbaku_id' => $bahan->id,
            'cabang_id'    => $gudang->id,
            'banyak_stok'  => $req->stok,
            'satuan'       => $req->satuan,
        ]);

        return back()->with('success', 'Bahan & stok berhasil ditambahkan!');
    }

    /* ============================================================
       UPDATE BAHAN / STOK — menerima $id = stok_id (baris stok)
       ============================================================ */
    public function updateBarang(Request $req, $id)
    {
        $req->validate([
            'kategori_id' => 'nullable|integer',
            'nama_bahan'  => 'required|string',
            'harga'       => 'nullable|numeric',
            'satuan'      => 'required|string',
            'stok'        => 'required|numeric|min:0',
            'batas_stok'  => 'nullable|numeric',
            'keterangan'  => 'nullable|string',
        ]);

        // $id adalah stok_id (baris di stok_bahan_bakus)
        $stok = MStokBahanBakus::findOrFail($id);

        // update bahan induk
        $bahan = MBahanBakus::findOrFail($stok->bahanbaku_id);
        $bahan->update([
            'kategori_id' => $req->kategori_id ?? $bahan->kategori_id,
            'nama_bahan'  => $req->nama_bahan,
            'harga'       => $req->harga ?? $bahan->harga,
            'satuan'      => $req->satuan,
            'batas_stok'  => $req->batas_stok ?? $bahan->batas_stok,
            'keterangan'  => $req->keterangan ?? $bahan->keterangan,
        ]);

        // update stok untuk gudang
        $stok->banyak_stok = $req->stok;
        $stok->satuan = $req->satuan;
        $stok->save();

        return back()->with('success', 'Data bahan berhasil diperbarui!');
    }

    /* ============================================================
       HAPUS BAHAN (hapus baris stok di gudang saja)
       ============================================================ */
    public function destroyBarang($id)
    {
        // $id adalah stok_id
        $stok = MStokBahanBakus::findOrFail($id);
        $stok->delete();

        return back()->with('success', 'Bahan (stok di gudang) berhasil dihapus!');
    }

    /* ============================================================
       2. CRUD STOK GUDANG PUSAT (halaman stok)
       ============================================================ */
    public function stok()
    {
        $gudang = $this->getGudang();

        // semua stok gudang
        $datas = MStokBahanBakus::with('bahanbaku')
                    ->where('cabang_id', $gudang->id)
                    ->orderByDesc('banyak_stok')
                    ->get();

        // semua daftar bahan baku
        $barangs = MBahanBakus::orderBy('nama_bahan')->get();

        return view('admin.inventaris.gudangpusat.stok', [
            'gudang'  => $gudang,
            'datas'   => $datas,
            'barangs' => $barangs  
        ]);
    }


    public function tambahStok(Request $req)
    {
        $req->validate([
            'id_bahan' => 'required|integer',
            'stok'     => 'required|numeric|min:0',
        ]);

        $stok = MStokBahanBakus::findOrFail($req->id_bahan);
        $stok->banyak_stok += $req->stok;
        $stok->save();

        return back()->with('success', 'Stok berhasil ditambahkan!');
    }

    public function updateStok(Request $req, $id)
    {
        $req->validate(['stok' => 'required|numeric|min:0']);

        $stok = MStokBahanBakus::findOrFail($id);
        $stok->banyak_stok = $req->stok;
        $stok->save();

        return back()->with('success', 'Stok berhasil diperbarui!');
    }

    public function deleteStok($id)
    {
        MStokBahanBakus::findOrFail($id)->delete();
        return back()->with('success', 'Data stok berhasil dihapus!');
    }

    /* ============================================================
       3. PENGIRIMAN BAHAN KE CABANG (index + store + update status + destroy)
       ============================================================ */
    public function index()
    {
        $gudang = $this->getGudang();

        $barangs = MStokBahanBakus::with('bahanbaku')
                    ->where('cabang_id', $gudang->id)
                    ->get();

        $cabangs = Cabang::where('jenis', 'cabang')->get();

        $pengiriman = MPengiriman::with(['stok.bahanbaku'])
                        ->where('id_gudang', $gudang->id)
                        ->orderBy('id_pengiriman', 'DESC')
                        ->get();

        return view('admin.inventaris.gudangpusat.pengiriman', [
            'gudang'     => $gudang,
            'barangs'    => $barangs,
            'cabangs'    => $cabangs,
            'pengiriman' => $pengiriman,
        ]);
    }

    public function store(Request $req)
    {
        $req->validate([
            'id_stok'   => 'required',
            'jumlah'    => 'required|numeric|min:1',
            'tujuan'    => 'required',
            'tanggal'   => 'required|date',
        ]);

        $gudang = $this->getGudang();

        $stokGudang = MStokBahanBakus::where('id', $req->id_stok)
                        ->where('cabang_id', $gudang->id)
                        ->first();

        if (!$stokGudang || $stokGudang->banyak_stok < $req->jumlah) {
            return back()->with('error', 'Stok gudang tidak mencukupi!');
        }

        $stokGudang->banyak_stok -= $req->jumlah;
        $stokGudang->save();

        MPengiriman::create([
            'id_gudang' => $gudang->id,
            'id_stok'   => $req->id_stok,
            'jumlah'    => $req->jumlah,
            'tujuan_pengiriman'  => $req->tujuan,
            'tanggal_pengiriman' => $req->tanggal,
            'status_pengiriman'  => 'Dikemas',
        ]);

        return back()->with('success', 'Pengiriman berhasil ditambahkan!');
    }

    public function updateStatus(Request $req, $id)
    {
        $req->validate(['status_pengiriman' => 'required|in:Dikemas,Dikirim']);

        $pengiriman = MPengiriman::findOrFail($id);
        $pengiriman->status_pengiriman = $req->status_pengiriman;
        $pengiriman->save();

        return back()->with('success', 'Status pengiriman diperbarui!');
    }

    public function destroy($id)
    {
        $pengiriman = MPengiriman::findOrFail($id);

        if ($pengiriman->status_pengiriman !== 'Dikemas') {
            return back()->with('error', 'Hanya pengiriman yang masih Dikemas dapat dihapus!');
        }

        $gudang = $this->getGudang();

        $stokGudang = MStokBahanBakus::where('id', $pengiriman->id_stok)
                        ->where('cabang_id', $gudang->id)
                        ->first();

        if ($stokGudang) {
            $stokGudang->banyak_stok += $pengiriman->jumlah;
            $stokGudang->save();
        }

        $pengiriman->delete();

        return back()->with('success', 'Pengiriman berhasil dihapus!');
    }
}
