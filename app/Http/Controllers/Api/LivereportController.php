<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LivereportResource;
use App\Models\Livereport;
use Illuminate\Http\Request;

class LivereportController extends Controller
{
    //
    public function index()
    {   
        $title = Livereport::select('link_livereport')
        ->where('deskripsi', 'Live Vidio')->first();

        $livestrean = Livereport::where('status_livereport', 1)->first();

        $section =[
            'section' => [
                'title' => 'LiveStream',
                'link' =>  config('jp.path_url_web')."api/livestream",
            ],
            'live' => [
                'status' => $livestrean->status_livereport,
                'title' => $livestrean->deskripsi,
                'link' =>  $livestrean->link_livereport,
            ]
        
        ];

        $livereport = LivereportResource::collection(Livereport::select('tb_livereport.link_livereport as youtube',
        'b.link_livereport as facebook',
        'c.link_livereport as jtv',
        'd.link_livereport as video')
        ->crossJoin('tb_livereport AS b')
        ->crossJoin('tb_livereport AS c')
        ->crossJoin('tb_livereport AS d')
        ->where('tb_livereport.keterangan','=',1)
        ->where('b.keterangan','=',2)
        ->where('c.keterangan','=',3)
        ->where('d.keterangan','=',4)
            ->get())
            ->additional($section);

        return  $livereport;
    }
}
