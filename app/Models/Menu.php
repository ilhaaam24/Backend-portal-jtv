<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'slug', 'order', 'parent_id','location','content','created_at','updated_at'];      

    public function buildMenu($menu, $parentid = 0)
    {
    $result = null;
    foreach($menu as $item)
        if ($item->parent_id == $parentid) {
        $result .= "<li class='dd-item dd3-item' data-id='{$item->id}' data-order='{$item->order}'>
        <div class='dd-handle dd3-handle'>
            <i class='fas fa-arrows-alt'></i>
        </div>
        <div class='dd3-content'>
        {$item->title} 
        <span style='float:right;'>
        <a href='/menustop/{$item->id}'>Edit</a> |
            <a href='#' class='delete_toggle text-danger' rel='{$item->id}'>Delete</a>
        </span>
        </div>".$this->buildMenu($menu, $item->id) . "</li>";
        }
        return $result ?  "\n<ol class=\"dd-list\">\n$result</ol>\n" : null;
    }

    // Getter for the HTML menu builder
    public function getHTML($items)
    {
     return $this->buildMenu($items);
    }
}
