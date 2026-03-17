<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\IklanResource;
use App\Services\Query\Iklan\Iklan as IklanIklan;

class IklanController extends Controller
{
    //
    public function index(IklanIklan $iklan)
    {   

        $limit = request('limit') ?? config('jp.api_paginate');
        $limit = $limit >  config('jp.maxlimit') ? config('jp.maxlimit') : $limit;

        $top_navbar = IklanResource::collection($iklan->get_top_navbar($limit));
        $bottom_navbar = IklanResource::collection($iklan->get_bottom_navbar($limit));
        $left_body = IklanResource::collection($iklan->get_left_body($limit));
        $sidebar = IklanResource::collection($iklan->get_sidebar($limit));
        $bottom_news = IklanResource::collection($iklan->get_bottom_news($limit));  
        $right_body = IklanResource::collection($iklan->get_right_body($limit));
         $popup = IklanResource::collection($iklan->getpopup());

        return  $section =[
            'top_navbar' =>  $top_navbar ,
            'bottom_navbar' =>  $bottom_navbar ,
            'left_body' =>  $left_body ,
            'sidebar' =>  $sidebar ,
            'bottom_news' =>  $bottom_news ,
            'right_body' =>  $right_body ,
            'popup' =>  $popup ,
  
        ];

    }

}
