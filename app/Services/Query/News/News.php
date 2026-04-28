<?php

namespace App\Services\Query\News;

use App\Models\Berita;
use App\Models\Biro;
use App\Models\TaxonomyTagging;
use Illuminate\Contracts\Database\Eloquent\Builder;

class News
{
    public function getHeadline($id, $limit)
    {
        return  cache()->lock("get_BeritaHeadline:".$id, 10)->get(
            fn () => cache()->remember('BeritaHeadline:'.$id, now()->addMinutes(5), function () use ($id, $limit) {
                $getbiro = Biro::where('seo', $id)->first();
                if (!$getbiro) return [];
                $penggunas = $getbiro->penggunaz->pluck('id_pengguna');

                   return Berita::latest('date_publish_berita')
                   ->with(['pengguna.biro'])
                    ->whereIn('id_pengguna', $penggunas)
                    ->where('status_berita', 'Publish')
                    ->where('tipe_berita_utama', '1')
                    ->limit($limit)
                    ->get();
             
            })
        );
    }

    public function getTerbaru($id, $limit)
    {   
        // cache()->flush();
        return  cache()->lock("get_BeritaTerbaru:".$id, 10)->get(
            fn () => cache()->remember('BeritaTerbaru:'.$id, now()->addMinutes(1), function () use ($id, $limit) {
                    $getbiro = Biro::where('seo', $id)->first();
                    if (!$getbiro) return [];
                    $penggunas = $getbiro->penggunaz->pluck('id_pengguna');
                   return Berita::latest('is_berita_terbaru')
                    ->with(['pengguna.biro'])
                    ->whereIn('id_pengguna', $penggunas)
                    ->latest('date_publish_berita') 
                    ->where('status_berita', 'Publish')
                    ->with('pengguna')
                    ->paginate($limit);
             
            })
        );
    }

    public function getTerbaik($limit)
    {   
        return  cache()->lock("get_BeritaTerbaik", 10)->get(
            fn () => cache()->remember('BeritaTerbaik', now()->addMinutes(5), function () use ($limit) {
                   return Berita::latest('date_publish_berita')
                    ->with(['pengguna.biro'])
                    ->where('status_berita', 'Publish')
                    ->paginate($limit);
             
            })
        );
    }

    public function getPilihan($id, $limit)
    {
        return  cache()->lock("get_BeritaPilihan:".$id, 10)->get(
            fn () => cache()->remember('BeritaPilihan:'.$id, now()->addMinutes(5), function () use ($id, $limit) {
                    $getbiro = Biro::where('seo', $id)->first();
                    if (!$getbiro) return [];
                    $penggunas = $getbiro->penggunaz->pluck('id_pengguna');

                   return Berita::latest('date_publish_berita')
                   ->with(['pengguna.biro'])
                   ->whereIn('id_pengguna', $penggunas)
                   ->where('tipe_berita_pilihan', 1)
                   ->where('status_berita', 'Publish')
                   ->paginate($limit);
             
            })
        );
    }

    public function getPopuler($id, $limit)
    {
        return  cache()->lock("get_BeritaPopuler:".$id, 10)->get(
            fn () => cache()->remember('BeritaPopuler:'.$id, now()->addMinutes(5), function () use ($id,$limit) {
                $getbiro = Biro::where('seo', $id)->first();
                if (!$getbiro) return [];
                $penggunas = $getbiro->penggunaz->pluck('id_pengguna');

                   return Berita::orderByRaw('CAST(pengunjung_berita AS UNSIGNED) DESC')
                   ->with(['pengguna.biro'])
                   ->whereIn('id_pengguna', $penggunas)
                   ->where('status_berita', 'Publish')
                   ->whereRaw('CAST(pengunjung_berita AS UNSIGNED) >= 0')
                   ->paginate($limit);
             
            })
        );
    }

