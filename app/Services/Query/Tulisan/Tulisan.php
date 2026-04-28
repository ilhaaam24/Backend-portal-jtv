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
                return Opini::with(['penulis', 'tipetulisan'])
                    ->join('tb_tipetulisan', 'tb_opini.tipe_opini', '=', 'tb_tipetulisan.seo')
                    ->where('tb_opini.status_opini', '=', 'Publish')
                    ->where('tb_tipetulisan.kategori', $id)
                    ->select('tb_opini.*')
                    ->latest('tb_opini.id_opini')
                    ->paginate($limit);
            })
        );
    }

    public function getKategoriTulisan($id, $id2, $limit)
    {
        return cache()->lock("get_KategoriTulisan_$id.$id2", 10)->get(
            fn() => cache()->remember('katTulisan_' . $id . '_' . $id2, now()->addMinutes(5), function () use ($id, $id2, $limit) {
                return Opini::with(['penulis', 'tipetulisan'])
                    ->join('tb_tipetulisan', 'tb_opini.tipe_opini', '=', 'tb_tipetulisan.seo')
                    ->where('tb_opini.status_opini', '=', 'Publish')
                    ->where('tb_tipetulisan.kategori', $id)
                    ->where('tb_opini.tipe_opini', $id2)
                    ->select('tb_opini.*')
                    ->latest('tb_opini.id_opini')
                    ->paginate($limit);
            })
        );
    }

    public function getTagOpini($id, $limit)
    {
        return cache()->lock("get_TagOpini_$id", 10)->get(
            fn() => cache()->remember('TagOpini_' . $id, now()->addMinutes(5), function () use ($id, $limit) {
                return Opini::with(['penulis', 'tipetulisan'])
                    ->where('status_opini', 'Publish')
                    ->where('tag_opini', 'regexp', $id)
                    ->latest('date_publish_opini')
                    ->paginate($limit);
            })
        );
    }

    public function getAuthorOpini($id, $status, $limit)
    {
        return cache()->lock("get_AuthorOpini_".$id, 10)->get(
            fn() => cache()->remember('AuthorOpini_'.$id, now()->addMinutes(1), function () use ($id, $status, $limit) {
                return Opini::with(['penulis', 'tipetulisan'])
                    ->whereHas('penulis', function($q) use ($id) {
                        $q->where('seo', $id);
                    })
                    ->whereIn('status_opini', $status)
                    ->paginate($limit);
            })
        );
    }

    public function getListAuthorOpini($limit)
    {
        return cache()->lock("get_ListAuthorOpini", 10)->get(
            fn() => cache()->remember('ListAuthorOpini', now()->addMinutes(5), function () use ($limit) {
                return Penulis::latest('tb_penulis.id_penulis')
                    ->withCount(['opini' => function($q) {
                        $q->where('status_opini', 'Publish');
                    }])
                    ->having('opini_count', '>', 0)
                    ->paginate($limit);
            })
        );
    }

    public function getDetailOpini($id)
    {
        return cache()->lock("get_DetailOpini_".$id, 10)->get(
            fn() => cache()->remember('DetailOpini_'.$id, now()->addMinutes(5), function () use ($id) {
                return Opini::with(['penulis', 'tipetulisan'])
                    ->where('status_opini', '=', 'Publish')
                    ->where('seo_opini', $id)
                    ->firstOrFail();
            })
        );
    }

    public function getOpiniAuthor($id_penulis, $limit)
    {
        return cache()->lock("get_AuthorOpini_$id_penulis", 10)->get(
            fn() => cache()->remember('AuthorOpini_' . $id_penulis, now()->addMinutes(2), function () use ($id_penulis, $limit) {
                return Opini::with(['penulis', 'tipetulisan'])
                    ->where('id_penulis_opini', $id_penulis)
                    ->latest('id_opini')
                    ->paginate($limit);
            })
        );
    }
}
