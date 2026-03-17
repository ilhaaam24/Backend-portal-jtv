<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\VideoResource;
use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    //
    public function index()
    {   
        $limit = request('limit') ?? config('jp.api_paginate');
        $limit = $limit >  config('jp.maxlimit') ? config('jp.maxlimit') : $limit;

        $video = VideoResource::collection(Video::latest('id_video')->paginate($limit))
        ->additional([
            'section' => [
                "title" => "Video",
                "link" =>  config('jp.path_url_web')."api/video"
            
            ]
        ]);
        return  $video;
    }
        
    public function detailVideo($id){
        $title = Video::select('judul_video')
        ->where('id_video_yt', $id)->first();


        $detail_video =   VideoResource::make(Video::where('id_video_yt', $id)
        ->first())
        ->additional([
            'section' => [
                "title" => $title->judul_video,
                "link" =>  config('jp.path_url_web')."api/video/".$id
            
            ]
        ]);

        return  $detail_video;
    }

}
