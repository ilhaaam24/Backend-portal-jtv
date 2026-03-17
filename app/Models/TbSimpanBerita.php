<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbSimpanBerita extends Model
{
    protected $table = 'tb_simpan_berita';
    protected $fillable = ['id_user', 'id_berita'];
    public $timestamps = true;

    // Relasi ke Penulis (bukan User)
    public function penulis()
    {
        return $this->belongsTo(Penulis::class, 'id_user', 'id_penulis');
    }

    // Relasi ke Berita
    public function berita()
    {
        return $this->belongsTo(TbBerita::class, 'id_berita', 'id_berita');
    }
}
