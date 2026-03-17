<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Footbar extends Model
{
    use HasFactory;
    protected $table = 'tb_footbar';

    protected $primaryKey = 'id_footbar';
    protected $guarded = [];

    public $timestamps = false;

    public function buildMenu($footbar, $id_parent = null)
    {
    $result = null;
    foreach($footbar as $item)
        if ($item->id_parent == $id_parent) {
        $result .= "<li class='dd-item dd3-item' data-id='{$item->id_footbar}' data-order='{$item->no_urut}'>
        <div class='dd-handle dd3-handle'>
            <i class='fas fa-arrows-alt'></i>
        </div>
        <div class='dd3-content'>
        {$item->judul_footbar} 
        <span style='float:right;'>
        <a href='#' data-bs-toggle='modal' data-bs-target='#newModal'
        data-form='edit' data-id='{$item->id_footbar}' class='editFootbar'>Edit</a>        
        </span>
        </div>".$this->buildMenu($footbar, $item->id_footbar) . "</li>";
        }
        return $result ?  "\n<ol class=\"dd-list\">\n$result</ol>\n" : null;
    }

    // Getter for the HTML menu builder
    public function getHTMLNav($items)
    {
     return $this->buildMenu($items);
    }

}
