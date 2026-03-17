<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TbSubnavbar extends Model
{
    use HasFactory, HasRoles;

    protected $table = 'tb_subnavbar';
    protected $guarded = [];
    public $timestamps = false;

    public function kategoriBerita(): BelongsTo
    {
        return $this->belongsTo(KategoriBerita::class, 'id_navbar', 'id_navbar');
    }

}
