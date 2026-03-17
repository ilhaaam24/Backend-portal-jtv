<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class TbOpini extends Model
{
    use HasFactory, HasRoles;


 
    protected $table = 'tb_opini';
    protected $primaryKey = 'id_opini';

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
   
    protected $guarded = [];

    public $timestamps = false;
}
