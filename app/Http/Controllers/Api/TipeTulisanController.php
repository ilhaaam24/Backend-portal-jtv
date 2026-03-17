<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TipetulisanResource;
use App\Models\Tipetulisan;
use Illuminate\Http\Request;

class TipeTulisanController extends Controller
{
    //
    public function index(){
        $tipe_tulisan=   TipetulisanResource::collection(Tipetulisan::oldest('kategori')
                                ->oldest('judul')            
                                ->get())
        ->additional(
            ['section' => [
                'title' => 'Tipe Tulisan',
                'link' =>  config('jp.path_url_be')."api/tipe-tulisan",
                ]] 
        );
        
        return  $tipe_tulisan;
        }
}
