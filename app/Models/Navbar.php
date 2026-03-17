<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Navbar extends Model
{
    use HasFactory;
    protected $table = 'tb_navbar';

    protected $primaryKey = 'id_navbar';
    public $timestamps = false;
    protected $guarded = [];

    public function subNavbar()
    {
        return $this->hasMany(Navbar::class,'id_parent','id_navbar'); 
    }

    public function buildMenu($navbar, $id_parent = null)
    {
    $result = null;
    foreach($navbar as $item)
        if ($item->id_parent == $id_parent) {
        $result .= "<li class='dd-item dd3-item' data-id='{$item->id_navbar}' data-order='{$item->urut}'>
        <div class='dd-handle dd3-handle'>
            <i class='fas fa-arrows-alt'></i>
        </div>
        <div class='dd3-content'>
        {$item->judul_navbar} 
        <span style='float:right;'>
        <a href='#' data-bs-toggle='modal' data-bs-target='#newModal'
        data-form='edit' data-id='{$item->id_navbar}' class='editNavbar'>Edit</a>
     
        <a href='#' class='deleteNavbar text-danger'  data-id='{$item->id_navbar}' 
            rel='{$item->id_navbar}'>Delete</a>
        </span>
        </div>".$this->buildMenu($navbar, $item->id_navbar) . "</li>";
        }
        return $result ?  "\n<ol class=\"dd-list\">\n$result</ol>\n" : null;
    }

    // Getter for the HTML menu builder
    public function getHTMLNav($items)
    {
     return $this->buildMenu($items);
    }
}
