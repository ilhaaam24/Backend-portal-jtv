<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleHasMenu extends Model
{
    use HasFactory;

    protected $table = 'role_has_menus';
    protected $guarded = [];

    public $timestamps = false;

    public function subMenus()
    {
        return $this->hasMany(Navigation::class,'main_menu','navigation_id')
       ->orderBy('short', 'asc'); 
    }

}
