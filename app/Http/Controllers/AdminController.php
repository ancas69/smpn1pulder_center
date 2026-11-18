<?php

namespace App\Http\Controllers;

use App\Models\User;


class AdminController extends Controller
{
    public function halaman_home(){
        return view('admin.home');
    }

    public function halaman_pengguna(){
        return view('admin.pengguna');
    }

    public function tampilkan_semua_pengguna(){
        return response()->json(User::all());
    }
}
