<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OpiniResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title'     => $this->judul_opini,
            'seo'       => $this->seo_opini,
            'tag'      => $this->tag_opini,
            'photo'     => $this->imageOpini(),
            'caption'   => $this->caption_gambar_opini,
            'status'    => $this->status_opini,
            'date'      => $this->date_publish_opini,
            'author'      => $this->penulis->nama_penulis ?? '',
            'seo_author'      => $this->penulis->seo ?? '',

            'pic_author'  => $this->imageOpiniUsers(),
            'desc_author' => $this->penulis->tentang_penulis ?? '',
            'category' => $this->id_kategori_opini, // Fallback ke ID kategori jika view tidak ada
            'seo_category' => $this->seo_opini,
            'type_category' => $this->tipe_opini,
        ];
    }

    /**
     * Indicates if the resource's collection keys should be preserved.
     *
     * @var bool
     */
    // public $preserveKeys = true;
}
