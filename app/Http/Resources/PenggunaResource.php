<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PenggunaResource extends JsonResource
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
            'title' => $this->nama_pengguna,
            'seo' => $this->seo,
            'photo' => asset('').config('jp.path_img_profile').$this->gambar_pengguna,
            'description' => '',
            'notes' => '',
            'link' => '',
           
        ];
    }
}
