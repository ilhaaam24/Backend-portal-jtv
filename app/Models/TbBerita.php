<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;

class TbBerita extends Model
{
    use HasFactory, HasRoles;

    protected $table = 'tb_berita';

    public function imageNews()
    {
        $img = $this->gambar_depan_berita;

        if (Storage::exists("upload-gambar/$img")) {
            $path_server = asset("assets/upload-gambar/$img");
        }else{
            $path_server =  asset(config('jp.path_url_no_img'));
        }

        return $path_server;

    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }


    protected $primaryKey = 'id_berita';

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
   
    protected $guarded = [];

    public $timestamps = false;
}
