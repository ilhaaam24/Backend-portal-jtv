<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Instagram extends Model
{
    
    use HasFactory, HasRoles;

    protected $table = 'tb_instagram_api';

    protected $guarded = [];
    public $timestamps = false;
}
