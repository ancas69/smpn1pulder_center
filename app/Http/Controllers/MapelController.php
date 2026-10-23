<?php

namespace App\Http\Controllers;

use App\Models\Mapel;
use Illuminate\Http\Request;

class MapelController extends Controller
{
    public function tampilkan_semua_mapel(){
        $data_mapel = Mapel::all();
        return view('public.halaman_mapel', compact('data_mapel'));
    }
}
