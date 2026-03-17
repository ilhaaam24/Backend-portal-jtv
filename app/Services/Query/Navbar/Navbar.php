<?php

namespace App\Services\Query\Navbar;

use App\Models\Navbar as ModelsNavbar;

class Navbar
{
    public function getKategori()
    {
        return  cache()->lock("get_Kategori".now(), 10)->get(
            fn () => cache()->remember('Kategori'.now(), now()->addMinutes(5), function ()  {
                   return ModelsNavbar::oldest('no_urut')
                   ->where('is_active','=', 1)
                   ->where('id_parent','=', null)
                   ->get();
             
            })
        );
    }

    public function getRubrik()
    {
        return  cache()->lock("get_Rubrik", 10)->get(
            fn () => cache()->remember('Rubrik'.now(), now()->addMinutes(5), function () {
                   return ModelsNavbar::oldest('judul_navbar')
                   ->where('rubrik',  '=', 1)
                   ->get();
             
            })
        );
    }


}