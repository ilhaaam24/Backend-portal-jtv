<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KategoriIklan extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];
    protected $table = 'kategori_iklan';
    protected $dates = ['deleted_at'];

    public function iklan()
    {
        return $this->hasMany(Iklan::class, 'kategori', 'title');
    }

}
