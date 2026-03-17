<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Tag extends Model
{
    use HasFactory;

    
    protected $guarded = [];
    protected $table = 'tb_tag';
    public $timestamps = false;

    public function berita(): BelongsToMany
    {
        return $this->belongsToMany(TbBerita::class);
    }

    public function sorot(): BelongsTo
    {
        return $this->belongsTo(Sorot::class, 'seo_tag', 'id_tag');
    }


}
