<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logo extends Model
{
    use HasFactory;

    protected $table = 'tb_logo';

    protected $primaryKey = 'id_logo';
    protected $guarded = [];

    public $timestamps = false;
}