    public function getBreaking($id, $limit)
    {
        return  cache()->lock("get_BeritaBreaking:".$id, 10)->get(
            fn () => cache()->remember('BeritaBreaking:'.$id, now()->addMinutes(5), function () use ($id, $limit) {
                $getbiro = Biro::where('seo', $id)->first();
                if (!$getbiro) return [];
                $penggunas = $getbiro->penggunaz->pluck('id_pengguna');

                   return  Berita::latest('date_publish_berita')
                   ->with(['pengguna.biro'])
                   ->whereIn('id_pengguna', $penggunas)
                   ->where('status_berita', 'Publish')
                   ->limit(config('jp.api_paginate'))
                   ->paginate($limit);
                   
            })
        );
    }

    public function getDetail($id)
    {
        // cache()->flush();
        return  cache()->lock("get_BeritaDetail".$id, 10)->get(
            fn () => cache()->remember('detail'.$id, now()->addMinutes(2), function () use ($id) {
                return $data_berita = Berita::where('seo_berita', $id)
                    ->where('status_berita', '!=', 'trash')
                    ->firstOrFail();  
            })
        );
    }

    public function getKategori($id, $limit)
    {
        return  cache()->lock("get_Kategori", 10)->get(
            fn () => cache()->remember('kat'.$id, now()->addMinutes(5), function () use ($id , $limit) {
                   return Berita::latest('date_publish_berita')
                   ->latest('date_perubahan_berita')
                //    ->where('rubrik', 1)
                   ->where('status_berita', 'Publish')
                   ->where('seo_kategori_berita', $id)
                   ->paginate($limit);  
            })
        );
    }

    public function getKanal($id, $limit)
    {
        return  cache()->lock("get_Kanal", 10)->get(
            fn () => cache()->remember('kanal'.$id, now()->addMinutes(5), function () use ($id , $limit) {
                   return Berita::latest('date_publish_berita')
                    ->latest('date_perubahan_berita')
                    ->where('status_berita', 'Publish')
                    ->where(function($q)use ($id) {
                            $q->where('tag_judul', $id);
                    })
                   ->paginate($limit);  
            })
        );
    }

    public function getSearch($id, $limit)
    {
        return  cache()->lock("get_Search:".$id, 10)->get(
            fn () => cache()->remember('Search:'.$id, now()->addMinutes(5), function () use ($id , $limit) {
                   return Berita::latest('date_perubahan_berita')
                   ->where('status_berita', 'Publish')
                   ->where('judul_berita','LIKE','%'.$id.'%')
                   ->paginate($limit);  
            })
        );
    }

    public function getIndexBerita($search, $penulis, $kategori, $mulai, $sampai, $limit)
    {
        $cacheKey = 'SearchIndex:'.md5($search.$penulis.$kategori.$mulai.$sampai.$limit);
        return  cache()->lock("get_SearchIndex:".$cacheKey, 10)->get(
        fn () => cache()->remember($cacheKey, now()->addMinutes(5), function () use ($search, $penulis, $kategori, $mulai, $sampai, $limit) {
                return Berita::latest('date_perubahan_berita')
                ->where('status_berita', 'Publish')
                ->where(function ($query)  use ($search, $penulis, $kategori, $mulai, $sampai) {
                    if($search!=''){
                        $query->where('judul_berita', 'like','%'.$search.'%');
                    } 
                    
                    if($penulis!=''){
                        $query->where('seo_pengguna',$penulis);
                    }
                    
                    if($kategori!=''){
                       $query->where('id_menu_berita',$kategori);
                    }
                    if($mulai!=''){
                        $query->where('date_publish_berita', '>=',$mulai);
                    }

                    if($sampai!=''){
                        $query->where('date_publish_berita', '<=',$sampai);
                    }

                    
                        })
                ->paginate($limit);  
            })
        );
    }

    public function getTag($id, $limit)
    {
        return  cache()->lock("get_Tag:".$id, 10)->get(
            fn () => cache()->remember('Tags:'.$id, now()->addMinutes(2), function () use ($id , $limit) {
                        return Berita::latest('tb_berita.date_perubahan_berita')
                        ->join('tagging', 'tb_berita.id_berita','=','tagging.id_berita')
                        ->join('tb_tag', 'tagging.id_tag','=','tb_tag.id_tag')
                        ->where('tb_berita.status_berita', 'Publish')
                        ->where('tb_tag.seo_tag',$id)
                        ->select('tb_berita.*')
                        ->paginate($limit);  
            })
        );
    }


   

}