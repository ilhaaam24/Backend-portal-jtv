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
        return  cache()->lock("get_detailsorot", 10)->get(
            fn () => cache()->remember('DetailSorot'.$id, now()->addMinutes(5), function () use ($id, $limit) {
         
                   return ModelsSorot::latest('v_berita.date_perubahan_berita')
                        ->select('tb_sorot.judul', 'tb_sorot.tag', 'tb_tag.nama_tag', 'tb_tag.id_tag', 'tagging.id_tagging',
                        'v_berita.id_berita', 'v_berita.judul_berita', 'v_berita.seo_berita', 
                        'v_berita.status_berita', 'v_berita.gambar_depan_berita',  'v_berita.rangkuman_berita',
                        'v_berita.caption_gambar_berita', 'v_berita.kota_berita',
                        'v_berita.date_publish_berita', 'tb_kategori_berita.nama_kategori_berita', 
                        'v_berita.editor_berita', 'tb_kategori_berita.seo_kategori_berita',
                        'tb_pengguna.nama_pengguna','tb_pengguna.seo', 'tb_pengguna.gambar_pengguna')
                        ->join('tb_tag', 'tb_sorot.tag','=','tb_tag.seo_tag')
                        ->join('tagging', 'tagging.id_tag','=','tb_tag.id_tag')
                        ->join('v_berita', 'v_berita.id_berita','=','tagging.id_berita')
                        ->join('tb_kategori_berita', 'v_berita.id_kategori','=','tb_kategori_berita.id_kategori_berita')
                        ->join('tb_pengguna','v_berita.id_author','=','tb_pengguna.id_pengguna' )
                        ->where('v_berita.status_berita', 'Publish')
                        ->where('tb_tag.seo_tag',$id)
                        ->paginate($limit);  
            })
        );
    }
}