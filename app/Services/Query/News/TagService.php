<?php

namespace App\Services\Query\News;

use App\Models\Tag;

class TagService
{
    public function getWhitelistTag($limit)
    {
        return  cache()->lock("get_WhitelistTag", 10)->get(
            fn () => cache()->remember('WhitelistTag'.now() , now()->addMinutes(1), function () use ($limit) {
                return Tag::select('tb_tag.id_tag', 'tb_tag.nama_tag', Tag::raw('count(tb_tag.id_tag) as count_used'))
                ->join('tagging', 'tagging.id_tag','=','tb_tag.id_tag')
                ->groupBy('tb_tag.id_tag', 'tb_tag.nama_tag')
                ->latest('count_used')
                ->get();
             
            })
        );
    }
}