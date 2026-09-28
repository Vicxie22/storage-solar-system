<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan form login
    public function showLoginForm()
    {
        if (Auth::check()) {
            // Evaluasi hak akses jika sesi masih aktif
            if (Auth::user()->role === 'Admin') {
                return redirect()->route('solar.index');
            }
            return redirect()->route('solar.stok');
        }
        return view('auth.login');
    }

    // Memproses data login
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        // Cek apakah input berupa email atau username
        $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Coba login
        if (Auth::attempt([$fieldType => $request->username, 'password' => $request->password])) {
            $request->session()->regenerate();

            // Pengalihan berbasis peran (Role-based redirect)
            if (auth()->user()->role === 'Admin') {
                return redirect()->route('solar.index')->with('success', 'Berhasil login sebagai Administrator.');
            }
            
            return redirect()->route('solar.stok')->with('success', 'Berhasil login. Berikut adalah informasi stok saat ini.');
        }

        return back()->withErrors([
            'username' => 'Username/Email atau Password salah.',
        ])->onlyInput('username');
    }

    // Memproses logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }
}