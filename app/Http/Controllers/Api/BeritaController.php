<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthorResource;
use Illuminate\Http\Request;
use App\Http\Resources\BeritaResource;
use App\Http\Resources\DetailBeritaResource;
use App\Http\Resources\PenggunaResource;
use App\Http\Resources\PenulisResource;
use App\Http\Resources\TagResource;
use App\Models\Berita;
use App\Models\Biro;
use App\Models\Hit;
use App\Models\Navbar;
use App\Models\NewKategori;
use App\Models\Opini;
use App\Models\Pengguna;
use App\Models\Penulis;
use App\Models\Tag;
use App\Models\TaxonomyTagging;
use App\Models\TbSubnavbar;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

use App\Services\Query\News\News;
use Illuminate\Support\Arr;

class BeritaController extends Controller
{

    //
     /**
     * index
     *
     * @return void
     */
    public function detailBerita($id, News $News){
        // cache()->flush();
        $title = Berita::select('judul_berita', 'id_berita')->where('seo_berita', $id)->first();

        if (!$title) {
            return response()->json([
                'data' => null,
                'section' => [
                    'title' => $id,
                    'link' =>  config('jp.path_url_be')."api/news/detail/".$id,
                ]
            ], 200);
        }

        $idberita = $title->id_berita;

         $tagberita = TagResource::collection(TaxonomyTagging::select('tb_tag.nama_tag as namatag', 'tb_tag.seo_tag as seo_tag')
                                ->where('tagging.id_berita', $idberita)
                                ->join('tb_berita', 'tb_berita.id_berita', '=', 'tagging.id_berita')
                                ->join('tb_tag', 'tb_tag.id_tag', '=', 'tagging.id_tag')
                                ->get());
        $section =[
            'section' => [
                'title' =>  ($title) ? $title->judul_berita :'',
                'link' =>  config('jp.path_url_be')."api/news/detail/".$id,
            ],
            'tags' => $tagberita


        ];

        $data_berita = Berita::where('seo_berita', $id)
                    ->where('status_berita', '!=', 'trash')
                    ->first();
        
        if (!$data_berita) {
             return response()->json(['data' => null, 'section' => $section['section']], 200);
        }

        $get_biro = Pengguna::where('id_pengguna', $data_berita->id_pengguna)->with('biro')->first();

        // AMBIL DATA DARI SERVICE
        $q_beritadetail = $News->getDetail($id);

        // --- [FIX: SUNTIKAN ID BERITA] ---
        // Kita paksa masukkan id_berita ke object ini agar Resource menerimanya
        $q_beritadetail->id_berita = $idberita;
        // ---------------------------------

        // --- [FIX: SAFETY CHECK BIRO] ---
        $seo_biro = ($get_biro && $get_biro->biro) ? $get_biro->biro->seo : null;
        $q_beritadetail->setAttribute('seo_biro' , $seo_biro);
        // ---------------------------------

        return DetailBeritaResource::make($q_beritadetail)->additional($section);
    }

    public function beritaHeadline($id, News $News){
        $limit = request('limit') ?? config('jp.api_paginate');
        $limit = $limit >  config('jp.maxlimit') ? config('jp.maxlimit') : $limit;

        $section =[
            'section' => [
                'title' => 'Headline '.$id,
                'link' =>  config('jp.path_url_be')."api/news/headline/".$id,
            ]];

        $result = $News->getHeadline($id, $limit);

        if (empty($result)) {
            return response()->json(['data' => [], 'section' => $section['section']], 200);
        }

        $beritaheadline = BeritaResource::collection($result)
        ->additional($section);

        return $beritaheadline;
    }

    public function BeritaTerbaru($id, News $News){

        $limit = request('limit') ?? config('jp.api_paginate');
        $limit = $limit >  config('jp.maxlimit') ? config('jp.maxlimit') : $limit;

        $section =[
            'section' => [
                "title" => "Berita Terbaru ".$id,
                "link" =>  config('jp.path_url_be')."api/news/terbaru/".$id
            ]];

        $result = $News->getTerbaru($id, $limit);

        if (empty($result)) {
            return response()->json(['data' => [], 'section' => $section['section']], 200);
        }

        return BeritaResource::collection($result)->additional($section);
    }

    public function BeritaTerbaik(News $News){

        $limit = request('limit') ?? config('jp.api_paginate');
        $limit = $limit >  config('jp.maxlimit') ? config('jp.maxlimit') : $limit;

        $section =[
            'section' => [
                "title" => "Berita Terbaik ",
                "link" =>  config('jp.path_url_be')."api/news/terbaik/"
            ]];
        return BeritaResource::collection($News->getTerbaik($limit))->additional($section);
    }

