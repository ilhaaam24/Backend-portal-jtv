<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;

class Pengguna extends Model
{
    use HasFactory, HasRoles;

    protected $table = 'tb_pengguna';
    protected $guarded = [];
    public $timestamps = false;


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function biro(): BelongsTo
    {
        return $this->belongsTo(Biro::class, 'id_biro', 'id');
    }

    public function berita()
    {
        return $this->hasMany(Berita::class, 'id_pengguna', 'id_pengguna');
    }


    public function imageUsers()
    {
        $img = $this->gambar_pengguna;

        if (Storage::exists("foto-profil/$img")) {
            $path_server = asset("assets/foto-profil/$img");
        } else {
            $path_server = asset(config('jp.path_url_no_img'));
        }

        return $path_server;
    }

    public function minat()
    {
        // belongsToMany(ModelLawan, NamaTabelPivot, KeyKita, KeyLawan)
        return $this->belongsToMany(NewKategori::class, 'tb_minat_penulis', 'id_pengguna', 'id_kategori');
    }

}
