<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCabangRequest;
use App\Http\Requests\UpdateCabangRequest;
use App\Models\Cabang;
use Illuminate\Http\Request;

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
        Cabang::create($request->validated());
        return redirect()->route('cabangs.index')->with('success', 'Cabang berhasil dibuat.');
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

        return redirect()
            ->route('cabangs.index')
            ->with('success', 'Cabang berhasil diperbarui.');
    }

    public function destroy(Cabang $cabang)
    {
        $cabang->delete();
        return redirect()->route('cabangs.index')->with('success', 'Cabang dihapus.');
    }
}
