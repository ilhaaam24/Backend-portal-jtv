<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\PenulisResource;
use App\Models\Berita;
use App\Models\Opini;
use App\Models\Penulis;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PenulisController extends Controller
{
    //
     /**
     * index
     *
     * @return void
     */
    public function index()
    {
   
       $opini = Penulis::with('opini')
       ->find(27);

       return $opini;
       
      
    }

    public function getpenulis(){
        $penulis = Penulis::paginate(6);
        return view('master.penulisdata', compact('penulis'));
        
    }

  public function listdata(Request $request)
  {
    // return Opini::with('penulis')->get();
      $opini = Penulis::with('opini')
     ->find($request);

     return view('master.penulis', compact('opini'));
  }  
}
