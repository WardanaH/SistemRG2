<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MJenisPelanggan;

class JenisPelanggansController extends Controller
{
    public function index()
    {
        $jenispelanggans = MJenisPelanggan::latest()->get();
        return view('admin.jenispelanggan.index', compact('jenispelanggans'));
    }

    /**
     * Simpan data jenis pelanggan baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tambah_jenispelanggan' => 'required|string|max:255',
        ]);

        $data = MJenisPelanggan::create([
            'jenis_pelanggan' => $validated['tambah_jenispelanggan'],
        ]);

        // (Opsional) logging aktivitas
        // if (method_exists($this, 'createlog') && Auth::check()) {
        //     $isi = Auth::user()->username . " menambahkan jenis pelanggan {$data->jenis_pelanggan}.";
        //     $this->createlog($isi, "add");
        // }

        return redirect()
            ->route('jenispelanggan.index')
            ->with('success', 'Jenis pelanggan berhasil ditambahkan.');
    }

    /**
     * Update data jenis pelanggan.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'jenispelanggan_id' => 'required',
            'edit_jenispelanggan' => 'required|string|max:255',
        ]);

        try {
            $id = decrypt($validated['jenispelanggan_id']);
        } catch (\Exception $e) {
            return redirect()
                ->route('jenispelanggan.index')
                ->withErrors('ID tidak valid.');
        }

        $data = MJenisPelanggan::findOrFail($id);
        $data->update([
            'jenis_pelanggan' => $validated['edit_jenispelanggan'],
        ]);

        // if (method_exists($this, 'createlog') && Auth::check()) {
        //     $isi = Auth::user()->username . " mengubah jenis pelanggan {$data->jenis_pelanggan}.";
        //     $this->createlog($isi, "edit");
        // }

        return redirect()
            ->route('jenispelanggan.index')
            ->with('success', 'Jenis pelanggan berhasil diperbarui.');
    }

    /**
     * Hapus data jenis pelanggan.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'hapus_jenispelanggan_id' => 'required',
        ]);

        try {
            $id = decrypt($request->hapus_jenispelanggan_id);
        } catch (\Exception $e) {
            return redirect()
                ->route('jenispelanggan.index')
                ->withErrors('ID tidak valid.');
        }

        $data = MJenisPelanggan::findOrFail($id);
        $nama = $data->jenis_pelanggan;
        $data->delete();

        // if (method_exists($this, 'createlog') && Auth::check()) {
        //     $isi = Auth::user()->username . " menghapus jenis pelanggan {$nama}.";
        //     $this->createlog($isi, "delete");
        // }

        return redirect()
            ->route('jenispelanggan.index')
            ->with('success', 'Jenis pelanggan berhasil dihapus.');
    }
}
