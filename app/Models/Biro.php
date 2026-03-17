<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Spatie\Permission\Traits\HasRoles;

class Biro extends Model
{
    use HasFactory, HasRoles;

    protected $table = 'tb_biro';
    // protected $primaryKey = 'id_biro';
    protected $guarded = [];
    public $timestamps = false;



    public function berita(): HasManyThrough
    {
        return $this->hasManyThrough(Berita::class, Pengguna::class, 
            'id_biro', // Foreign key on the pengguna table...
            'id_pengguna', // Foreign key on the berita table...
            'id', // Local key on the Biro table...
            'id_pengguna' // Local key on the pengguna table...
        );
    }

    public function penggunaz()
    {
        return $this->hasMany(Pengguna::class, 'id_biro');
    }




}
