<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\NewKategori;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    //
    public function index()
    {
        $gallery = Gallery::oldest('id')->get();
        $kategori_list = NewKategori::where('main_kategori_berita', null)
                ->where('status_kategori_berita', 1)->get();
        $data = array();
        $data = [   
                'data_gallery'=> $gallery,
                'kategori_list'=> $kategori_list,
        ];

        return view('media.index', $data);
    }

    public function deleteImages(Request $request)
    {
        $id =  $request->get('id');
        $getdata = Gallery::where('id',$id)->first();
        $name = $getdata->original_filename;
        $path = public_path('assets/upload-gambar/').$name;
       
        if (file_exists($path)) {
            unlink($path);
        }

        $deleteData = Gallery::where('id',$id)->delete();

        if($deleteData)
        {
            $data['image_name'] = $name;
            $data['src'] = $path;
            $data['status'] = 'success';
            $data['success'] = 1;
            $data['message'] = 'Delete Successfully!';
        }
        else{
            $data['success'] = 0;
            $data['status'] = 'error';
            $data['message'] = 'Failed.'; 
        }
        return response()->json($data);

    }  

    public function save_img_gallery(Request $request){
        $Now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
        $data = $request->all(); 
        $path ='';
        if($request->tipe_gambar =='berita' || $request->tipe_gambar =='opini'){
            $path = ('assets/upload-gambar/');
        }else if($request->tipe_gambar =='sorot'){
            $path = ('assets/sorot/');
        }else if($request->tipe_gambar =='iklan'){
            $path = ('assets/iklan/');
        }
      
        $image_full_path = $path. $request->original_filename;

        $param_insert = [
            'original_filename' => $request->original_filename,
            'title'             => $request->image_name_add,
            'filename'          => $image_full_path,
            'caption'           => $request->image_caption,
            'tipe'              => $request->tipe_gambar,
            'id_kategori'       => $request->id_kategori,
            'created_at'        => $Now->format('Y-m-d H:i:s'),
            'updated_at'        => $Now->format('Y-m-d H:i:s'),
        ];

        $image_cek = Gallery::where('original_filename', $request->image_name_add)->first();

        if($image_cek != null){
            $param_upd_gallery = [
                'title' => $request->image_name_add,
                'caption' => $request->image_caption,
                'tipe' => $request->tipe_gambar,
                'updated_at'     => $Now->format('Y-m-d H:i:s'),
            ];
            $update_gallery = Gallery::where('original_filename', $request->original_filename)
                            ->update($param_upd_gallery);
            if($update_gallery)
            {
                $data['status'] = 'success';
                $data['success'] = 1;
                $data['message'] = 'Updated Successfully!';
            }
            else{
                $data['status'] = 'error';
                $data['success'] = 0;
                $data['message'] = 'Failed!';
            }            
        }
        else{
            $create = Gallery::create($param_insert);
            $lastId = $create->id;
            if($create)
                {
                    $data['image_full_path'] = asset('').$image_full_path;
                    $data['image_path'] = $image_full_path;
                    $data['image_name'] = $request->image_name_add;
                    $data['status'] = 'success';
                    $data['success'] = 1;
                    $data['message'] = 'Uploaded Successfully!';
                }
                else{
                    $data['status'] = 'error';
                    $data['success'] = 0;
                    $data['message'] = 'File not uploaded.'; 
                }
        }
        return response()->json($data);
    }

    public function update_img_gallery(Request $request){
        $Now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
        $data = $request->all(); 
      
        $image_cek = Gallery::where('id', $request->id_gallery)->first();

        if($image_cek != null){
            $param_upd_gallery = [
                'title' => $request->image_name,
                'caption' => $request->image_caption_text,
                'tipe' => $request->image_tipe,
                'updated_at'     => $Now->format('Y-m-d H:i:s'),
            ];
            $update_gallery = Gallery::where('id', $request->id_gallery)
                            ->update($param_upd_gallery);
            if($update_gallery)
            {
                $data['status'] = 'success';
                $data['success'] = 1;
                $data['message'] = 'Updated Successfully!';
            }
            else{
                $data['status'] = 'error';
                $data['success'] = 0;
                $data['message'] = 'Failed!';
            }            
        }
        else{
            
                    $data['status'] = 'error';
                    $data['success'] = 0;
                    $data['message'] = 'Data Tidak ada.'; 
                
        }
        return response()->json($data);
    }

     // UPLOAD SINGLE DROPZONE
     public function dropzoneStore(Request $request)
     {
         $Now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
          $folder_path = $request->folder_path;
            
         if($folder_path=='berita' || $folder_path=='opini'){
             $path = ('assets/upload-gambar/');
         }else if($folder_path=='iklan'){
             $path = ('assets/iklan/');
         }else if($folder_path=='sorot'){
             $path = ('assets/sorot/');
         }


         $image = $request->file('file');
       
         $fileInfo = $image->getClientOriginalName();
         $filename = pathinfo($fileInfo, PATHINFO_FILENAME);
         $extension = pathinfo($fileInfo, PATHINFO_EXTENSION);
         $file_name= $filename.'-'.time().'.'.$extension;
     
         $imageName = 'jtv_'.time().'.'.$image->extension();
        $new_folder = public_path($path); 
        $uploaded =  $image->move($new_folder,$imageName);

         $image_full_path  = $path. $imageName;

   
         if($uploaded)
         {
             // $data['image_full_path'] = config('jp.path_url_web').$image_full_path;
             $data['image_full_path'] = asset('').$image_full_path;
             $data['image_path'] = $image_full_path;
             $data['image_name'] = $imageName;
             $data['status'] = 'success';
             $data['success'] = 1;
             $data['message'] = 'Uploaded Successfully!';
         }
         else{
             $data['status'] = 'error';
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
         $path = public_path($filename);
        
         if (file_exists($path)) {
             unlink($path);
         }
         return response()->json([ 'name'=>$name, 'src'=>$filename]);
     }  


}