    public function beritaPilihan($id, News $News){
        $limit = request('limit') ?? config('jp.api_paginate');
        $limit = $limit >  config('jp.maxlimit') ? config('jp.maxlimit') : $limit;

        $section =[
            'section' => [
                "title" => "Berita Pilihan ".$id,
                "link" =>  config('jp.path_url_be')."api/news/pilihan/".$id
            ]];

        $result = $News->getPilihan($id, $limit);

        if (empty($result)) {
            return response()->json(['data' => [], 'section' => $section['section']], 200);
        }

        return BeritaResource::collection($result)->additional($section);
    }

    public function beritaPopuler($id, News $News){
        $limit = request('limit') ?? config('jp.api_paginate');
        $limit = $limit >  config('jp.maxlimit') ? config('jp.maxlimit') : $limit;

        $section =[
            'section' => [
                "title" => "Berita Terpopuler ".$id,
                "link" =>  config('jp.path_url_be')."api/news/populer/".$id
            ]];

        $result = $News->getPopuler($id, $limit);

        if (empty($result)) {
            return response()->json(['data' => [], 'section' => $section['section']], 200);
        }

        return BeritaResource::collection($result)->additional($section);
    }

    public function beritaBreaking($id, News $News){
        $limit = request('limit') ?? config('jp.api_paginate');
        $limit = $limit >  config('jp.maxlimit') ? config('jp.maxlimit') : $limit;

        $section =[
            'section' => [
                "title" => "Breaking News",
                "link" =>  config('jp.path_url_be')."api/news/breaking"
            ]];

        $result = $News->getBreaking($id, $limit);

        if (empty($result)) {
            return response()->json(['data' => [], 'section' => $section['section']], 200);
        }

        return BeritaResource::collection($result)->additional($section);
    }

    public function opiniget()
    {
        $opini = Opini::paginate(15);
        return  $opini;
    }


    public function kategori(Request $request, $id)
    {
        $limit = $request->input('limit', 150);

        // Cari Data Kategori berdasarkan SEO/Slug
        $kategori = NewKategori::where('seo_kategori_berita', $id)->first();

        // Jika kategori tidak ditemukan, return success dengan data kosong
        if (!$kategori) {
            return response()->json([
                'data' => [],
                'section' => [
                    'title' => $id,
                    'link'  => config('jp.path_url_be')."api/news/kategori/".$id,
                ]
            ], 200);
        }

        // QUERY LANGSUNG
        $dataBerita = Berita::with(['kategori', 'pengguna'])
            ->where('id_kategori', $kategori->id_kategori_berita)
            ->where('status_berita', 'publish')
            ->latest('date_publish_berita')
            ->paginate($limit);

        $section = [
            'section' => [
                'title' => $kategori->nama_kategori_berita,
                'link'  => config('jp.path_url_be')."api/news/kategori/".$id,
            ]
        ];

        return BeritaResource::collection($dataBerita)->additional($section);
    }

    public function kanal(Request $request, $id)
    {
        $limit = $request->input('limit', 150);

        // Cari Data Kanal (Navbar)
        $navbar = \App\Models\Navbar::where('tag_judul', $id)->first();

        // Jika kanal tidak ditemukan, return success dengan data kosong
        if (!$navbar) {
            return response()->json([
                'data' => [],
                'section' => [
                    'title' => $id,
                    'link'  => config('jp.path_url_be')."api/news/kanal/".$id,
                ]
            ], 200);
        }

        // QUERY LANGSUNG
        $dataBerita = Berita::with(['kategori', 'pengguna'])
            ->where('id_menu_berita', $navbar->id_navbar)
            ->where('status_berita', 'publish')
            ->latest('date_publish_berita')
            ->paginate($limit);

        $section = [
            'section' => [
                'title' => $navbar->judul_navbar,
                'link'  => config('jp.path_url_be')."api/news/kanal/".$id,
            ]
        ];

        return BeritaResource::collection($dataBerita)->additional($section);
    }

    public function tag($id, News $news)
        {
        $limit = request('limit') ?? config('jp.api_paginate');
        $limit = $limit >  config('jp.maxlimit') ? config('jp.maxlimit') : $limit;

        $title = Tag::select('nama_tag')
        ->where('seo_tag', $id)
        ->first();

        // Jika tag tidak ditemukan, return success dengan data kosong
        if (!$title) {
            return response()->json([
                'data' => [],
                'section' => [
                    'title' => $id,
                    'link'  => config('jp.path_url_be')."api/news/tag/".$id,
                ]
            ], 200);
        }

         $tagjudul = BeritaResource::collection($news->getTag($id, 50))
            ->additional(['section' => [
            'title' => $title->nama_tag,
            'link' =>  config('jp.path_url_be')."api/news/tag/".$id,
         ]
        ]);

        return $tagjudul;
    }

