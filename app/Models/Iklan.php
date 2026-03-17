<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Traits\HasRoles;

class Iklan extends Model
{
    use HasFactory, HasRoles;

    protected $table = 'tb_iklan';

    protected $primaryKey = 'id_iklan';
    protected $guarded = [];

    public $timestamps = false;

}
