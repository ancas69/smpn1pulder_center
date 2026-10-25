<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Menampilkan form login
    public function halaman_login()
    {
        return view('auth.login.halaman_login');
    }

    // Memproses login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Coba login
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // $request->session()->regenerate(); // Keamanan: cegah session fixation
            // return redirect()->intended(route('user.home')); // Redirect ke halaman tujuan

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil!',
                'redirect_url' => route('user.home'),
            ]);
        }else {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah!',
            ]);
        }

        // // Jika gagal
        // throw ValidationException::withMessages([
        //     'email' => __('auth.failed'),
        // ]);
    }


}
