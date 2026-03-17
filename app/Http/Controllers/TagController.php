<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Services\Query\News\TagService;
use Illuminate\Http\Request;

class TagController extends Controller
{
    //
    public function index()
    {

    }

    public function listTags()
    {
        
    }

    public function WhitelistTags(TagService $tags)
    {
        $limit = request('limit') ?? config('jp.api_paginate');
        $limit = $limit >  config('jp.maxlimit') ? config('jp.maxlimit') : $limit;

        $section =[
            'section' => [
                "title" => "Whitelist Tags",
                "link" =>  config('jp.path_url_be')."api/news/breaking"
            ]];
        
        return $whitelist =  $tags->getWhitelistTag($limit); 
    
    }
    
}
