<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Opini extends Model
{
    use HasFactory, HasRoles;

    protected $table = 'tb_opini';

    protected $primaryKey = 'id_opini';
    protected $guarded = [];

    public function penulis(): BelongsTo
    {
        return $this->belongsTo(Penulis::class,'id_penulis_opini', 'id_penulis');
    }

    public function tipetulisan(): BelongsTo
    {
        return $this->belongsTo(Tipetulisan::class,'tipe_opini', 'seo');
    }


    public function imageOpini()
    {
        $img = $this->gambar_opini;
        //   config('jp.path_url_be').config('jp.path_img').$img ?? '';
        
        if (Storage::disk('jtv')->exists("upload-gambar/$img")) {
            $path_server = url("assets/upload-gambar/$img");
        }else{
            $path_server =  asset(config('jp.path_url_no_img'));
        }
        return $path_server;
    }

    public function imageOpiniUsers()
    {
        // Gunakan relasi penulis karena image_penulis tidak ada di tb_opini
        $img = $this->penulis->image_penulis ?? 'userpic.png';

        if (Storage::disk('jtv')->exists("foto-profil/$img")) {
            $path_server = url("assets/foto-profil/$img");
        }else{
            $path_server =  asset(config('jp.path_url_no_img'));
        }
        return $path_server;
    }

}
