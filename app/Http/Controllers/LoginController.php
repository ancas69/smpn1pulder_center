<?php

namespace App\Http\Controllers;

use App\Mail\UnlockAccountMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{

    // Menampilkan form login
    public function halaman_login()
    {
        return view('auth.login.halaman_login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();

        // 1. Cek apakah email terdaftar (Tetap samarkan pesan error agar aman)
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Email atau password salah!'], 401);
        }

        // 2. CEK APAKAH AKUN SEDANG DIBLOKIR
        if ($user->login_attempts >= 3) {
            // Opsional: Cek apakah token blokir sudah kedaluwarsa (misal: lewat 1 jam)
            // Jika lewat 1 jam, biarkan mereka mencoba lagi atau minta kirim ulang email

            return response()->json([
                'success' => false,
                'message' => 'Akun Anda diblokir karena terlalu banyak percobaan gagal. Silakan cek email Anda untuk membuka blokir.',
                'is_locked' => true // Flag untuk frontend
            ], 403);
        }

        // 3. COBA LOGIN
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Jika berhasil, RESET semua status
            $user->update([
                'login_attempts' => 0,
                'unlock_token' => null,
                'blokir' => false,
                'unlock_token_expires_at' => null,
            ]);

            $request->session()->regenerate();

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil!',
                'redirect_url' => route('user.home'),
            ]);
        }

        // 4. JIKA GAGAL: Tambah hitungan
        $user->increment('login_attempts');

        // 5. JIKA MENCAPAI 3 KALI: BLOKIR & KIRIM EMAIL
        if ($user->login_attempts >= 3) {
            // Buat token unik
            $token = Str::random(60);

            $user->update([
                'blokir' => true,
                'unlock_token' => $token,
                'unlock_token_expires_at' => Carbon::now()->addHour(), // Berlaku 1 jam
            ]);

            // Kirim Email (Gunakan try-catch agar jika email gagal, API tidak error 500)
            try {
                Mail::to($user->email)->send(new UnlockAccountMail($user, $token));
            } catch (\Exception $e) {
                // Log error jika email gagal terkirim (misal: konfigurasi SMTP salah)
                Log::error('Gagal kirim email unlock: ' . $e->getMessage());
            }

            return response()->json([
                'success' => false,
                'message' => 'Akun telah diblokir karena 3 kali gagal. Link buka blokir telah dikirim ke email Anda.',
                'is_locked' => true
            ], 403);
        }

        // Jika belum 3 kali
        return response()->json([
            'success' => false,
            'message' => 'Email atau password salah!',
        ], 401);
    }

    // Fungsi untuk memproses klik link dari email
    public function unlockAccount(Request $request, $token)
    {
        $user = User::where('unlock_token', $token)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Token tidak valid.'], 400);
        }

        // Cek kedaluwarsa
        if (Carbon::now()->gt($user->unlock_token_expires_at)) {
            return response()->json(['success' => false, 'message' => 'Token sudah kedaluwarsa. Silakan coba login kembali.'], 400);
        }

        // Buka blokir
        $user->update([
            'login_attempts' => 0,
            'unlock_token' => null,
            'unlock_token_expires_at' => null,
            'blokir' => false,
        ]);

        // Jika ini API, kembalikan JSON. Jika web biasa, redirect ke halaman login.
        return response()->json([
            'success' => true,
            'message' => 'Akun berhasil dibuka blokirnya. Silakan login kembali.'
        ]);
    }


}
