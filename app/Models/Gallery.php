<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;

class Gallery extends Model
{
    use HasFactory, HasRoles ;
    protected $guarded = [];
    protected $table = 'gallery';

    public function imageGallery()
    {
        $img = $this->original_filename;
   
        if (Storage::exists("upload-gambar/$img")) {
            $path_server = asset("assets/upload-gambar/$img");
        }else{
            $path_server =  asset(config('jp.path_url_no_img'));
        }
        
        return $path_server;
    }

   
}
