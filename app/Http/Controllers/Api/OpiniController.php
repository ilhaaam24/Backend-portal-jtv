<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OpiniDetailResource;
use App\Http\Resources\OpiniResource;
use App\Http\Resources\PenulisResource;
use App\Models\Opini;
use App\Models\Penulis;
use App\Models\Tipetulisan;
use App\Services\Query\Tulisan\Tulisan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Return_;

class OpiniController extends Controller
{
    
    public function index($id, Tulisan $tulisan)
    {
        $limit = request('limit') ?? config('jp.api_paginate');
        $limit = $limit >  config('jp.maxlimit') ? config('jp.maxlimit') : $limit;

        $title = Tipetulisan::select('kategori', 'judul')->where('kategori', $id)->first();
        
        $judultipetulisan = $title->judul; 


        $section =[
            'section' => [
                'title' =>   $judultipetulisan,
                'link' =>  config('jp.path_url_be')."api/tulisan/".$id,
            ],
            'author' => 
                PenulisResource::collection(
                    Penulis::latest('tb_penulis.nama_penulis')
                    ->select('tb_penulis.nama_penulis','tb_penulis.seo','tb_penulis.tentang_penulis','tb_penulis.image_penulis',
                    'tb_penulis.facebook','tb_penulis.instagram', 'tb_penulis.tiktok', 'tb_penulis.twitter', 'tb_penulis.youtube'
                    )
                    ->leftjoin('v_opini', 'v_opini.id_penulis_opini', '=', 'tb_penulis.id_penulis')
                    ->leftjoin('tb_tipetulisan', 'v_opini.tipe_opini', '=', 'tb_tipetulisan.seo')
                    ->where('kategori', $id)
                    ->groupBy('tb_penulis.nama_penulis','tb_penulis.seo','tb_penulis.tentang_penulis','tb_penulis.image_penulis',
                    'tb_penulis.facebook','tb_penulis.instagram', 'tb_penulis.tiktok', 'tb_penulis.twitter', 'tb_penulis.youtube')
                    ->get()
                )
            ,
            'categories' =>
                Tipetulisan::where('kategori', $id)
                ->select('seo', 'judul as title')
                ->get()
         
        ];
   
        
        $opini = OpiniResource::collection($tulisan->getTulisan($id, $limit))
        ->additional($section);
      
        return  $opini;
    }

    public function konsultasikategori($id, $id2, Tulisan $tulisan)
    {
      

        $limit = request('limit') ?? config('jp.api_paginate');
        $limit = $limit >  config('jp.maxlimit') ? config('jp.maxlimit') : $limit;

        $section =[
            'section' => [
                'title' => ucfirst($id).' '.ucfirst($id2),
                'link' =>  config('jp.path_url_be')."api/tulisan/".$id.'/'.$id2,
            ]
        ];
   

        DB::enableQueryLog();
        $konsultasi_kat = OpiniResource::collection($tulisan->getKategoriTulisan($id, $id2, $limit))
        ->additional($section);
       
        //CECK LAST QUERY
        /* $query = DB::getQueryLog();
        $query = end($query);
        dd($query);
        exit; */

        return  $konsultasi_kat;
    }

    public function tagOpini($id, Tulisan $tulisan)
    {
 
        // cache()->flush();
        $limit = request('limit') ?? config('jp.api_paginate');
        $limit = $limit >  config('jp.maxlimit') ? config('jp.maxlimit') : $limit;
     
        // return cache('TagOpini_'.$id);
        $section =[
            'section' => [
        
                'title' => $id,
                'link' =>  config('jp.path_url_be')."api/tulisan-tag/".$id,
            ]
        ];

         $konsultasi_tag = OpiniResource::collection($tulisan->getTagOpini($id, $limit))
        ->additional($section);
        
        return  $konsultasi_tag;
    }

    
    public function detailkonsultasikategori($detail, Tulisan $tulisan)
    {
        $limit = request('limit') ?? config('jp.api_paginate');
        $limit = $limit >  config('jp.maxlimit') ? config('jp.maxlimit') : $limit;

         $title = Opini::where('seo_opini', $detail)->firstOrFail();
    
        $section =[
            'section' => [
        
                'title' => $title->judul_opini,
                'link' =>  config('jp.path_url_be')."api/tulisan-detail/".$detail,
            ]
        ];

         $konsultasi_detail = OpiniDetailResource::make($tulisan->getDetailOpini($detail))
        ->additional($section);
        
        return  $konsultasi_detail;
    }


    // konsultasiauthor
    public function konsultasiauthor(Request $request, $id, Tulisan $tulisan)
    {   
         $stat_opini = request('status');
        if($stat_opini){
            if($stat_opini=='all' ){
                $status = ["Draft", "Publish"];
            }else if($stat_opini!='all' ){
                $status = ["$stat_opini"];
            }
        } else{
            $status = ["Publish"];
        }

        $limit = request('limit') ?? config('jp.api_paginate');
        $limit = $limit >  config('jp.maxlimit') ? config('jp.maxlimit') : $limit;

        $section =[
            'section' => [
                'title' => ucfirst($id),
                'link' =>  config('jp.path_url_be')."api/tulisan-author/".$id,
            ],
            'author' => 
                PenulisResource::make(Penulis::where('seo', $id)
                ->first()
            )
        ];

         $hasil_data = $tulisan->getAuthorOpini($id, $status, $limit);
            if($hasil_data->total()> 0){
                return  OpiniResource::collection($tulisan->getAuthorOpini($id, $status, $limit))->additional($section);
            }else{
                $data['status'] = "error";
                $data['message'] = 'No Record data!';
                return  $data; 
            }
    }


    public function tulisanListAuthor(Tulisan $tulisan)
    {   

       
        $limit = request('limit') ?? config('jp.api_paginate');
        $limit = $limit >  config('jp.maxlimit') ? config('jp.maxlimit') : $limit;

        $section =[
            'section' => [
                'title' => "List Author",
                'link' =>  config('jp.path_url_be')."api/tulisan-list-author",
            ]
        ];
   

        $opini = PenulisResource::collection($tulisan->getListAuthorOpini($limit))
        ->additional($section);
      
        return  $opini;
    }

   
    public function jurnalismeWarga()
    {
        $limit = request('limit') ?? config('jp.api_paginate');
        $limit = $limit >  config('jp.maxlimit') ? config('jp.maxlimit') : $limit;

        $jurnalisme_warga = OpiniResource::collection(Opini::where('status_opini', '=', 'Publish')
        ->where('tipe_opini', 'berita')
        ->latest('id_opini')
        ->paginate($limit));
        
        return  $jurnalisme_warga;
    }

  
}
