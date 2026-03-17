<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailBeritaResource extends JsonResource
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
        'id_berita'     => $this->id_berita,
        'title' => $this->judul_berita,
        'seo_biro' => $this->seo_biro,
        'seo' => $this->seo_berita,
        'content' => $this->artikel_berita,
        'summary' => $this->rangkuman_berita,
        'photo' => $this->imageNews(),
        'caption' => $this->caption_gambar_berita,
        'tag' => $this->tag_berita,
        'status' => $this->status_berita,
        'city' => $this->kota_berita,
        'date' => $this->date_publish_berita,

            'category' => $this->nama_kategori_berita,
            'seo_category' => $this->seo_kategori_berita,

            'user' => $this->nama_pengguna,
            'navbar' => $this->judul_navbar,
            'seo_navbar' => $this->tag_judul,

        'author' => $this->nama_author,
        'jabatan_author'    => $this->data_asli ? $this->data_asli->jabatan_author : '',
        'seo_author' => $this->seo_pengguna,
        'pic_author' => $this->imageNewsUsers() ?? '',
        'desc_author' => '',
        'hit' => $this->hit,
        'editor_berita' => $this->editor_berita,
        'tipe_gambar_utama' => $this->tipe_gambar_utama,
        ];
    }
}
