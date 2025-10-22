<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCabangRequest;
use App\Http\Requests\UpdateCabangRequest;
use App\Models\Cabang;

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

    public function store(StoreCabangRequest $request)
    {
        Cabang::create($request->validated());
        return redirect()->route('cabangs.index')->with('success','Cabang berhasil dibuat.');
    }

    public function edit(Cabang $cabang)
    {
        return view('admin.cabangs.edit', compact('cabang'));
    }

    public function update(UpdateCabangRequest $request, Cabang $cabang)
    {
        $cabang->update($request->validated());
        return redirect()->route('admin.cabangs.index')->with('success','Cabang diperbarui.');
    }

    public function destroy(Cabang $cabang)
    {
        $cabang->delete();
        return redirect()->route('cabangs.index')->with('success','Cabang dihapus.');
    }
}
