<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Kredensial admin sederhana (untuk demo). Ganti dengan DB/auth resmi di produksi.
        if ($request->username === 'admin' && $request->password === 'admin123') {
            session(['admin' => true, 'admin_name' => 'Administrator']);
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['username' => 'Username atau password salah.']);
    }

    public function logout()
    {
        session()->forget(['admin', 'admin_name']);
        return redirect()->route('ppdb.index');
    }
}
