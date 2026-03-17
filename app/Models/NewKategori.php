<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewKategori extends Model
{
    use HasFactory;

    protected $table = 'tb_kategori_berita';

    protected $primaryKey = 'id_kategori_berita';
    public $timestamps = false;
    protected $guarded = [];

    public function subKategori()
    {
        return $this->hasMany(NewKategori::class,'main_kategori_berita','id_kategori_berita'); 
    }

    public function buildMenu($kategori, $main_kategori_berita = null)
    {
    $result = null;
    foreach($kategori as $item)
        if ($item->main_kategori_berita == $main_kategori_berita) {
        $result .= "<li class='dd-item dd3-item' data-id='{$item->id_kategori_berita}' data-order='{$item->urut}'>
        <div class='dd-handle dd3-handle'>
            <i class='fas fa-arrows-alt'></i>
        </div>
        <div class='dd3-content'>
        {$item->nama_kategori_berita} 
        <span style='float:right;'>
        <a href='#' data-bs-toggle='modal' data-bs-target='#newModal'
        data-form='edit' data-id='{$item->id_kategori_berita}' class='editKategori'>Edit</a>
     
        <a href='#' class='deleteKategori text-danger'  data-id='{$item->id_kategori_berita}' 
            rel='{$item->id_kategori_berita}'>Delete</a>
        </span>
        </div>".$this->buildMenu($kategori, $item->id_kategori_berita) . "</li>";
        }
        return $result ?  "\n<ol class=\"dd-list\">\n$result</ol>\n" : null;
    }

    // Getter for the HTML menu builder
    public function getHTMLNav($items)
    {
     return $this->buildMenu($items);
    }

}
