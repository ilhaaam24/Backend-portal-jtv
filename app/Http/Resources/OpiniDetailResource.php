<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OpiniDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title'     => $this->judul_opini ?? '',
            'seo'       => $this->seo_opini ?? '',
            'content'   => $this->artikel_opini ?? '',
            'summary'   => '',

            'photo'     => $this->imageOpini(),
            'caption'   => $this->caption_gambar_opini ?? '',
            'tag'      => $this->tag_opini ?? '',
            'status'    => $this->status_opini ?? '',
            'date'      => $this->date_publish_opini ?? '',
            'author'      => $this->nama_penulis ?? '',
            'seo_author'      => $this->seo_penulis ?? '',
            'pic_author'  => $this->imageOpiniUsers(),
            'desc_author' => $this->tentang_penulis ?? '',
            
            'category' => $this->judul ?? '',
            'seo_category' => $this->seo ?? '',
            'type_category' => $this->kategori ?? '',
        ];
    }

    /**
     * Indicates if the resource's collection keys should be preserved.
     *
     * @var bool
     */
    // public $preserveKeys = true;
}
