<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class CabangController extends Controller
{
    public function index()
    {
        $cabangs = Cabang::paginate(15);
        return view('admin.cabangs.index', compact('cabangs'));
    }

    public function create()
    {
        return view('admin.cabangs.create');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'kode' => 'required|string|max:20|unique:cabangs',
                'nama' => 'required|string|max:255',
                'slug' => str_replace(' ', '-', strtolower($request->input('nama'))),
                'email' => 'nullable|email',
                'telepon' => 'nullable|string|max:20',
                'alamat' => 'nullable|string',
                'jenis' => 'required|in:pusat,cabang',
            ]);

            Cabang::create($request->all());

            $isi = auth()->user()->username . " telah menambahkan cabang baru." . $request->input('nama');
            $this->log($isi, "Penambahan");

            return redirect()->route('cabangs.index')->with('success', 'Cabang berhasil dibuat.');
        } catch (\Exception $e) {
            Log::error('Gagal membuat cabang: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal membuat cabang!');
        }
    }

    public function edit(Cabang $cabang)
    {
        return view('admin.cabangs.edit', compact('cabang'));
    }

    public function update(Request $request, Cabang $cabang)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:20|unique:cabangs,kode,' . $cabang->id,
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        $cabang->update($validated);

        $isi = auth()->user()->username . " telah mengedit cabang " . $cabang->nama . ".";
        $this->log($isi, "Pengubahan");

        return redirect()
            ->route('cabangs.index')
            ->with('success', 'Cabang berhasil diperbarui.');
    }

    public function destroy(Cabang $cabang)
    {
        $isi = auth()->user()->username . " telah menghapus cabang " . $cabang->nama . ".";
        $this->log($isi, "Penghapusan");

        $cabang->delete();

        return redirect()->route('cabangs.index')->with('success', 'Cabang dihapus.');
    }
}
