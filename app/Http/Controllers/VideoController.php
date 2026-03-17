<?php

namespace App\Http\Controllers;

use App\Models\Video;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    //
    public function index()
    {
        $video = Video::latest('id_video')
        ->get();

        $data = array();
        $data = [   
                'video'=> $video
        ];

        return view('konten.video.index', $data);
    }


    public function create()  
    {  
     $data = array();
     $data = [   
          
             'status_form'   => 'create_form',
     ];

        return view('konten.video.create', $data);
    }

    public function edit(Request $request)  
    {  
      $id = $request->id;

     $item = Video::where('id_video',$id)->first();
    
     $data = array();
         $data = [   
             'item'          => $item,
             'status_form'   => 'edit_form',
     ];

        return view('konten.video.create', $data);
    }

    public function store(Request $request)
    {
        $Now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
  
         $data = $request->all(); 
    
        
        // return $data;
        $validator =  Validator::make($request->all(),[
            'judul_video' => 'required',
            'id_video_yt' => 'required',
            'thumbnail' => 'required',

        ]);

        // Retrieve the validated input...
        $validated = $validator->validated();

        $param_insert = [
            'judul_video' => $request->judul_video,
            'id_video_yt' => $request->id_video_yt,
            'thumbnail' => $request->thumbnail,
            'date'     => $Now->format('Y-m-d H:i:s'),
        ];

        $create = Video::create($param_insert);
        $lastId = $create->id_video;

  
        if($create) {
            $result['lastId'] = $lastId;
            $result['status'] = "success";
            $result['message'] = "Video Created successfully!";

            $result['url'] = route('video.edit', ['id' => $lastId]);
            return response()->json($result);

        }else{
            $result['status'] = "failed";
        
            $result['message'] = "Video Updated Failed!";
            return response()->json($result);
        }

        return abort(500);
       
    }

    public function update(Request $request)
    {
        $Now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
  
         $data = $request->all(); 
    
        
        // return $data;
        $validator =  Validator::make($request->all(),[
            'judul_video' => 'required',
            'id_video_yt' => 'required',
            'thumbnail' => 'required',

        ]);

        // Retrieve the validated input...
        $validated = $validator->validated();

        $param_update = [
            'judul_video' => $request->judul_video,
            'id_video_yt' => $request->id_video_yt,
            'thumbnail' => $request->thumbnail,
            'date'     => $Now->format('Y-m-d H:i:s'),
        ];

        $updated = Video::where('id_video', $request->id_video)
                ->update($param_update);


  
        if($updated) {
            $result['lastId'] = $request->id_video;
            $result['status'] = "success";
            $result['message'] = "Video Updated successfully!";

            $result['url'] = route('video.edit', ['id' => $request->id_video]);
            return response()->json($result);

        }else{
            $result['status'] = "failed";
        
            $result['message'] = "Video Updated Failed!";
            return response()->json($result);
        }

        return abort(500);
       
    }

    public function delete(Request $request)
    {
        $id = $request->id;

        $param_update = [
            'status_video'     => 0,
        ];

        $updated = Video::where('id_video', $id)
                ->update($param_update);

                if($updated) {
                    $result['status'] = "success";
                    $result['message'] = "Video Updated successfully!";

                }else{
                    $result['status'] = "failed";
                    $result['message'] = "Video Updated Failed!";
                   
                }

                return response()->json($result);
       
    }


}
