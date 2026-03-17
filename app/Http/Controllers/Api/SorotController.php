<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BeritaResource;
use App\Http\Resources\SorotDetailResource;
use App\Http\Resources\SorotResource;
use App\Models\Berita;
use App\Models\Sorot;
use App\Services\Query\Sorot\Sorot as SorotSorot;
use Illuminate\Http\Request;

class SorotController extends Controller
{
    //
    public function index(SorotSorot $sorot)
    {
       
        $limit = request('limit') ?? config('jp.api_paginate');
        $limit = $limit >  config('jp.maxlimit') ? config('jp.maxlimit') : $limit;

        $section =[
            'section' => [
                'title' => 'Sorot',
                'link' =>  config('jp.path_url_be')."api/sorot/",
            ]];
        $sorot = SorotResource::collection($sorot->getSorot($limit))
        ->additional($section);

        return $sorot;
    }

    public function detailSorot($id, SorotSorot $sorot)
    {
        $title = Sorot::select('judul')
        ->where('status', 1)
        ->where('tag', $id)->firstOrFail();

        $limit = request('limit') ?? config('jp.api_paginate');
        $limit = $limit >  config('jp.maxlimit') ? config('jp.maxlimit') : $limit;

        // return cache('DetailSorot');
        $section =[
            'section' => [
                'title' => $title->judul,
                'link' =>  config('jp.path_url_be')."api/sorot/".$id,
            ]];
        $sorot = 
        SorotDetailResource::collection($sorot->getDetailSorot($id, $limit))
        ->additional($section);

        return $sorot;
    }
}
