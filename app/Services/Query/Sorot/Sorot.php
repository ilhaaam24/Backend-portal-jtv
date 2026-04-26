<?php

namespace App\Services\Query\Sorot;

use App\Models\Berita;
use App\Models\Sorot as ModelsSorot;

class Sorot
{
    public function getSorot($limit)
    {
        return  cache()->lock("get_sorot", 10)->get(
            fn () => cache()->remember('Sorot', now()->addMinutes(5), function () use ($limit) {
                   return ModelsSorot::latest('no_urut')
                   ->where('status', 1)
                   ->paginate($limit);  
            })
        );
    }

    public function getDetailSorot($id, $limit)
    {
        return  cache()->lock("get_detailsorot:".$id, 10)->get(
            fn () => cache()->remember('DetailSorot:'.$id, now()->addMinutes(5), function () use ($id, $limit) {
         
                   return ModelsSorot::latest('tb_berita.date_perubahan_berita')
                        ->select('tb_sorot.judul', 'tb_sorot.tag', 'tb_tag.nama_tag', 'tb_tag.id_tag', 'tagging.id_tagging',
                        'tb_berita.id_berita', 'tb_berita.judul_berita', 'tb_berita.seo_berita', 
                        'tb_berita.status_berita', 'tb_berita.gambar_depan_berita',  'tb_berita.rangkuman_berita',
                        'tb_berita.caption_gambar_berita', 'tb_berita.kota_berita',
                        'tb_berita.date_publish_berita', 'tb_kategori_berita.nama_kategori_berita', 
                        'tb_berita.editor_berita', 'tb_kategori_berita.seo_kategori_berita',
                        'tb_pengguna.nama_pengguna','tb_pengguna.seo', 'tb_pengguna.gambar_pengguna')
                        ->join('tb_tag', 'tb_sorot.tag','=','tb_tag.seo_tag')
                        ->join('tagging', 'tagging.id_tag','=','tb_tag.id_tag')
                        ->join('tb_berita', 'tb_berita.id_berita','=','tagging.id_berita')
                        ->join('tb_kategori_berita', 'tb_berita.id_kategori','=','tb_kategori_berita.id_kategori_berita')
                        ->join('tb_pengguna','tb_berita.id_pengguna','=','tb_pengguna.id_pengguna' )
                        ->where('tb_berita.status_berita', 'Publish')
                        ->where('tb_tag.seo_tag',$id)
                        ->paginate($limit);  
            })
        );
    }
}