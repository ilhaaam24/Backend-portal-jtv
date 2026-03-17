<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Navigation extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    // protected $primaryKey = 'id';

    public function role()
    {
        return $this->belongsToMany(Role::class, 'role_has_menu', 'navigation_id', 'role_id');
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class,'navigation_id','id'); 
    }

    public function subMenus()
    {
        return $this->hasMany(Navigation::class,'main_menu','id'); 
    }

    public function buildMenu($navigation, $main_menu = null)
    {
    $result = null;
    foreach($navigation as $item)
        if ($item->main_menu == $main_menu) {
        $result .= "<li class='dd-item dd3-item' data-id='{$item->id}' data-order='{$item->short}'>
        <div class='dd-handle dd3-handle'>
            <i class='fas fa-arrows-alt'></i>
        </div>
        <div class='dd3-content'>
        {$item->name} 
        <span style='float:right;'>
        <a href='#' data-bs-toggle='modal' data-bs-target='#newModal'
        data-form='edit' data-id='{$item->id}' class='editNavigasi'>Edit</a>
     
        <a href='#' class='deleteNavigasi text-danger'  data-id='{$item->id}' 
            rel='{$item->id}'>Delete</a>
        </span>
        </div>".$this->buildMenu($navigation, $item->id) . "</li>";
        }
        return $result ?  "\n<ol class=\"dd-list\">\n$result</ol>\n" : null;
    }

    // Getter for the HTML menu builder
    public function getHTMLNav($items)
    {
     return $this->buildMenu($items);
    }

   
}
