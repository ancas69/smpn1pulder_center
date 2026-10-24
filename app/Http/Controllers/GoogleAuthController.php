<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    // 1. Mengarahkan user ke halaman login Google
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // 2. Menangani balasan (callback) dari Google setelah login
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();



            // Cek apakah user sudah pernah terdaftar berdasarkan google_id atau email
            $user = User::where('google_id', $googleUser->id)
                        ->orWhere('email', $googleUser->email)
                        ->first();

            if ($user) {
                // Jika email sudah ada tapi belum punya google_id, update datanya
                $user->update(['google_id' => $googleUser->id]);
            } else {
                // Jika user baru, buatkan akun baru
                $user = User::create([
                    'name'      => $googleUser->name,
                    'email'     => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password'  => null, // Karena login via Google
                ]);
            }

            // Login-kan user ke dalam aplikasi Laravel
            Auth::login($user);

            // Arahkan ke halaman dashboard/home setelah berhasil
            return redirect(route('user.home'));

        } catch (\Exception $e) {
        //dd($e->getMessage());
        //Jika gagal, kembalikan ke halaman login dengan pesan error
            return redirect('/login')->with('error', 'Gagal login menggunakan Google. Silakan coba lagi.');
        }
    }
}
