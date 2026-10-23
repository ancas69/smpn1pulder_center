<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return view('auth.login.halaman_login');
})->name('login');

Route::get('/', function () {
    return view('public.halaman_home');
})->name('home');

Route::get('/tentang', function (){
    return view('public.halaman_tentang');
})->name('tentang');
