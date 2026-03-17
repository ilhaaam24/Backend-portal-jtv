<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KategoriBeritaResource extends JsonResource
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
            'title' => $this->judul_navbar,
            'seo' => $this->tag_judul,
            'seq' => $this->no_urut,
            'id' => $this->id_navbar,
            'rubrik' => $this->rubrik,
            'submenu' => $this->subnavbar,
        ];
    }

    /**
     * Indicates if the resource's collection keys should be preserved.
     *
     * @var bool
     */
    public $preserveKeys = true;
}
