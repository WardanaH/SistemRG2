<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $cabang = $user->cabang->nama ?? '-';
        return view('dashboard.index', compact('user', 'cabang'));
    }
}
