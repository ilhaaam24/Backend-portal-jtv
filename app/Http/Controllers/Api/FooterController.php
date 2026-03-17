<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Footer;
use App\Http\Resources\FooterResource;

class FooterController extends Controller
{
    //
    public function index(){
    $socmed=   FooterResource::make(Footer::first())
    ->additional(
        ['section' => [
            'title' => 'Social Media',
            'link' =>  config('jp.path_url_web')."api/socialmedia",
            ]] 
    );
    
    return  $socmed;
    }
}
