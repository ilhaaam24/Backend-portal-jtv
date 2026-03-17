<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tipetulisan extends Model
{
    use HasFactory, HasRoles;

    protected $table = 'tb_tipetulisan';

    // protected $primaryKey = 'seo';
    protected $guarded = [];

    public $timestamps = false;

    public function opini(): HasMany
    {
        return $this->hasMany(Opini::class, 'tipe_opini');
    }

}
