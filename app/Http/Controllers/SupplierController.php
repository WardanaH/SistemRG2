<?php

namespace App\Http\Controllers;

use App\Models\Suppliers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    public function index()
    {
        return view('admin.supplier.index');
    }

    // ✅ Ambil semua data supplier (tanpa DataTables)
    public function loadsupplier()
    {
        $suppliers = Suppliers::latest()->get();
        return response()->json(['data' => $suppliers]);
    }

    // ✅ Simpan supplier baru
    public function store(Request $request)
    {
        $rules = [
            'tambah_nama_supplier' => 'required',
            'tambah_pemilik_supplier' => 'required',
            'tambah_telpon_supplier' => 'required|numeric',
            'tambah_alamat_supplier' => 'required',
            'tambah_keterangan_suppliers' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()]);
        }

        $supplier = Suppliers::create([
            'nama_supplier' => $request->tambah_nama_supplier,
            'pemilik_supplier' => $request->tambah_pemilik_supplier,
            'telpon_supplier' => $request->tambah_telpon_supplier,
            'email_supplier' => $request->tambah_email_supplier,
            'alamat_supplier' => $request->tambah_alamat_supplier,
            'rekening_suppliers' => $request->tambah_rekening_suppliers,
            'keterangan_suppliers' => $request->tambah_keterangan_suppliers,
            'user_id' => Auth::id(),
        ]);

        return $supplier ? response()->json("Success") : response()->json("Failed");
    }

    // ✅ Update data supplier
    public function update(Request $request)
    {
        $rules = [
            'edit_nama_supplier' => 'required',
            'edit_pemilik_supplier' => 'required',
            'edit_telpon_supplier' => 'required|numeric',
            'edit_alamat_supplier' => 'required',
            'edit_keterangan_suppliers' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()]);
        }

        $supplier = Suppliers::findOrFail($request->supplier_id);
        $supplier->update([
            'nama_supplier' => $request->edit_nama_supplier,
            'pemilik_supplier' => $request->edit_pemilik_supplier,
            'telpon_supplier' => $request->edit_telpon_supplier,
            'email_supplier' => $request->edit_email_supplier,
            'alamat_supplier' => $request->edit_alamat_supplier,
            'rekening_suppliers' => $request->edit_rekening_suppliers,
            'keterangan_suppliers' => $request->edit_keterangan_suppliers,
        ]);

        return response()->json("Success");
    }

    // ✅ Hapus data supplier
    public function destroy(Request $request)
    {
        $supplier = Suppliers::findOrFail(decrypt($request->hapus_supplier_id));
        $supplier->delete();

        return response()->json("Success");
    }
}
