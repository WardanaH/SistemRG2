<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cabang;
use App\Models\MBahanBakus;
use App\Models\MStokBahanBakus;
use App\Models\MPengirimanGudang;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GudangPusatController extends Controller
{
    /**
     * Ambil data gudang pusat (by kode GDG-UTM)
     */
    private function getGudang()
    {
        return Cabang::where('kode', 'GDG-UTM')->firstOrFail();
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
        $kategori = \App\Models\MKategories::select(
            'id',
            \DB::raw('Nama_Kategori as nama_kategori')
        )->orderBy('Nama_Kategori')->get();

        return view('admin.inventaris.gudangpusat.barang', [
            'title'   => 'Data Barang Gudang Pusat',
            'gudang'  => $gudang,
            'datas'   => $datas,
            'kategori'=> $kategori,
        ]);
    }

    //tambah bahan
public function storeBarang(Request $request)
{
    $gudang = $this->getGudang(); // GDG-UTM

    $validated = $request->validate([
        'kategori_id' => 'required|integer',
        'nama_bahan'  => 'required|string|max:255|unique:bahanbakus,nama_bahan',
        'harga'       => 'required|numeric|min:0',
        'satuan'      => 'required|string|max:50',
        'batas_stok'  => 'nullable|numeric|min:0',
        'keterangan'  => 'nullable|string',
        'stok'        => 'required|numeric|min:0'
    ]);

    // 1️⃣ SIMPAN KE MASTER BAHAN
    $bahan = \App\Models\MBahanBakus::create([
        'kategori_id' => $validated['kategori_id'],
        'nama_bahan'  => $validated['nama_bahan'],
        'harga'       => $validated['harga'],
        'satuan'      => $validated['satuan'],
        'batas_stok'  => $validated['batas_stok'] ?? 0,
        'keterangan'  => $validated['keterangan'] ?? '',
        'hitung_luas' => in_array(strtoupper($validated['satuan']),['CENTIMETER', 'METER'])
    ]);

    // 2️⃣ BUAT STOK KE SEMUA CABANG
    foreach (Cabang::all() as $cabang) {
        \DB::table('stok_bahan_bakus')->insert([
            'bahanbaku_id' => $bahan->id,
            'cabang_id'    => $cabang->id,
            'banyak_stok'  => $cabang->id == $gudang->id
                                ? $validated['stok']
                                : 0,
            'satuan'       => $validated['satuan'],
        ]);
    }

    return back()->with('success', 'Bahan baku berhasil ditambahkan.');
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

            // Ambil stok + join ke tabel bahan baku
            $datas = MStokBahanBakus::leftJoin('bahanbakus', 'bahanbakus.id', '=', 'stok_bahan_bakus.bahanbaku_id')
                ->where('stok_bahan_bakus.cabang_id', $gudang->id)
                ->select(
                    'stok_bahan_bakus.id as stok_id',
                    'stok_bahan_bakus.banyak_stok',
                    'stok_bahan_bakus.satuan as satuan_stok',
                    'bahanbakus.nama_bahan'
                )
                ->orderBy('bahanbakus.nama_bahan', 'ASC')
                ->get();

            // Semua daftar bahan untuk modal tambah
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
                'bahanbaku_id' => 'required|exists:bahanbakus,id',
                'banyak_stok'  => 'required|numeric|min:0',
            ]);

            $gudang = $this->getGudang();

            // ambil data bahan (untuk satuan asli)
            $bahan = MBahanBakus::findOrFail($req->bahanbaku_id);

            // tambah atau buat stok gudang
            MStokBahanBakus::updateOrCreate(
                [
                    'bahanbaku_id' => $bahan->id,
                    'cabang_id'    => $gudang->id,
                ],
                [
                    'banyak_stok' => \DB::raw('COALESCE(banyak_stok,0) + '.$req->banyak_stok),
                    'satuan'      => $bahan->satuan,
                ]
            );

            return back()->with('success', 'Stok gudang berhasil disimpan!');
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
    3. PENGIRIMAN BAHAN KE CABANG (FULL STATUS + AUTO STOK TERIMA)
    ============================================================ */

    public function index()
    {
        $gudang = $this->getGudang();

        $barangs = MStokBahanBakus::with('bahanbaku')
            ->where('cabang_id', $gudang->id)
            ->get();

        $cabangs = Cabang::where('jenis', 'cabang')->get();

        $pengiriman = \App\Models\MPengirimanGudang::with([
            'bahanbaku',
            'cabangAsal',
            'cabangTujuan',
            'user'
        ])
        ->where('cabang_asal_id', $gudang->id)
        ->latest()
        ->get();

        return view('admin.inventaris.gudangpusat.pengiriman', [
            'gudang'     => $gudang,
            'barangs'    => $barangs,
            'cabangs'    => $cabangs,
            'pengiriman' => $pengiriman,
        ]);
    }

    /* ============================
    SIMPAN PENGIRIMAN BARU
    ============================ */
    public function store(Request $req)
    {
        $req->validate([
            'id_stok'   => 'required',
            'jumlah'    => 'required|numeric|min:1',
            'tujuan'    => 'required|exists:cabangs,id',
            'tanggal'   => 'required|date',
        ]);

        $gudang = $this->getGudang();

        $stokGudang = MStokBahanBakus::where('id', $req->id_stok)
            ->where('cabang_id', $gudang->id)
            ->firstOrFail();

        if ($stokGudang->banyak_stok < $req->jumlah) {
            return back()->with('error', 'Stok gudang tidak mencukupi!');
        }

        // KURANGI STOK GUDANG
        $stokGudang->banyak_stok -= $req->jumlah;
        $stokGudang->save();

        // SIMPAN PENGIRIMAN
        \App\Models\MPengirimanGudang::create([
            'cabang_asal_id'   => $gudang->id,
            'cabang_tujuan_id'=> $req->tujuan,
            'bahanbaku_id'    => $stokGudang->bahanbaku_id,
            'jumlah'          => $req->jumlah,
            'satuan'          => $stokGudang->satuan,
            'tujuan_pengiriman'=> $req->tujuan,
            'tanggal_pengiriman'=> $req->tanggal,
            'status_pengiriman'=> 'Dikemas',
            'user_id'         => auth()->id()
        ]);

        return back()->with('success', 'Pengiriman berhasil dibuat!');
    }

