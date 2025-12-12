<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DesignerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua user dengan role 'designer' + jumlah transaksi mereka
        $designers = User::role('designer')
            ->withCount('transaksi_desain') // menghitung jumlah transaksi per designer
            ->with('cabang')
            ->get();
        // dd($designers);

        return view('admin.users.designer.index', compact('designers'));
    }

    public function dashboard()
    {
        return view('designer.dashboard');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
