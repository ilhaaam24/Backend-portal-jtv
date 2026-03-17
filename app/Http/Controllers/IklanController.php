<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Iklan;
use App\Models\Instagram;
use App\Models\KategoriIklan;
use App\Models\Livereport;
use App\Models\Pengguna;
use App\Models\Video;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IklanController extends Controller
{
    //
    public function index()  
    {
        $iklan = Iklan::oldest('id_iklan')
                ->get();
        

        $data = array();
        $data = [   
                'iklan'=> $iklan
        ];

        return view('konten.iklan.index', $data);
    }

    public function statusiklan(Request $request)
    {
        $data = $request->all(); 
        $validated = $request->validate([
            'id_iklan' => 'id',
        ]);

        $value = 0;
        $dataiklan =  Iklan::where('id_iklan',  $request->id)->firstOrFail();
        $val_status =  $dataiklan->status_iklan;
        $val_status == 1 ? $value=0 : $value=1;

        $dataupdate['status_iklan'] = $value;

        $updated =  Iklan::where('id_iklan',  $request->id)->update($dataupdate);
        if($updated &&  $value==1) {
            $result['status'] = "success";
            $result['message'] = "Berhasil Aktifkan Iklan!";

        }else if($updated &&  $value==0) {
            $result['status'] = "success";
            $result['message'] = "Berhasil Non Aktif Iklan!";

        }else{
            $result['status'] = "failed";
            $result['message'] = "data gagal!";
        }
        return response()->json($result);
  }  

    public function editiklan(Request $request)  
    {  
      $id = $request->id;

     $item = Iklan::where('id_iklan',$id)->first();
     $pengguna_data = Pengguna::where('id_user', auth()->user()->id)->first();
        $kategori_iklan = KategoriIklan::get();
     $get_kategori_iklan = Iklan::select('kategori', 'id_iklan')->where('status_iklan', 1)
                            ->groupBy('kategori')
                            ->oldest('id_iklan')->get();

    $filename =  $item->gambar_iklan;
    if($filename != ''){
        $filepath_img = config('jp.path_url_be').config('jp.path_img_iklan').$filename;

        $img_gallery = Gallery::where('original_filename',$filename)->firstOrFail();

            $original_filename =  $img_gallery->original_filename;    //path original
          
                if (Storage::exists("iklan/$original_filename")) {
                    $path_server = asset("assets/iklan/$original_filename");
                }else{
                    $path_server =  asset(config('jp.path_url_no_img'));
                }
    }else{
        $path_server =  asset(config('jp.path_url_no_img'));
        $original_filename =  '';  
    }

     $data = array();
     $data = [   
             'pengguna_data' => $pengguna_data,
             'item'          => $item,
             'get_kategori_iklan' => $kategori_iklan,
             'gambar_iklan_utama' => $path_server,
             'gambar_iklan_file' => $original_filename,
             'status_form'   => 'edit_form',
     ];

        return view('konten.iklan.edit', $data);
    }


    public function updateiklan(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required',
            'kategori' => 'required',
        ]);

        $param_update = [
            'nama_iklan'        => $request->judul,
            'keterangan_iklan'  => $request->keterangan_iklan,
            'gambar_iklan'      => $request->addimage_iklan,            
            'kategori'          => $request->kategori,            
            'link'              => $request->link_iklan,            
        ];
    
        $updated =  Iklan::where('id_iklan',  $request->id_iklan)
            ->update($param_update);
           
        if($updated) {
            $result['status'] = "success";
            $result['message'] = "Iklan Updated successfully!";
            return response()->json($result);
        }else{
            $result['status'] = "failed";
            $result['message'] = "Iklan Updated Failed!";
       
            return response()->json($result);
        }
       
    }

     // UPLOAD SINGLE DROPZONE
     public function dropzoneStore(Request $request)
     {
         $Now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
         $path = ('assets/iklan/');

         !is_dir($path) &&
             mkdir($path, 0755, true);

         $image = $request->file('file');
       
         $fileInfo = $image->getClientOriginalName();
         $filename = pathinfo($fileInfo, PATHINFO_FILENAME);
         $extension = pathinfo($fileInfo, PATHINFO_EXTENSION);
         $file_name= $filename.'-'.time().'.'.$extension;
     
         $imageName = 'jtv_'.time().'.'.$image->extension();
         $image->move(public_path('assets/iklan'),$imageName);

         $image_full_path  = $path. $imageName;

         $saveFile       = new Gallery();
         $saveFile->original_filename = $imageName;
         $saveFile->filename = $image_full_path;
         $saveFile->tipe = 'iklan';
         $saveFile->save();
     
         if($saveFile->save())
         {
             // $data['image_full_path'] = config('jp.path_url_web').$image_full_path;
             $data['image_full_path'] = asset('').$image_full_path;
             $data['image_path'] = $image_full_path;
             $data['image_name'] = $imageName;
             $data['success'] = 1;
             $data['message'] = 'Uploaded Successfully!';
         }
         else{
             $data['success'] = 0;
             $data['message'] = 'File not uploaded.'; 
         }
         return response()->json($data);
     }

     public function destroyImages(Request $request)
     {
         $filename =  $request->get('filename');
         $name =  $request->get('name');
         Gallery::where('filename',$filename)->delete();
         $path = public_path('assets/iklan/').$name;
        
         if (file_exists($path)) {
             unlink($path);
         }
         return response()->json([ 'name'=>$name, 'src'=>$filename]);
     } 

     public function getImages_galleryiklan()
     {
         $tableImages[] =array();
         $images = Gallery::where('tipe', 'iklan')->get()->toArray();

         if($images){
             $data['images'] = $images;
             $data['success'] = 1;
             $data['message'] = 'Data Images get Successfully!';
         }else{
             $data['success'] = 0;
             $data['message'] = 'Failed to load images!';
         }
         return response()->json($data);
     }

     
    public function getImageSearchiklan(Request $request)
    {
        $search =  $request->keyword;

        $images_gets = '';

        if (trim($request->keyword)) {
            $images_gets = Gallery::where('original_filename','LIKE',"%{$search}%")
                            ->where('tipe', 'iklan')
                            ->orderBy('created_at','DESC')->limit(12)->get();
        }

        return response()->json([
            'list_getimages' => $images_gets
         ]);    
    }
     

    public function livereport()  
    {
        $livereport = Livereport::where('status_livereport',0)
                ->oldest('id_livereport')
                ->get();

        $data = array();
        $data = ['livereport'=> $livereport ];

        return view('konten.siaranlangsung.index', $data);
    }

    public function video()  
    {
        $video_list = Video::oldest('id_video')
                ->get();

        $data = array();
        $data = [   
                'video'=> $video_list
        ];

        return view('konten.video.index', $data);
    }

    public function instagram_api()  
    {
        $instagram_api = Instagram::get();

        $data = array();
        $data = [   
                'instagram_api'=> $instagram_api
        ];

        return view('konten.instagram.index', $data);
    }
}
