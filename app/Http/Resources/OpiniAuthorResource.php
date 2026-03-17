<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OpiniAuthorResource extends JsonResource
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
            'name'     => $this->nama_penulis,
            'seo'       => $this->seo,
            'biodata'      => $this->tentang_penulis,
            'photo'     => asset('').config('jp.path_img_profile').$this->image_penulis,
            'facebook'     => $this->facebook,
            'instagram'     => $this->instagram,
            'tiktok'     => $this->tiktok,
            'twitter'     => $this->twitter,
            'youtube'     => $this->youtube,
        ];
    }
}
