<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->pengguna->nama_pengguna,
            'seo' => $this->pengguna->seo,
            'photo' => asset('').config('jp.path_img_profile').$this->pengguna->gambar_pengguna,
           
        ];
    }
}
