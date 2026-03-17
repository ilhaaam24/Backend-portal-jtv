<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IklanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
      return [
        'title' => $this->nama_iklan,
        'photo' => config('jp.path_url_be').config('jp.path_img_iklan'). $this->gambar_iklan,
        'link' => $this->link,
        'width'  => $this->width,
        'height'  => $this->height,
        ];
    }
}
