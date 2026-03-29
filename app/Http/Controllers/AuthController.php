<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Coba login
        if (Auth::attempt($credentials)) {
            // 3. Jika berhasil: buat ulang sesi untuk cegah hacking (session fixation)
            $request->session()->regenerate();
            
            // 4. Redirect ke halaman dashboard (atau halaman yang dituju)
            // Ganti '/dashboard' dengan route yang sesuai nanti
            return redirect('/dashboard'); 
        }

        // 4.1 Cek role untuk redirect yang sesuai
        if (auth()->user()->role->name === 'SuperAdmin') {
            return redirect()->route('admin.dashboard');
        } elseif (auth()->user()->role->name === 'User Biasa') {
            return redirect()->route('peminjaman.index');
        }
        

        // 5. Jika gagal: kembalikan user ke halaman login beserta pesan error
        return back()->withErrors([
            'email' => 'Email atau password salah jirr.',
        ])->onlyInput('email'); // onlyInput menjaga email tetap terisi di form
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Redirect kembali ke halaman login setelah logout
        return redirect('/login');
    }
}