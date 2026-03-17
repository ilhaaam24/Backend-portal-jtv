<?php

namespace App\Http\Controllers;

use App\Models\Logo;
use Illuminate\Http\Request;

class LogoController extends Controller
{
    //
    public function index(){

        $logo_data = Logo::get();

        $data = array();
        $data=[
            'logo_data' => $logo_data,
        ];

        return view('layout.logo.index', $data);
    }

    public function edit(Request $request){
        $id = $request->id;
        $logo_data = Logo::where('id_logo', $id)->firstOrFail();

        $data = array();
        $data=[
            'logo_data' => $logo_data,
        ];

        return view('layout.logo.edit', $data);

    }
}
