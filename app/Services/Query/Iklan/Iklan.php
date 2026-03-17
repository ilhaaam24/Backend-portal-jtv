<?php

namespace App\Services\Query\Iklan;

use App\Models\Iklan as ModelIklan;
use Illuminate\Support\Facades\Cache;

class Iklan
{
    public function get_top_navbar()
    {
       return  cache()->lock("get_top_navbar", 10)->get(
            fn () => cache()->remember('get_topnavbar', now()->addMinutes(10), function ()  {
                   return ModelIklan::oldest('id_iklan')
                   ->where('status_iklan', '=', 1)
                   ->where('kategori', '=', 'top_navbar')
                   ->get();
             
            })
        );
    }

    public function get_bottom_navbar()
    {
        cache()->flush();
       return  cache()->lock("get_bottom_navbar", 1)->get(
            fn () => cache()->remember('get_bottomnavbar', now()->addMinutes(1), function ()  {
                   return ModelIklan::oldest('id_iklan')
                   ->where('status_iklan', '=', 1)
                   ->where('kategori', '=', 'bottom_navbar')
                   ->get();
             
            })
        );
    }

    public function get_left_body()
    {
       return  cache()->lock("get_left_body", 10)->get(
            fn () => cache()->remember('get_leftbody', now()->addMinutes(10), function ()  {
                   return ModelIklan::oldest('id_iklan')
                   ->where('status_iklan', '=', 1)
                   ->where('kategori', '=', 'left_body')
                   ->get();
             
            })
        );
    }


    public function get_sidebar()
    {
       return  cache()->lock("get_sidebar", 10)->get(
            fn () => cache()->remember('getsidebar', now()->addMinutes(10), function ()  {
                   return ModelIklan::oldest('id_iklan')
                   ->where('status_iklan', '=', 1)
                   ->where('kategori', '=', 'sidebar')
                   ->get();
             
            })
        );
    }


    public function get_bottom_news()
    {
       return  cache()->lock("get_bottom_news", 10)->get(
            fn () => cache()->remember('get_bottomnews', now()->addMinutes(10), function ()  {
                   return ModelIklan::oldest('id_iklan')
                   ->where('status_iklan', '=', 1)
                   ->where('kategori', '=', 'bottom_news')
                   ->get();
             
            })
        );
    }



    public function get_right_body()
    {
      
       return  cache()->lock("get_right_body", 10)->get(
            fn () => cache()->remember('get_rightbody', now()->addMinutes(10), function ()  {
                   return ModelIklan::oldest('id_iklan')
                   ->where('status_iklan', '=', 1)
                   ->where('kategori', '=', 'right_body')
                   ->get();
             
            })
        );
    }

    public function getpopup()
    {
        return  cache()->lock("get_popup", 10)->get(
            fn () => cache()->remember('getpopup', now()->addMinutes(10), function ()  {
                   return ModelIklan::oldest('id_iklan')
                   ->where('status_iklan', '=', 1)
                   ->where('kategori', '=', 'popup')
                   ->get();
             
            })
        );
    }


}