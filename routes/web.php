<?php

use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/login', [LoginController::class, 'halaman_login'])->name('login');
Route::post('/masuk', [LoginController::class, 'login'])->name('login_masuk');
Route::get('/', function () {
    return view('public.halaman_home');
})->name('home');
Route::get('/tentang', function (){
    return view('public.halaman_tentang');
})->name('tentang');
Route::get('/mapel', [MapelController::class, 'tampilkan_semua_mapel'])->name('mapel');
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);
Route::get('/register', [\App\Http\Controllers\RegisterController::class, 'halaman_register'])->name('register');
Route::post('/register/akun', [\App\Http\Controllers\RegisterController::class, 'register'])->name('register.submit');


Route::get('/user/home', [UserController::class, 'halaman_user'])->name('user.home')->middleware('auth');
Route::post('/auuuuuuu', function (){
    Auth::logout();
    return redirect('/'); // Kembali ke halaman depan
})->name('logout')->middleware('auth');
