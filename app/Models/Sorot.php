<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;


class Sorot extends Model
{
    use HasFactory, HasRoles;
    protected $table = 'tb_sorot';
    protected $guarded = [];

    public function imageSorot()
    {
        $img = $this->photo;
       if (Storage::exists("sorot/$img")) {
        return asset("assets/sorot/$img");
         }else{
            return asset(config('jp.path_url_no_img'));
         }

    }

    
    public function tag(): HasOne
    {
        return $this->hasOne(Tag::class, 'seo_tag', 'tag');
    }

    public $timestamps = false;

}
