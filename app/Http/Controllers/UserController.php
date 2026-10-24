<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function halaman_user(){
        return view('user.home');
    }
}
