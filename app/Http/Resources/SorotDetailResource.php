<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SorotDetailResource extends JsonResource
{
   
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        return [
            'title' => $this->judul_berita,
            'seo' => $this->seo_berita,
            'status' => $this->status_berita,
            'summary' => $this->rangkuman_berita,
            'photo' =>  config('jp.path_url_be').config('jp.path_img').$this->gambar_depan_berita ?? '',
            'caption' => $this->caption_gambar_berita,
            'city' => $this->kota_berita,
            'date' => $this->date_publish_berita,
            'category' => $this->nama_kategori_berita,
            'seo_category' => $this->seo_kategori_berita,
            'tag'      => $this->nama_tag,
            'author'      => $this->nama_pengguna,
            'seo_author'      => $this->seo,
            'pic_author' => config('jp.path_url_be').config('jp.path_img_profile').$this->gambar_pengguna ?? '',
            'editor' => $this->editor_berita,

        ];
    }

     

}
