<?php

// namespace App\Http\Controllers\Admin;

// use App\Models\User;
// use App\Models\Cabang;
// use Illuminate\Http\Request;
// use Spatie\Permission\Models\Role;
// use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Hash;
// use App\Http\Requests\StoreUserRequest;
// use App\Http\Requests\UpdateUserRequest;

// class UserController extends Controller
// {
//     public function index()
//     {
//         // ambil data yang diperlukan untuk tabel + form tambah user di halaman index
//         $users = User::with('cabang', 'roles')->paginate(15);
//         $roles = Role::all();         // <-- pastikan ini ada
//         $cabangs = Cabang::all();    // <-- dan ini juga

//         return view('admin.users.index', compact('users', 'roles', 'cabangs'));
//     }

//     public function create()
//     {
//         // jika kamu menggunakan halaman create terpisah
//         $roles = Role::all();
//         $cabangs = Cabang::all();
//         return view('admin.users.create', compact('roles', 'cabangs'));
//     }

//     public function store(Request $request)
//     {
//         $validated = $request->validate([
//             'name' => 'required|string|max:255',
//             'username' => 'required|string|unique:users,username',
//             'email' => 'nullable|email|unique:users,email',
//             'password' => 'required|string|min:6',
//             'telepon' => 'nullable|string',
//             'gaji' => 'nullable|numeric',
//             'alamat' => 'nullable|string',
//             'cabang_id' => 'nullable|exists:cabangs,id',
//             'role' => 'required|string|exists:roles,name'
//         ]);

//         $user = User::create([
//             'nama' => $validated['name'],
//             'username' => $validated['username'],
//             'email' => $validated['email'] ?? null,
//             'password' => Hash::make($validated['password']),
//             'telepon' => $validated['telepon'] ?? null,
//             'gaji' => $validated['gaji'] ?? null,
//             'alamat' => $validated['alamat'] ?? null,
//             'cabang_id' => $validated['cabang_id'] ?? null,
//         ]);

//         $user->assignRole($validated['role']);

//         return redirect()->route('users.index')->with('success', 'User berhasil dibuat.');
//     }

//     public function edit(User $user)
//     {
//         $roles = Role::pluck('name', 'name');
//         $cabangs = \App\Models\Cabang::pluck('nama', 'id');
//         return view('admin.users.edit', compact('user', 'roles', 'cabangs'));
//     }

//     public function update(UpdateUserRequest $request, User $user)
//     {
//         $data = $request->validated();
//         if (!empty($data['password'])) {
//             $data['password'] = Hash::make($data['password']);
//         } else {
//             unset($data['password']);
//         }

//         $user->update($data);

//         if (isset($data['roles'])) {
//             $user->syncRoles($data['roles']);
//         }

//         return redirect()->route('users.index')->with('success', 'User diperbarui.');
//     }

//     public function destroy(User $user)
//     {
//         $user->delete();
//         return redirect()->route('users.index')->with('success', 'User dihapus.');
//     }
// }

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Cabang;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    public function index()
    {
        // Ambil data user beserta relasi cabang & role
        $users = User::with('cabang', 'roles')->paginate(15);
        $roles = Role::all();
        $cabangs = Cabang::all();

        return view('admin.users.index', compact('users', 'roles', 'cabangs'));
    }

    public function create()
    {
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
            'role' => 'required|string|exists:roles,name',
            'nama_cabang' => 'nullable|string|max:100', // tambahan untuk cabang dinamis
        ]);

        // 🔹 LOGIKA CABANG DINAMIS
        $slug = $validated['nama_cabang']
            ? Str::slug($validated['nama_cabang'])
            : 'gudangpusat';

        // Cari cabang yang sesuai
        $cabang = Cabang::where('slug', $slug)->first();

        // Kalau belum ada, buat baru (kecuali cabang utama)
        if (!$cabang && $slug !== 'gudangpusat') {
            $kode = 'CBG-' . strtoupper(substr($slug, 0, 3));
            $cabang = Cabang::create([
                'kode' => $kode,
                'nama' => ucfirst($validated['nama_cabang']),
                'slug' => $slug,
                'alamat' => '-',
            ]);
        }

        // Simpan user baru
        $user = User::create([
            'nama' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'] ?? null,
            'password' => Hash::make($validated['password']),
            'telepon' => $validated['telepon'] ?? null,
            'gaji' => $validated['gaji'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'id_cabang' => $cabang ? $cabang->id : 1, // default ke cabang utama
        ]);

        // Tambahkan role
        $user->assignRole($validated['role']);

        return redirect()->route('users.index')->with('success', 'User berhasil dibuat dan cabang disinkron.');
    }

    public function edit(User $user)
    {
        // dd(auth()->user()->getRoleNames()->first());
        $roles = Role::pluck('name', 'name');
        $cabangs = Cabang::pluck('nama', 'id');
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
        // dd($data);

        // 🔹 Sinkronisasi cabang jika diubah
        if (!empty($data['nama_cabang'])) {
            $slug = Str::slug($data['nama_cabang']);
            $cabang = Cabang::where('slug', $slug)->first();

            if (!$cabang && $slug !== 'gudangpusat') {
                $kode = 'CBG-' . strtoupper(substr($slug, 0, 3));
                $cabang = Cabang::create([
                    'kode' => $kode,
                    'nama' => ucfirst($data['nama_cabang']),
                    'slug' => $slug,
                    'alamat' => '-',
                ]);
            }

            $data['id_cabang'] = $cabang ? $cabang->id : 1;
        }

        $user->update($data);

        if (isset($data['roles'])) {
            $user->syncRoles($data['roles']);
        }

        return redirect()->route('users.index')->with('success', 'User diperbarui.');
    }

    public function destroy(User $user)
    {
        $idCabang = $user->id_cabang;
        $user->delete();

        // 🔹 Hapus cabang jika tidak ada user lain & bukan cabang utama
        if ($idCabang != 1) {
            $stillExists = User::where('id_cabang', $idCabang)->exists();
            if (!$stillExists) {
                $cabang = Cabang::find($idCabang);
                if ($cabang) {
                    $cabang->delete();
                }
            }
        }

        return redirect()->route('users.index')->with('success', 'User dihapus dan cabang disinkron.');
    }
}
