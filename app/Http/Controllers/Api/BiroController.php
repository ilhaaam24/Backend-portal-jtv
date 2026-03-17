<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Biro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class BiroController extends Controller
{
    //
    public function index(){
        $biro = Biro::all();

        return $biro;
    }

    public function viewBiro()
    {
        $list_biro = Biro::oldest('nama_biro')->get();

        $data = array();
        $data = [   
                'list_biro'=> $list_biro
        ];

        return view('master.biro.index', $data);
    }
    
    public function SubmitBiroUpdate(Request $request)
    {
        $updated = Biro::where('seo', $request->seo )->update(['link' => $request->link]);
       
        if($updated) {
            $result['status'] = "success";
            $result['message'] = "Biro Updated successfully!";
            return response()->json($result);
        }else{
            $result['status'] = "failed";
            $result['message'] = "Biro Updated Failed!";
            return response()->json($result);
        }
    }
}
