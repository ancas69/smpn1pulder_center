<?php

namespace App\Http\Controllers;


class AdminController extends Controller
{
    public function halaman_home(){
        return view('admin.home');
    }
}
