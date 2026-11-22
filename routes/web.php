<?php

use App\Http\Controllers\AdminController;
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
Route::get('/unlock-account/{token}', [LoginController::class, 'unlockAccount'])->name('unlock.account');

Route::get('/user/home', [UserController::class, 'halaman_home'])->name('user.home')->middleware('auth');

Route::get('/admin/home', [AdminController::class, 'halaman_home'])->name('admin.home')->middleware('auth');
Route::get('/admin/pengguna', [AdminController::class, 'halaman_pengguna'])->name('admin.pengguna')->middleware('auth');




Route::post('/auuuuuuu', function (){
    Auth::logout();
    return redirect('/'); // Kembali ke halaman depan
})->name('logout')->middleware('auth');



Route::post('/allUsers', [AdminController::class, 'tampilkan_semua_pengguna'])->name('admin.allUsers')->middleware('auth');
Route::post('/api/murid', [AdminController::class, 'tampilkan_murid'])->name('admin.murid')->middleware('auth');

Route::post('/api/pengguna/store', [AdminController::class, 'tambah_satu_pengguna'])->name('admin.pengguna.store')->middleware('auth');
Route::post('/api/pengguna/delete/{id}', [AdminController::class, 'hapus_satu_user'])->name('admin.pengguna.delete')->middleware('auth');
Route::post('/api/pengguna/update/{id}', [AdminController::class, 'ubah_satu_data_pengguna'])->name('admin.pengguna.update')->middleware('auth');
