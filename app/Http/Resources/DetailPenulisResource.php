<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailPenulisResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // 👇 TAMBAHIN INI G! PENTING BANGET!
            'id'           => $this->id_penulis,

            'name'         => $this->nama_penulis ?? '',
            'seo'          => $this->seo ?? '',
            'biodata'      => $this->tentang_penulis ?? '',
            'photo'        => $this->imagePenulis(),
            'email'        => $this->email_penulis ?? '',
            'phone'        => $this->telp_penulis ?? '',

            /* 'facebook' => $this->facebook,
            'instagram'   => $this->instagram,
            'tiktok'      => $this->tiktok,
            'twitter'     => $this->twitter,
            'youtube'     => $this->youtube, */
        ];
    }
}
