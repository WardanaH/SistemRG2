<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cabang;
use App\Models\MCabangBarang;
use App\Models\MPengiriman;

class PengirimanGudangController extends Controller
{
    /**
     * Ambil data gudang pusat sekali, berdasarkan slug 'gudangpusat'
     */
    private function getGudang()
    {
        return Cabang::where('slug', 'gudangpusat')->firstOrFail();
    }

    /**
     * Halaman daftar barang di gudang pusat
     */
    public function barang()
    {
        $gudang  = $this->getGudang();

        $barangs = MCabangBarang::where('id_cabang', $gudang->id)->get();

        return view('admin.inventaris.gudangpusat.barang', [
            'gudang'  => $gudang,
            'barangs' => $barangs,
        ]);
    }

    /**
     * Halaman stok barang di gudang pusat
     */
    public function stok()
    {
        $gudang  = $this->getGudang();

        $barangs = MCabangBarang::where('id_cabang', $gudang->id)->get();

        return view('admin.inventaris.gudangpusat.stok', [
            'gudang'  => $gudang,
            'barangs' => $barangs,
        ]);
    }

    /**
     * Halaman pengiriman dari gudang ke cabang
     */
    public function index()
    {
        $gudang = $this->getGudang();

        // Barang yang ada di gudang pusat
        $barangs = MCabangBarang::where('id_cabang', $gudang->id)->get();

        // Semua cabang tujuan (jenis = cabang)
        $cabangs = Cabang::where('jenis', 'cabang')->get();

        // Riwayat pengiriman dari gudang pusat
        $pengiriman = MPengiriman::with('barang')
            ->where('id_gudang', $gudang->id)
            ->orderBy('id_pengiriman', 'DESC')
            ->get();

        return view('admin.inventaris.gudangpusat.pengiriman', [
            'gudang'      => $gudang,
            'barangs'     => $barangs,
            'cabangs'     => $cabangs,
            'pengiriman'  => $pengiriman,
        ]);
    }

    /**
     * Simpan pengiriman baru
     */
    public function store(Request $req)
    {
        $req->validate([
            'id_barang'          => 'required',
            'jumlah'             => 'required|numeric|min:1',
            'tujuan_pengiriman'  => 'required',
            'tanggal_pengiriman' => 'required|date',
        ]);

        $gudang = $this->getGudang();

        // Cek stok di gudang
        $stokGudang = MCabangBarang::where('id_barang', $req->id_barang)
            ->where('id_cabang', $gudang->id)
            ->first();

        if (!$stokGudang || $stokGudang->stok < $req->jumlah) {
            return back()->with('error', 'Stok gudang tidak mencukupi!');
        }

        // Kurangi stok gudang
        $stokGudang->stok -= $req->jumlah;
        $stokGudang->save();

        // Simpan pengiriman
        MPengiriman::create([
            'id_gudang'          => $gudang->id,
            'id_barang'          => $req->id_barang,
            'jumlah'             => $req->jumlah,
            'tujuan_pengiriman'  => $req->tujuan_pengiriman, // slug cabang
            'tanggal_pengiriman' => $req->tanggal_pengiriman,
            'status_pengiriman'  => 'Dikemas',
        ]);

        return back()->with('success', 'Pengiriman berhasil ditambahkan');
    }

    /**
     * Update status pengiriman (Dikemas → Dikirim)
     */
    public function updateStatus(Request $req, $id)
    {
        $req->validate([
            'status_pengiriman' => 'required|in:Dikemas,Dikirim',
        ]);

        $pengiriman = MPengiriman::findOrFail($id);
        $pengiriman->status_pengiriman = $req->status_pengiriman;
        $pengiriman->save();

        return back()->with('success', 'Status pengiriman diperbarui');
    }

    /**
     * Hapus pengiriman (hanya jika masih Dikemas)
     * dan kembalikan stok ke gudang
     */
    public function destroy($id)
    {
        $pengiriman = MPengiriman::findOrFail($id);

        if ($pengiriman->status_pengiriman !== 'Dikemas') {
            return back()->with('error', 'Hanya pengiriman yang masih Dikemas yang boleh dihapus!');
        }

        $gudang = $this->getGudang();

        // Kembalikan stok gudang
        $stokGudang = MCabangBarang::where('id_barang', $pengiriman->id_barang)
            ->where('id_cabang', $gudang->id)
            ->first();

        if ($stokGudang) {
            $stokGudang->stok += $pengiriman->jumlah;
            $stokGudang->save();
        }

        $pengiriman->delete();

        return back()->with('success', 'Pengiriman berhasil dihapus!');
    }
}
