<?php

namespace App\Http\Controllers;

use App\Http\Resources\OpiniResource;
use App\Models\Gallery;
use App\Models\HitCounter;
use App\Models\Opini;
use App\Models\Pengguna;
use App\Models\TbOpini;
use App\Models\Tipetulisan;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class OpiniController extends Controller
{
    //
    public function index(){
         $opini = Opini::find('25')->penulis;
     
        dd($opini);
 
        // return $opini->penulis->nama_penulis;
    }

    public function jurnalisme_warga()
    {
        $jurnalisme_warga = OpiniResource::collection(Opini::where('status_opini', '=', 'Publish')
        // ->where('tipe_opini', 'berita')
        ->latest('id_opini')
        ->get());
        
        // return  $jurnalisme_warga;
      
        $data = array();
        $data = [   
                'berita'=> $jurnalisme_warga
        ];

        return view('konten.warga.index', $data);
    }

    // CREATE OPINI
    public function createJurnal(){
        $data = [];
        $pengguna_data = Pengguna::where('id_user', auth()->user()->id)->firstOrFail();
        $pengguna_list = Pengguna::where('level', '=', '3')
        ->oldest('id_pengguna')->get();
   
       $list_tipetulisan = Tipetulisan::oldest('id')->get();

        $data = [   
                'pengguna_data' => $pengguna_data,
                'list_pengguna' => $pengguna_list,
                'list_tipetulisan' => $list_tipetulisan,
                'status_form'   => 'create_form',
        ];

        return view('konten.warga.edit', $data);
    }

    // STORE DATA JURNALISME WARGA(OPINI)
    public function storeJurnal(Request $request)
    {
        $result = [];
        $validator =  Validator::make($request->all(),[
            'judul_opini' => 'required',
            'seo_opini' => 'required',
            'tipe_opini' => 'required',
            'artikel_opini' => 'required',
            'tag_opini' => 'required',
            'id_pengguna' => 'required',
            'rangkuman_berita' => 'required',
            'status_opini' => 'required',
        ]);

      $validator->validated();

        $param_insert = [
            'judul_opini'           => $request->judul_opini,
            'seo_opini'             => $request->seo_opini,
            'id_penulis_opini'      => $request->id_pengguna,
            'artikel_opini'         => $request->artikel_opini,
            'status_opini'          => $request->status_opini,
            'date_publish_opini'    => $request->date_publish_opini,
            'date_input_opini'      => now(),
            'tag_opini'             => $request->tag_opini,
            'tipe_opini'            => $request->tipe_opini,
            'gambar_opini'          => $request->gambar_opini,
            'caption_gambar_opini'  => $request->caption_gambar_opini,
        ];

     
        $param_hit_counter = [
            'seo_berita' => $request->seo_opini,
            'hit' => 0,
            'tipe' => 'opini',
        ];
        
        $hitcounter_cek = HitCounter::where('seo_berita', $request->seo_opini)->first();

        if($hitcounter_cek){
            $result['status'] = "error";
            $result['message'] = "SEO Opini sudah Digunakan!";
        }else{
              
               
                try {
                    //Create Hit Counter
                    $create_hitcounter = HitCounter::create($param_hit_counter);
                  
                  } catch (\Exception $e) {
                  
                    $result['status'] = "error";
                    $result['message'] = "Opini Updated Failed!";
                  }

                
                  $create = TbOpini::create($param_insert);
                  $lastId = $create->id_opini;

                  $result['lastId'] = $lastId;
                  $result['status'] = "success";
                  $result['message'] = "Opini Created successfully!";
                  $result['url'] = route('edit.jurnalismewarga', ['id' => $lastId]);
        }
        return response()->json($result);
    }


    //EDIT JURNALISME WARGA
    public function getEditJurnalisme(Request $request)
    {
    $id = $request->id;

    $pengguna_list = Pengguna::where('level', '=', '3')
     ->oldest('id_pengguna')->get();

    $list_tipetulisan = Tipetulisan::oldest('id')->get();

    /*  $menu_list = KategoriBerita::where('judul_status', '=', '1')
     ->oldest('no_urut') ->get();

     $kategori_list = TbKategoriBerita::all(); */

    $item = TbOpini::where('id_opini',$id)->firstOrFail();
     $pengguna_data = Pengguna::where('id_user', auth()->user()->id)->firstOrFail();

     $data = array();
     $data = [   
             'pengguna_data' => $pengguna_data,
             'list_pengguna' => $pengguna_list,
             'list_tipetulisan' => $list_tipetulisan,
             'item'          => $item,
             'status_form'   => 'edit_form',
     ];

      return view('konten.warga.edit', $data);
    }

    // Save Update data jurnalisme warga
    public function updateJurnal(Request $request)
    {
        $result = [];
        $Now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
        $data = $request->all(); 
        $validated = $request->validate([
            'judul_opini' => 'required',
            'seo_opini' => 'required',
            'artikel_opini' => 'required',
            'status_opini' => 'required',
            'tipe_opini' => 'required',
          
        ]);

        $param_update_jurnal = [
            'judul_opini'           => $request->judul_opini,
            'seo_opini'             => $request->seo_opini,
            'tipe_opini'            => $request->tipe_opini,
            'artikel_opini'         => $request->artikel_opini,
            'status_opini'          => $request->status_opini,
            'date_publish_opini'    => $request->date_publish_opini,
            'tag_opini'             => $request->tag_opini,
            'gambar_opini'              => $request->gambar_opini,
            'caption_gambar_opini'     => $request->caption_gambar_opini,
            'updated_at'                => $Now->format('Y-m-d H:i:s'),
        ];

        $updated =  TbOpini::where('id_opini',  $request->id_opini)->update($param_update_jurnal);

        if($updated) {
            $result['status'] = "success";
            $result['message'] = "Opini Updated successfully!";
           
        }else{
            $result['status'] = "failed";
            $result['message'] = "Post Updated Failed!";
        }
        return response()->json($result);
    }

    
     // UPLOAD SINGLE DROPZONE
     public function dropzoneStore(Request $request)
     {
         $Now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
         $path = ('assets/upload-gambar/');

          !is_dir($path) &&
             mkdir($path, 0755, true);


         $image = $request->file('file');
       

         $fileInfo = $image->getClientOriginalName();
         $filename = pathinfo($fileInfo, PATHINFO_FILENAME);
         $extension = pathinfo($fileInfo, PATHINFO_EXTENSION);
         $file_name= $filename.'-'.time().'.'.$extension;
     
         $imageName = 'jtv_opini_'.time().'.'.$image->extension();
         $image->move(public_path('assets/upload-gambar'),$imageName);

         $image_full_path  = $path. $imageName;

         $saveFile       = new Gallery();
         $saveFile->original_filename = $imageName;
         $saveFile->filename = $image_full_path;
         $saveFile->tipe = 'opini';
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
         $path = public_path('assets/upload-gambar/').$name;
        
         if (file_exists($path)) {
             unlink($path);
         }
         return response()->json([ 'name'=>$name, 'src'=>$filename]);
     }
     
     public function getImages_galleryOpini()
     {
         $tableImages[] =array();
         $images = Gallery::where('tipe','opini')->get();

    
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

     public function getImageSearchOpini(Request $request)
    {
        $search =  $request->keyword;

        $images_gets = '';

        if (trim($request->keyword)) {
            $images_gets = Gallery::where('original_filename','LIKE',"%{$search}%")
                        ->where('tipe','opini')
                        ->orderBy('created_at','DESC')->limit(20)->get();
                        // ->orderBy('created_at','DESC')->paginate(20);

                            
        }

        return response()->json([
            'list_getimages' => $images_gets
         ]);    
    }


    public function getImages_gallery(Request $request)
    {
        $card_class = "crd-img";
        $img_class = "img_gallerys";
    
        $tableImages[] =array();
        $images = Gallery::where('tipe','opini')->paginate(24);
        $exist = '';
    
        $html = '';
        if ($request->ajax()) {
          
            foreach ($images as $row) {
                $filename =  $row->original_filename;
                $filepath = public_path('assets/upload-gambar/').$filename;

                if (Storage::exists("upload-gambar/$filename")) {
                    $path_server = asset("assets/upload-gambar/$filename");
                }else{
                    $path_server =  asset(config('jp.path_url_no_img'));
                }

                $html.='<div class="col-4 col-md-2 mb-2">
                <div class="d-inline-flex position-relative mt-3">              
                <div class="card '.$card_class.'  gap-2 d-grid border-light" id="lib_img_'.$row->id.'" >
                    <div id="image_'.$row->id.'">
                    <img class="b-lazy '.$img_class.' img rounded-top" 
                    data-id="img_'.$row->id.'" 
                    data-originalname="'.$row->original_filename.'"
                    data-filename="'.$row->filename.'"
                    data-imagecaption="'.$row->caption.'"
                    data-imagedescription="'.$row->description.'"
                    data-src="'.$path_server.'"
                    data-bs-toggle="tooltip"
                    title="'.$row->original_filename.'"
                    alt="'.$row->original_filename.'"
                    src="'.$path_server.'" max-width: 60px; height="120px">
                    <div class="w-100 badge bg-label-light text-muted rounded-bottom img_name_preview">'.substr($row->original_filename, 0, 10).'</div>
                    </div>
                </div>
                
            </div>
            </div>
            ';
            }
          
        }
   
        if($images){
            // $data['data'] = $data_img;
            $data['images'] = $images;
            $data['success'] = 1;
            $data['message'] = 'Data Images get Successfully!';
            // $data['pagination'] = (string)$images->links('pagination::bootstrap-5');
            $data['html'] = $html;
           
        }else{
            $data['success'] = 0;
            $data['message'] = 'Failed to load images!';
        }

        
        return response()->json($data);
    }

    public function getImageSearch(Request $request)
    {
        $search =  $request->keyword;

       
            $card_class = "crd-img";
            $img_class = "img_gallerys";
     
        $images_gets = '';

        if (trim($request->keyword)) {
            $images_gets = Gallery::where('original_filename','LIKE',"%{$search}%")
                            ->where('tipe','opini')
                            ->orderBy('created_at','DESC')->paginate(24);   

               
        }

     
        $html = '';
        if ($request->ajax()) {
          
            foreach ($images_gets as $row) {

                $src_img = $row->imageGallery();
                $html.='<div class="col-2 mb-2">
                <div class="card '.$card_class.' gap-2 d-grid border-light" id="lib_img_'.$row->id.'" >
                    <div id="image_'.$row->id.'">
                    <img class="b-lazy '.$img_class.' img rounded-top" 
                    data-id="img_'.$row->id.'" 
                    data-originalname="'.$row->original_filename.'"
                    data-filename="'.$row->filename.'"
                    data-imagecaption="'.$row->caption.'"
                    data-imagedescription="'.$row->description.'"
                    data-src="'.$src_img.'"
                    data-bs-toggle="tooltip"
                    title="'.$row->original_filename.'"
                    alt="'.$row->original_filename.'"
                    src="'.url("/").'/'.$row->filename.'" max-width: 60px; height="120px">
                    <div class="w-100 badge bg-label-light text-muted rounded-bottom img_name_preview">'.substr($row->original_filename, 0, 10).'</div>
                    </div>
                </div>
            </div>
            ';
            }
        }

        return response()->json([
            'list_getimages' => $images_gets,
            'html'           => $html
         ]);    
    }

    public function destroyJurnal(Request $request)
    {
        $id =  $request->get('id');
        $getdata = TbOpini::where('id_opini',$id)->first();

        if($getdata){
            $filename =  $getdata->gambar_opini;
            Gallery::where('filename',$filename)->delete();
            $path = app_path($filename);
    
            $deleted_image = Gallery::where('filename', $filename)->delete();
           
            if (file_exists($path)) {
                unlink($path);
            }
            $deleted = TbOpini::where('id_opini', $id)->delete();
            if( $deleted){
                return redirect()->route('konten.jurnalismewarga');
            }
            return response()->json([ 'status'=>'success', 'src'=>$filename, 'path'=>$path]);
        }
       
    }  



}
