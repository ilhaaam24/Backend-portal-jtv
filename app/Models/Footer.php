<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;


class Footer extends Model
{
    use HasFactory, HasRoles;

    protected $table = 'tb_footer';

    protected $primaryKey = 'id_footer';
    protected $guarded = [];

    public $timestamps = false;
}
