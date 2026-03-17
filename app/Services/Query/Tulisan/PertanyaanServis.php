<?php

namespace App\Services\Query\Tulisan;

use App\Models\Pertanyaan;

class PertanyaanServis
{
    public function getListPertanyaan($limit, $kepada)
    {
        cache()->flush();
        return  cache()->lock("get_list_pertanyaan".$kepada.now(), 10)->get(
            fn () => cache()->remember('list_pertanyaan'.now(), now()->addMinutes(2), function () use ($limit, $kepada) {

                   return Pertanyaan::where('status_tanya', '=', 1)
                   ->where('kepada', $kepada)
                   ->latest('id_pertanyaan')
                   ->paginate($limit);  
            })
        );
    }

    public function getAkunListPertanyaan($id, $limit)
    {
        cache()->flush();
        return  cache()->lock("get_akunlist_pertanyaan_$id".now(), 1)->get(
            fn () => cache()->remember("akunlist_pertanyaan_$id".now(), now()->addMinutes(1), function () use ($id, $limit) {

                   return Pertanyaan::where('status_tanya', '=', 1)
                   ->where('dari', $id)
                   ->latest('id_pertanyaan')
                   ->paginate($limit);  
            })
        );
    }

    

}