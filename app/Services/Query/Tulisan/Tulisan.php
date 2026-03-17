<?php

namespace App\Services\Query\Tulisan;

use App\Http\Resources\OpiniResource;
use App\Models\Berita;
use App\Models\Opini;
use App\Models\Penulis;
use Illuminate\Support\Facades\DB;

class Tulisan
{
    public function getTulisan($id, $limit)
    {
        return cache()->lock("get_tulisan_$id", 10)->get(
            fn() => cache()->remember('tulisan_' . $id, now()->addMinutes(2), function () use ($id, $limit) {
                return Opini::where('status_opini', '=', 'Publish')
                    ->leftjoin('tb_tipetulisan', 'v_opini.tipe_opini', '=', 'tb_tipetulisan.seo')
                    ->where('kategori', $id) //KATEGORI TIPE TULISAN ['konsultasi', 'warga', 'opini', 'sentilan', 'komunitas']
                    ->latest('id_opini')
                    ->paginate($limit);
            })
        );
    }

    public function getKategoriTulisan($id, $id2, $limit)
    {
        return cache()->lock("get_KategoriTulisan_$id.$id2", 10)->get(
            fn() => cache()->remember('katTulisan_' . $id . '_' . $id2, now()->addMinutes(5), function () use ($id, $id2, $limit) {
                return Opini::where('status_opini', '=', 'Publish')
                    ->leftjoin('tb_tipetulisan', 'v_opini.tipe_opini', '=', 'tb_tipetulisan.seo')
                    ->where('kategori', $id) // KATEGORI TIPE TULISAN
                    ->where('seo', $id2) // SEO(TIPE OPINI)
                    ->latest('id_opini')
                    ->paginate($limit);
            })
        );
    }

    public function getTagOpini($id, $limit)
    {
        return cache()->lock("get_TagOpini_$id", 10)->get(
            fn() => cache()->remember('TagOpini_' . $id, now()->addMinutes(5), function () use ($id, $limit) {
                return Opini::latest('date_publish_opini')
                    ->leftjoin('tb_tipetulisan', 'v_opini.tipe_opini', '=', 'tb_tipetulisan.seo')
                    ->where('status_opini', 'Publish')
                    ->where('tag_opini', 'regexp', $id)
                    //    ->where('tag_opini',  'LIKE', "%$id%")
                    ->paginate($limit);
            })
        );
    }

    public function getAuthorOpini($id, $status, $limit)
    {
        cache()->flush();

        return cache()->lock("get_AuthorOpini_$id" . now(), 1)->get(
            fn() => cache()->remember('AuthorOpini_' . $id . now(), now()->addMinutes(1), function () use ($id, $status, $limit) {
                return Opini::where('seo_penulis', $id)
                    ->whereIn('status_opini', $status)
                    ->paginate($limit);
            })
        );
    }

    public function getListAuthorOpini($limit)
    {
        cache()->flush();
        return cache()->lock("get_ListAuthorOpini" . now(), 10)->get(
            fn() => cache()->remember('ListAuthorOpini_' . now(), now()->addMinutes(5), function () use ($limit) {
                return Penulis::latest('tb_penulis.id_penulis')
                    ->select('v_opini.id_penulis_opini', Penulis::raw('count(*) as total'), 'tb_penulis.*')
                    ->leftjoin('v_opini', 'v_opini.id_penulis_opini', '=', 'tb_penulis.id_penulis')
                    ->where('status_opini', 'Publish')
                    ->groupBy('v_opini.id_penulis_opini')
                    ->latest('v_opini.id_opini')
                    ->paginate($limit);

            })
        );
    }

    public function getDetailOpini($id)
    {
        return cache()->lock("get_DetailOpini_$id" . now(), 10)->get(
            fn() => cache()->remember('DetailOpini_' . $id . now(), now()->addMinutes(5), function () use ($id) {
                return Opini::where('status_opini', '=', 'Publish')
                    ->leftjoin('tb_tipetulisan', 'v_opini.tipe_opini', '=', 'tb_tipetulisan.seo')
                    ->where('seo_opini', $id)
                    ->firstOrFail();
            })
        );
    }

    public function getOpiniAuthor($id_penulis, $limit)
    {
        // Hapus cache flush yang gak perlu kalau mau performa
        // cache()->flush();

        // Ganti nama cache key biar unik per user ID
        return cache()->lock("get_AuthorOpini_$id_penulis", 10)->get(
            fn() => cache()->remember('AuthorOpini_' . $id_penulis, now()->addMinutes(2), function () use ($id_penulis, $limit) {

                return Opini::query() // Pake query builder biar rapi
                    ->leftjoin('tb_penulis', 'v_opini.id_penulis_opini', '=', 'tb_penulis.id_penulis')
                    ->leftjoin('tb_tipetulisan', 'v_opini.tipe_opini', '=', 'tb_tipetulisan.seo')

                    // 👇 UBAH WHERE INI
                    // Gak perlu cek status publish kalau ini dashboard penulis sendiri (dia mau liat draft juga kan?)
                    // Kalau mau semua status: Hapus where status_opini
                    // Kalau mau cuma publish: Biarin where status_opini

                    ->where('v_opini.id_penulis_opini', $id_penulis) // <--- CEK ID LANGSUNG

                    ->latest('id_opini')
                    ->paginate($limit);
            })
        );
    }


}
