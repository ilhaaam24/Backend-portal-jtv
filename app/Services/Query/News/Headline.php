<?php

namespace App\Services\Query\News;

use App\Models\Berita;

class Headline
{
    public function getHeadline($limit)
    {
        return  cache()->lock("get_BeritaHeadline", 10)->get(
            fn () => cache()->remember('BeritaHeadline', now()->addMinutes(5), function () use ($limit) {
                   return Berita::latest('date_publish_berita')
                    ->where('status_berita', 'Publish')
                    ->where('tipe_berita_utama', '1')
                    ->limit($limit)
                    ->get();
             
            })
        );
    }
}