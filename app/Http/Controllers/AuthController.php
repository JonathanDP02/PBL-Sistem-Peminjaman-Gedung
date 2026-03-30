<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        //Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        //Coba login
        if (Auth::attempt($credentials)) {
            //Jika berhasil: buat ulang sesi untuk cegah hacking (session fixation)
            $request->session()->regenerate();
            
           // Ambil role user yang baru saja login
            $user = Auth::user();
            if (!$user) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'User ini belum diberikan role. Hubungi admin.',
                ])->onlyInput('email');
            }

            $role = $user->role->name;

            // Redirect berdasarkan Role
            if ($role === 'User') {
                return redirect('/user/peminjaman'); // Pastikan route name ini ada nanti
            }

            // Redirect ke halaman dashboard (atau halaman yang dituju)
            return redirect('/admin/dashboard'); 
        }

        

        //Jika gagal: kembalikan user ke halaman login beserta pesan error
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