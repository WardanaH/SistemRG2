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

        return $kategori ? response()->json("Success") : response()->json("Failed");
    }

    public function update(Request $request)
    {
        $rules = [
            'edit_nama_kategori' => 'required|string|max:128',
            'edit_keterangan'    => 'required|string',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()]);
        }

        $kategori = MKategories::findOrFail($request->kategori_id);

        $kategori->update([
            'Nama_Kategori' => $request->edit_nama_kategori,
            'Keterangan'    => $request->edit_keterangan,
        ]);

        return response()->json("Success");
    }

    public function destroy(Request $request)
    {
        $kategori = MKategories::findOrFail($request->hapus_kategori_id);
        $kategori->delete();

        return response()->json("Success");
    }
}
