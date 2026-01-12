<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Cek role user dan redirect sesuai peran
            if ($user->hasRole('operator indoor') || $user->hasRole('operator outdoor') || $user->hasRole('operator multi')) {
                $isi = Auth::user()->username . " telah login dicabang " . Auth::user()->cabang->nama . ".";
                $save = $this->log($isi, "Login");
                return redirect()->route('operator.dashboard')->with('success', 'Selamat datang kembali!');
            } elseif ($user->hasRole('designer')) {
                $isi = Auth::user()->username . " telah login dicabang " . Auth::user()->cabang->nama . ".";
                $save = $this->log($isi, "Login");
                return redirect()->route('designer.dashboard')->with('success', 'Selamat datang kembali!');
            } elseif ($user->hasRole('adversting')) {
                $isi = Auth::user()->username . " telah login dicabang " . Auth::user()->cabang->nama . ".";
                $save = $this->log($isi, "Login");
                return redirect()->route('adversting.index')->with('success', 'Selamat datang kembali!');
            } elseif ($user->hasRole('documentation')) {
                $isi = Auth::user()->username . " telah login dicabang " . Auth::user()->cabang->nama . ".";
                $save = $this->log($isi, "Login");
                return redirect()->route('companies.index')->with('success', 'Selamat datang kembali!');
            } elseif ($user->hasRole('Inventory Utama')) {
                $isi = Auth::user()->username . " telah login dicabang " . Auth::user()->cabang->nama . ".";
                $save = $this->log($isi, "Login");
                return redirect()->route('gudangpusat.dashboard')->with('success', 'Selamat datang kembali!');
            } elseif ($user->hasRole('Inventory Cabang')) {
                $isi = Auth::user()->username . " telah login dicabang " . Auth::user()->cabang->nama . ".";
                $save = $this->log($isi, "Login");
                return redirect()->route('templateinventaris.dashboard')->with('success', 'Selamat datang kembali!');
            }else {
                $isi = Auth::user()->username . " telah login dicabang " . Auth::user()->cabang->nama . ".";
                $save = $this->log($isi, "Login");
                return redirect()->route('dashboard')->with('success', 'Selamat datang kembali!');
            }
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Berhasil logout.');
    }
}
