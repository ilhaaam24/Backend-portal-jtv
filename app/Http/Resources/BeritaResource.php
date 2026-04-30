<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BeritaResource extends JsonResource
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
            'seo_biro' => $this->pengguna->biro->seo,
            'status' => $this->status_berita,
            'photo' =>  $this->imageNews(),
            'summary' => $this->rangkuman_berita,
            'caption' => $this->caption_gambar_berita,
            'city' => $this->kota_berita,
            'date' => $this->date_publish_berita,
            'category' => $this->kategori->nama_kategori_berita,
            'seo_category' => $this->kategori->seo_kategori_berita,
            'tag'      => $this->tag_berita,
            'author'      => $this->pengguna->nama_pengguna,
            'jabatan_author'    => $this->data_asli ? $this->data_asli->jabatan_author : '',
            'seo_author'      => $this->pengguna->seo,
            'editor' => $this->editor_berita,
            'pic_author' => $this->imageNewsUsers(),
            'is_youtube' => $this->is_youtube(),
        ];
    }



}
