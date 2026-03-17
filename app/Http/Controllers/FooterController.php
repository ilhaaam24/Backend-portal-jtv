<?php

namespace App\Http\Controllers;

use App\Models\Footer;
use Illuminate\Http\Request;

class FooterController extends Controller
{
    //
    public function index()
    {
        $footer = Footer::get();

        $data = array();

        $data = [
            'footer' => $footer,
        ];

        return view('layout.footer.index', $data);

    }

    public function edit(Request $request){
        $id= $request->id;

        $footer = Footer::where('id_footer', $id)
                ->paginate(10);

        $data = array();

        $data = [
            'footer' => $footer,
        ];

        return view('layout.footer.edit', $data);
    }
}