/* ============================
UPDATE STATUS (DIKEMAS → DIKIRIM)
DITERIMA HANYA DARI CABANG
============================ */
public function updateStatus(Request $req, $id)
{
    // ✅ GUDANG HANYA BOLEH UBAH KE DIKIRIM
    $req->validate([
        'status_pengiriman' => 'required|in:Dikemas,Dikirim'
    ]);

    $pengiriman = \App\Models\MPengirimanGudang::findOrFail($id);

    // ❌ JIKA SUDAH DITERIMA, TIDAK BOLEH DIUBAH LAGI
    if ($pengiriman->status_pengiriman === 'Diterima') {
        return back()->with('error', 'Pengiriman ini sudah DITERIMA cabang dan tidak bisa diubah lagi!');
    }

    // ✅ UPDATE STATUS DARI GUDANG
    $pengiriman->status_pengiriman = $req->status_pengiriman;

    // ✅ JIKA BERUBAH KE DIKIRIM
    if ($req->status_pengiriman === 'Dikirim') {
        $pengiriman->tanggal_pengiriman = now();
    }

    $pengiriman->save();

    return back()->with('success', 'Status pengiriman berhasil diperbarui!');
}

    /* ============================
    HAPUS PENGIRIMAN (HANYA SAAT DIKEMAS)
    ============================ */
    public function destroy($id)
    {
        $pengiriman = \App\Models\MPengirimanGudang::findOrFail($id);

        if ($pengiriman->status_pengiriman !== 'Dikemas') {
            return back()->with('error', 'Pengiriman hanya bisa dibatalkan saat masih Dikemas!');
        }

        // KEMBALIKAN STOK KE GUDANG
        $stokGudang = MStokBahanBakus::where('bahanbaku_id', $pengiriman->bahanbaku_id)
            ->where('cabang_id', $pengiriman->cabang_asal_id)
            ->first();

        if ($stokGudang) {
            $stokGudang->banyak_stok += $pengiriman->jumlah;
            $stokGudang->save();
        }

        $pengiriman->delete();

        return back()->with('success', 'Pengiriman berhasil dibatalkan!');
    }

    //DASHBOARD
    public function dashboard()
    {
        $today = Carbon::today();
        $gudang = $this->getGudang();

        // ===== CARD =====
        $totalBahan = \App\Models\MBahanBakus::count();

        $pengirimanHariIni = \App\Models\MPengirimanGudang::where('cabang_asal_id', $gudang->id)
            ->whereDate('created_at', Carbon::today())
            ->count();

        $stokMasukHariIni = \App\Models\MStokBahanBakus::where('cabang_id', $gudang->id)
            ->whereDate('created_at', Carbon::today())
            ->sum('banyak_stok');

        // ===== PIE CHART: Pengiriman per Cabang (bulan ini) =====
        $pengirimanPerCabang = \App\Models\MPengirimanGudang::select(
                'cabangs.nama as nama_cabang',
                DB::raw('COUNT(pengiriman_gudangs.id) as total')
            )
            ->join('cabangs', 'cabangs.id', '=', 'pengiriman_gudangs.cabang_tujuan_id')
            ->where('pengiriman_gudangs.cabang_asal_id', $gudang->id)
            ->whereMonth('pengiriman_gudangs.created_at', Carbon::now()->month)
            ->whereYear('pengiriman_gudangs.created_at', Carbon::now()->year)
            ->groupBy('cabangs.nama')
            ->get();

        // stok kritis
        $stokKritis = DB::table('bahanbakus')
            ->join('cabangs', 'cabangs.jenis', '=', DB::raw("'cabang'"))
            ->leftJoin('stok_bahan_bakus', function ($join) {
                $join->on('stok_bahan_bakus.bahanbaku_id', '=', 'bahanbakus.id')
                    ->on('stok_bahan_bakus.cabang_id', '=', 'cabangs.id');
            })
            ->select(
                'cabangs.nama as nama_cabang',
                'bahanbakus.nama_bahan',
                DB::raw('COALESCE(stok_bahan_bakus.banyak_stok, 0) as banyak_stok'),
                'bahanbakus.batas_stok'
            )
            ->where(function ($q) {
                $q->whereRaw('COALESCE(stok_bahan_bakus.banyak_stok, 0) = 0')
                ->orWhereRaw('COALESCE(stok_bahan_bakus.banyak_stok, 0) <= bahanbakus.batas_stok');
            })
            ->orderBy('banyak_stok', 'asc')
            ->paginate(5); 

        return view('admin.inventaris.gudangpusat.dashboard', compact(
            'totalBahan',
            'pengirimanHariIni',
            'stokMasukHariIni',
            'pengirimanPerCabang',
            'stokKritis'
        ));
    }

}
