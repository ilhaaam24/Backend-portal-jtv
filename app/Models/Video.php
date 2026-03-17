<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Video extends Model
{
    use HasFactory, HasRoles;

    protected $table = 'tb_video';

    protected $primaryKey = 'id_video';
    protected $guarded = [];
    public $timestamps = false;
}
