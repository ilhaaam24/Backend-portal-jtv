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
        $title = Berita::select('judul_berita', 'id_berita')->where('seo_berita', $id)->firstOrFail();

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
                    ->firstOrFail();

        $get_biro = Pengguna::where('id_pengguna', $data_berita->id_pengguna)->with('biro')->first();

        // AMBIL DATA DARI SERVICE
        $q_beritadetail = $News->getDetail($id);

        // --- [FIX: SUNTIKAN ID BERITA] ---
        // Kita paksa masukkan id_berita ke object ini agar Resource menerimanya
        $q_beritadetail->id_berita = $idberita;
        // ---------------------------------

        $q_beritadetail->setAttribute('seo_biro' , $get_biro->biro->seo);

        return DetailBeritaResource::make($q_beritadetail)->additional($section);
    }

    public function beritaHeadline($id, News $News){
        $limit = request('limit') ?? config('jp.api_paginate');
        $limit = $limit >  config('jp.maxlimit') ? config('jp.maxlimit') : $limit;

        // return cache('users');
        $section =[
            'section' => [
                'title' => 'Headline '.$id,
                'link' =>  config('jp.path_url_be')."api/news/headline/".$id,
            ]];

        $beritaheadline = BeritaResource::collection($News->getHeadline($id, $limit))
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
        return BeritaResource::collection($News->getTerbaru($id, $limit))->additional($section);
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


        return BeritaResource::collection($News->getPilihan($id, $limit))->additional($section);
    }

    public function beritaPopuler($id, News $News){
        $limit = request('limit') ?? config('jp.api_paginate');
        $limit = $limit >  config('jp.maxlimit') ? config('jp.maxlimit') : $limit;

        $section =[
            'section' => [
                "title" => "Berita Terpopuler ".$id,
                "link" =>  config('jp.path_url_be')."api/news/populer/".$id
            ]];
        return BeritaResource::collection($News->getPopuler($id, $limit))->additional($section);
    }

    public function beritaBreaking($id, News $News){
        $limit = request('limit') ?? config('jp.api_paginate');
        $limit = $limit >  config('jp.maxlimit') ? config('jp.maxlimit') : $limit;

        $section =[
            'section' => [
                "title" => "Breaking News",
                "link" =>  config('jp.path_url_be')."api/news/breaking"
            ]];

        return BeritaResource::collection($News->getBreaking($id, $limit))->additional($section);
    }

    public function opiniget()
    {
        $opini = Opini::paginate(15);
        return  $opini;
    }


    public function kategori(Request $request, $id)
    {
        // 1. Set Limit jadi 150 sesuai request (atau ambil dari parameter URL kalau ada)
        // Default 150 kalau gak ada request limit
        $limit = $request->input('limit', 150);

        // Safety cap: Jangan biarkan user minta lebih dari maxlimit config (opsional)
        // $limit = $limit > config('jp.maxlimit') ? config('jp.maxlimit') : $limit;

        // 2. Cari Data Kategori berdasarkan SEO/Slug
        $kategori = NewKategori::where('seo_kategori_berita', $id)
                        ->firstOrFail(); // Kalau gak ketemu, otomatis 404

        // 3. QUERY LANGSUNG (Bypass Service & Cache yang bikin error looping)
        $dataBerita = Berita::with(['kategori', 'pengguna']) // Load relasi biar ringan
            ->where('id_kategori', $kategori->id_kategori_berita) // Filter Kategori
            ->where('status_berita', 'publish') // Cuma yang publish
            ->latest('date_publish_berita') // Urutkan dari yang terbaru
            ->paginate($limit); // ✨ Paginasi otomatis jalan bener disini

        // 4. Siapkan Section Data
        $section = [
            'section' => [
                'title' => $kategori->nama_kategori_berita,
                'link'  => config('jp.path_url_be')."api/news/kategori/".$id,
            ]
        ];

        // 5. Return Resource
        return BeritaResource::collection($dataBerita)->additional($section);
    }

    public function kanal(Request $request, $id)
    {
        $limit = $request->input('limit', 150); // Default 150

        // 1. Cari Data Kanal (Navbar)
        // Pastikan Model Navbar sudah di-import: use App\Models\Navbar;
        $navbar = \App\Models\Navbar::where('tag_judul', $id)->firstOrFail();

        // 2. QUERY LANGSUNG (Bypass Service)
        $dataBerita = Berita::with(['kategori', 'pengguna'])
            ->where('id_menu_berita', $navbar->id_navbar) // Filter berdasarkan ID Menu/Kanal
            ->where('status_berita', 'publish')
            ->latest('date_publish_berita')
            ->paginate($limit);

        // 3. Siapkan Section
        $section = [
            'section' => [
                'title' => $navbar->judul_navbar,
                'link'  => config('jp.path_url_be')."api/news/kanal/".$id,
            ]
        ];

        // 4. Return
        return BeritaResource::collection($dataBerita)->additional($section);
    }

    public function tag($id, News $news)
        {
        $limit = request('limit') ?? config('jp.api_paginate');
        $limit = $limit >  config('jp.maxlimit') ? config('jp.maxlimit') : $limit;

        $title = Tag::select('nama_tag')
        ->where('seo_tag', $id)
        ->firstOrFail();

        // return $news->getTag($id, $limit);
         $tagjudul = BeritaResource::collection($news->getTag($id, 50))
            ->additional(['section' => [
            'title' => ($title) ? $title->nama_tag :'',
            'link' =>  config('jp.path_url_be')."api/news/tag/".$id,
         ]
        ]);

        return $tagjudul;
    }

    public function author($id)
        {
        $limit = request('limit') ?? config('jp.api_paginate');
        $limit = $limit >  config('jp.maxlimit') ? config('jp.maxlimit') : $limit;

         $author_name = BeritaResource::collection(Berita::latest('date_perubahan_berita')
        // ->where('rubrik', 1)
        ->where('status_berita', 'Publish')
        ->where('seo_pengguna', $id)
        ->paginate($limit))
        ->additional(['section' => [
            'title' => $id,
            'link' =>  config('jp.path_url_be')."api/news/author/".$id,
            ],
            'author' => PenggunaResource::make(Pengguna::where('seo', $id)
            ->firstOrFail())
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

    public function searchBerita(Request $search)
    {
        $search_item = $search->search;

        $limit = request('limit') ?? config('jp.api_paginate');
        $limit = $limit >  config('jp.maxlimit') ? config('jp.maxlimit') : $limit;

        return  cache()->lock("get_Search$search_item".now(), 10)->get(
            fn () => cache()->remember('Search'.$search_item.now(), now()->addMinutes(5), function () use ($search_item , $limit) {
                   return Berita::latest('date_perubahan_berita')
                   ->where('status_berita', 'Publish')
                   ->where('judul_berita','LIKE','%'.$search_item.'%')
                   ->where(function ($query)  use ($search_item) {
                    return $query->where('judul_berita', 'like','%'.$search_item.'%')
                                ->orWhere('title', 'like','%'.$search_item.'%')
                                ->orWhere('other_product_numbers','%'.$search_item.'%');
                        })
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
