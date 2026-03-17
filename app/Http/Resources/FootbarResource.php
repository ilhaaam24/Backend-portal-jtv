<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FootbarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
 
        return [
            'title' => $this->judul_footbar,
            'seo' => $this->tag_judul,
            'status' => $this->judul_status,
            'content' =>  $this->isi_footbar,
        ];
    
    }
}
