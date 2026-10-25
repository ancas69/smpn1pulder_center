<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class RegisterController extends Controller
{
    public function halaman_register()
    {
        return view('auth.login.halaman_register');
    }

    public function register(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Buat user baru
        $user = \App\Models\User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
        ]);

        // Redirect ke halaman dashboard/home setelah berhasil register
        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran berhasil!',
            'redirect_url' => route('login'), // Redirect ke halaman login setelah register
        ]);
    }
}
