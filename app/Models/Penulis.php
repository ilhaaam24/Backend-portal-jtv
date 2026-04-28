<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Penulis extends Authenticatable
{
    // 4. PASANG TRAIT HasApiTokens
    use HasFactory, HasRoles, HasApiTokens;

    protected $guarded = [];
    protected $table = 'tb_penulis';
    public $timestamps = false;
    protected $primaryKey = 'id_penulis'; // Primary Key Penulis

    // --- RELASI KE MINAT (PENTING!) ---
    public function minat()
    {
        return $this->belongsToMany(
            NewKategori::class,
            'tb_minat_penulis',
            'id_penulis',
            'id_kategori'
        )->withPivot('score')
            ->withTimestamps();
        ;
    }

    // --- RELASI BAWAAN LO ---
    public function opini(): HasMany
    {
        return $this->hasMany(Opini::class, 'id_penulis_opini');
    }

    public function pertanyaandari(): HasMany
    {
        return $this->HasMany(Pertanyaan::class, 'dari', 'id_penulis');
    }

    public function pertanyaankepada(): HasMany
    {
        return $this->HasMany(Pertanyaan::class, 'kepada', 'id_penulis');
    }

    public function imagePenulis()
    {
        $img = $this->image_penulis;
        if (Storage::disk('jtv')->exists("foto-profil/$img")) {
            $path_server = url("assets/foto-profil/$img");
        } else {
            $path_server = asset(config('jp.path_url_no_img'));
        }
        return $path_server;
    }
}