    public function author($id)
        {
        $limit = request('limit') ?? config('jp.api_paginate');
        $limit = $limit >  config('jp.maxlimit') ? config('jp.maxlimit') : $limit;

        $pengguna = Pengguna::where('seo', $id)->first();

        // Jika author tidak ditemukan, return success dengan data kosong
        if (!$pengguna) {
            return response()->json([
                'data' => [],
                'section' => [
                    'title' => $id,
                    'link'  => config('jp.path_url_be')."api/news/author/".$id,
                ],
                'author' => null
            ], 200);
        }

         $author_name = BeritaResource::collection(Berita::latest('date_perubahan_berita')
        ->where('status_berita', 'Publish')
        ->where('seo_pengguna', $id)
        ->paginate($limit))
        ->additional(['section' => [
            'title' => $id,
            'link' =>  config('jp.path_url_be')."api/news/author/".$id,
            ],
            'author' => PenggunaResource::make($pengguna)
        ]);

        return $author_name;
    }

    public function listAuthor()
    {
    $limit = request('limit') ?? config('jp.api_paginate');
    $limit = $limit >  config('jp.maxlimit') ? config('jp.maxlimit') : $limit;

    $author_name = AuthorResource::collection(User::with(['pengguna' => function($query){
        $query->orderBy('id_pengguna', 'asc');
        }])->whereHas("roles", function($q){ $q->where("name", "author"); })
    ->paginate($limit))
    ->additional(['section' => [
        'title' => 'List Author',
        'link' =>  config('jp.path_url_be')."api/news/author/",
        ]

    ]);

  /*   $author_news = User::with(['pengguna' => function($query){
        $query->orderBy('id_pengguna', 'asc');
        }])->whereHas("roles", function($q){ $q->where("name", "author"); })->get();
    */
    return $author_name;
}



    public function search($id, News $news)
    {

    $limit = request('limit') ?? config('jp.api_paginate');
    $limit = $limit >  config('jp.maxlimit') ? config('jp.maxlimit') : $limit;

    // return $news->getSearch($id, $limit);
    // return BeritaResource::collection($news->getSearch($id, $limit));
    // DB::enableQueryLog();
    $data = BeritaResource::collection($news->getSearch($id, $limit));
 //CECK LAST QUERY
        /* $query = DB::getQueryLog();
        $query = end($query);
        dd($query);
        exit; */
    $section =[
        'section' => [
            'title' => $id,
            'link' =>  config('jp.path_url_be')."api/news/search/".$id,
        ]];

    $searchNews=$data
     ->additional($section);

    return $searchNews;
    }

    public function hit_counter(Request $request)
    {
        $seo_hit =  $request->id; //seo
        $mode =  $request->mode; //dev / prod
        $tipe =  $request->tipe; //dev / prod

        if($mode=="production"){
                $hit_count = Hit::where('seo_berita',$seo_hit)
                            ->where('tipe', $tipe)->increment('hit');
            if( $hit_count ){
                $data['status'] = "success";
                $data['message'] = 'Submit Successfully!';

            }else{
                $data['status'] = "error";
                $data['message'] = 'Submit failed!';
            }
            return  $data;
        }else{
            $data['status'] = "error";
            $data['message'] = 'Submit failed!';
            return  $data;
        }
    }

    public function searchBerita(Request $request)
    {
        $search_item = $request->input('search');

        if (!$search_item) {
            return response()->json(['data' => []]);
        }

        $limit = $request->input('limit') ?? config('jp.api_paginate');
        $limit = $limit > config('jp.maxlimit') ? config('jp.maxlimit') : $limit;

        // Gunakan cache agar tidak berat
        return cache()->lock("get_RagSearch:" . $search_item, 10)->get(
            fn() => cache()->remember('RagSearch:' . $search_item, now()->addMinutes(5), function () use ($search_item, $limit) {
                return Berita::with(['kategori'])
                    ->where('status_berita', 'Publish')
                    ->where(function ($query) use ($search_item) {
                        $query->where('judul_berita', 'like', '%' . $search_item . '%')
                              ->orWhere('artikel_berita', 'like', '%' . $search_item . '%')
                              ->orWhere('rangkuman_berita', 'like', '%' . $search_item . '%');
                    })
                    ->latest('date_publish_berita')
                    ->paginate($limit);
            })
        );
    }

    public function indexBerita(Request $request, News $news)
    {
        $search = $request->input('search');
        $penulis = $request->input('penulis');
        $kategori = $request->input('kategori');
        $mulai = $request->input('mulai');
        $sampai = $request->input('sampai');


        $limit = request('limit') ?? config('jp.api_paginate');
        $limit = $limit >  config('jp.maxlimit') ? config('jp.maxlimit') : $limit;

        $data = BeritaResource::collection($news->getIndexBerita($search, $penulis, $kategori, $mulai, $sampai, $limit));

        $section =[
            'section' => [
                'title' => 'Index Berita',
                'link' =>  config('jp.path_url_be')."api/news/index-berita/",
            ]];

        $kat=$data
         ->additional($section);

        return $kat;



    }


}
