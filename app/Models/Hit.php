<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hit extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'tb_hit_counter';
    public $timestamps = false;

}
