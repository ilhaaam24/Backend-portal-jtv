<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PenulisResource extends JsonResource
{

/*     public $status;
    public $message; */
    
    /**
     * __construct
     *
     * @param  mixed $status
     * @param  mixed $message
     * @param  mixed $resource
     * @return void
     */
  /*   public function __construct($status, $message, $resource)
    {
        parent::__construct($resource);
        $this->status  = $status;
        $this->message = $message;
    } */

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name'     => $this->nama_penulis,
            'seo'       => $this->seo,
            'biodata'      => $this->tentang_penulis,
            // 'photo'         => config('jp.path_url_be').config('jp.path_img_profile').$this->image_penulis,
            'photo'         => $this->imagePenulis(), 
            'facebook'     => $this->facebook,
            'instagram'     => $this->instagram,
            'tiktok'     => $this->tiktok,
            'twitter'     => $this->twitter,
            'youtube'     => $this->youtube,
           
        ];
    }
}
