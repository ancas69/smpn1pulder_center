<?php

namespace App\Http\Controllers;


class UserController extends Controller
{
    public function halaman_home(){
        return view('user.home');
    }
}
