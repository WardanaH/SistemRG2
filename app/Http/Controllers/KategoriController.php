<?php

namespace App\Http\Controllers;

use App\Models\MKategories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    public function index()
    {
        return view('admin.kategori.index');
    }

    public function loadkategori()
    {
        $kategories = MKategories::latest()->get();
        return response()->json(['data' => $kategories]);
    }

    public function store(Request $request)
    {
        $rules = [
            'tambah_nama_kategori' => 'required|string|max:128',
            'tambah_keterangan'    => 'required|string',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()]);
        }

        $kategori = MKategories::create([
            'Nama_Kategori' => $request->tambah_nama_kategori,
            'Keterangan'    => $request->tambah_keterangan,
            'user_id'       => Auth::id(),
        ]);

        $isi = auth()->user()->username . " telah menambahkan kategori " . $kategori->Nama_Kategori . ".";
        $this->log($isi, "Penambahan");

        return $kategori ? response()->json("Success") : response()->json("Failed");
    }

    public function update(Request $request)
    {
        try {
            $rules = [
                'edit_nama_kategori' => 'required|string|max:128',
                'edit_keterangan'    => 'required|string',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->getMessageBag()]);
            }

            // Perbaikan di sini
            $category = MKategories::findOrFail($request->edit_kategori_id);

            $category->update([
                'Nama_Kategori' => $request->edit_nama_kategori,
                'Keterangan'    => $request->edit_keterangan,
            ]);

            $isi = auth()->user()->username . " telah mengubah kategori " . $category->Nama_Kategori . ".";
            $this->log($isi, "Pengubahan");

            return response()->json("Success");
        } catch (\Throwable $th) {
            return response()->json(['errors' => ['Gagal mengupdate kategori: ' . $th->getMessage()]]);
        }
    }

    public function destroy(Request $request)
    {
        $kategori = MKategories::findOrFail($request->hapus_kategori_id);
        $kategori->delete();

        $isi = auth()->user()->username . " telah menghapus kategori " . $kategori->Nama_Kategori . ".";
        $this->log($isi, "Penghapusan");

        return response()->json("Success");
    }
}
