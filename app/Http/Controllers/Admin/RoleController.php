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
        $permissions = Permission::all();

        return view('admin.roles.index', compact('roles', 'permissions'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:roles,name']);
        Role::create(['name' => $request->name]);

        return back()->with('success', 'Role berhasil dibuat.');
    }

    public function update(Request $request, Role $role)
    {
        $role->syncPermissions($request->permissions ?? []);
        return back()->with('success', 'Hak akses diperbarui.');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return back()->with('success', 'Role dihapus.');
    }
}
