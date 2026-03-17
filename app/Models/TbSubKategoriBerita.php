<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class TbSubKategoriBerita extends Model
{
    use HasFactory, HasRoles;

    protected $table = 'tb_subkategori';
    protected $guarded = [];
    public $timestamps = false;

}
