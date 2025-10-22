<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Cabang;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    public function index()
    {
        // ambil data yang diperlukan untuk tabel + form tambah user di halaman index
        $users = User::with('cabang', 'roles')->paginate(15);
        $roles = Role::all();         // <-- pastikan ini ada
        $cabangs = Cabang::all();    // <-- dan ini juga

        return view('admin.users.index', compact('users', 'roles', 'cabangs'));
    }

    public function create()
    {
        // jika kamu menggunakan halaman create terpisah
        $roles = Role::all();
        $cabangs = Cabang::all();
        return view('admin.users.create', compact('roles', 'cabangs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username',
            'email' => 'nullable|email|unique:users,email',
            'password' => 'required|string|min:6',
            'telepon' => 'nullable|string',
            'gaji' => 'nullable|numeric',
            'alamat' => 'nullable|string',
            'cabang_id' => 'nullable|exists:cabangs,id',
            'role' => 'required|string|exists:roles,name'
        ]);

        $user = User::create([
            'nama' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'] ?? null,
            'password' => Hash::make($validated['password']),
            'telepon' => $validated['telepon'] ?? null,
            'gaji' => $validated['gaji'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'cabang_id' => $validated['cabang_id'] ?? null,
        ]);

        $user->assignRole($validated['role']);

        return redirect()->route('users.index')->with('success', 'User berhasil dibuat.');
    }

    public function edit(User $user)
    {
        $roles = Role::pluck('name', 'name');
        $cabangs = \App\Models\Cabang::pluck('nama', 'id');
        return view('admin.users.edit', compact('user', 'roles', 'cabangs'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        if (isset($data['roles'])) {
            $user->syncRoles($data['roles']);
        }

        return redirect()->route('users.index')->with('success', 'User diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User dihapus.');
    }
}
