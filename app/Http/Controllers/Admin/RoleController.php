<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        // $permissions = Permission::all();
        // Group permission PREFIX -> item (safe)
        $permissions = Permission::all()->groupBy(function ($item) {

            if (!is_string($item->name) || trim($item->name) === '') {
                return 'other';
            }

            $parts = explode('-', $item->name);
            return $parts[0] ?? 'other';
        });

        return view('admin.roles.index', compact('roles', 'permissions'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:roles,name']);
        Role::create(['name' => $request->name]);

        $isi = auth()->user()->username . " telah menambahkan role baru " . $request->name . ".";
        $this->log($isi, "Penambahan");

        return back()->with('success', 'Role berhasil dibuat.');
    }

    public function update(Request $request, Role $role)
    {
        $role->syncPermissions($request->permissions ?? []);

        $isi = auth()->user()->username . " telah mengedit hak akses role " . $role->name . ".";
        $this->log($isi, "Pengubahan");

        return back()->with('success', 'Hak akses diperbarui.');
    }

    public function destroy(Role $role)
    {
        $role->delete();

        $isi = auth()->user()->username . " telah menghapus role " . $role->name . ".";
        $this->log($isi, "Penghapusan");

        return back()->with('success', 'Role dihapus.');
    }
}
