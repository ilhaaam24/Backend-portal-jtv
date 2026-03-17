<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Pengguna;
use App\Models\Sorot;
use App\Models\Tag;
use App\Models\TaxonomyTagging;
use Illuminate\Http\Request;
use DateTime;
use DateTimeZone;
use Illuminate\Console\View\Components\Alert;

class SorotController extends Controller
{
    //
    public function index()  
    {
        $sorot_data = Sorot::oldest('no_urut')->get();

        $data = array();
        $data = [ 'sorot_data'=> $sorot_data ];

        return view('konten.sorot.index', $data);
    }

    public function create()  
    {
        $pengguna_data = Pengguna::where('id_user', auth()->user()->id)->first();
       
        $tag_list = Tag::select('tb_tag.id_tag', 'tb_tag.seo_tag',  'tb_tag.nama_tag', Tag::raw('count(tb_tag.id_tag) as count_used'))
                    ->join('tagging', 'tagging.id_tag','=','tb_tag.id_tag')
                    ->groupBy('tb_tag.id_tag', 'tb_tag.seo_tag', 'tb_tag.nama_tag')
                    ->latest('count_used')
                    ->get();

        $data = array();
        $data = [
                'pengguna_data' => $pengguna_data,   
                'list_tag'      => $tag_list,
                'status_form'   => 'create_form',
        ];

        return view('konten.sorot.edit', $data);
    }

    public function edit(Request $request)  
    {  
       $id = $request->id;

        $item = Sorot::where('no_urut',$id)->first();
        $pengguna_data = Pengguna::where('id_user', auth()->user()->id)->first();

        $tag_list = Tag::select('tb_tag.id_tag', 'tb_tag.seo_tag',  'tb_tag.nama_tag', Tag::raw('count(tb_tag.id_tag) as count_used'))
                    ->join('tagging', 'tagging.id_tag','=','tb_tag.id_tag')
                    ->groupBy('tb_tag.id_tag', 'tb_tag.seo_tag', 'tb_tag.nama_tag')
                    ->latest('count_used')
                    ->get();
     
       $sorot = json_decode($item->with('tag')->where('no_urut',$id)->first());

    
      $tag_sorot = Tag::where('seo_tag', $item->tag)->first();

     $data = array();
     $data = [   
             'pengguna_data' => $pengguna_data,
             'item'          => $item,
             'tag'           => $sorot,
             'list_tag'      => $tag_list,
             'status_form'   => 'edit_form',
     ];

        return view('konten.sorot.edit', $data);
    }

    public function store(Request $request)
    {
        $Now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
     
        $data = $request->all(); 

        $validated = $request->validate([
            'judul' => 'required',
            'tag' => 'required',
        ]);

      /*   $data_tag = array_column(json_decode($request->tag_sorot), 'value'); // item

        foreach($data_tag as $item){
            $cek_tag = Tag::where('nama_tag', $item)->first();
            ($cek_tag) ? $seo_tag = $cek_tag->seo_tag:  $seo_tag='';
 
        }
          */

        $param_insert = [
            'judul'     => $request->judul,
            'tag'       => $request->tag,
            'photo'     => $request->addimage_sorot,            
        ];

    
        $create = Sorot::create($param_insert);
        $lastId = $create->id;
    
        if($create) {
            $result['lastid'] = $lastId;
            $result['url'] = route('konten.editsorot', ['id' => $lastId]);
            $result['status'] = "success";
            $result['message'] = "Post Updated successfully!";
            return response()->json($result);

        }else{
            $result['status'] = "failed";
            $result['message'] = "Post Updated Failed!";
            return response()->json($result);
            // return abort(500);
        }
       
    }

    public function delete(Request $request)  
    {  
      $id = $request->id;

     $param_updatestatus = [
        'status'     => 0,  
    ];


     $updated =  Sorot::where('no_urut',  $request->id)
     ->update($param_updatestatus);

     if($updated) {
        $result['status'] = "success";
        $result['message'] = "Sorot Deleted successfully!";
    
    }else{
        $result['status'] = "failed";
        $result['message'] = "Sorot Delete Failed!";
    
    }
    return response()->json($result);
       
    }

    public function update(Request $request)
    {
        $Now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
        $data = $request->all(); 

        $validated = $request->validate([
            'judul' => 'required',
            'tag' => 'required',
        ]);

        $data_tag = array_column(json_decode($request->tag_sorot), 'value'); // item

        foreach($data_tag as $item){
            $cek_tag = Tag::where('nama_tag', $item)->first();
            ($cek_tag) ? $seo_tag = $cek_tag->seo_tag:  $seo_tag='';
        }
         
        $param_update = [
            'judul'     => $request->judul,
            'tag'       => $request->tag,
            'photo'     => $request->addimage_sorot,            
        ];

        $updated =  Sorot::where('no_urut',  $request->id_sorot)
            ->update($param_update);
           
        if($updated) {
            $result['status'] = "success";
            $result['message'] = "Sorot Updated successfully!";
            return response()->json($result);
            // return redirect()->route('edit.berita', ['id' => $lastId]);
        }else{
            $result['status'] = "failed";
            $result['message'] = "Sorot Updated Failed!";
       
            return response()->json($result);
            // return abort(500);
        }
       
    }


     // UPLOAD SINGLE DROPZONE
     public function dropzoneStore(Request $request)
     {
         $Now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
         $path = ('assets/sorot/');

         !is_dir($path) &&
             mkdir($path, 0755, true);


         $image = $request->file('file');
       

         $fileInfo = $image->getClientOriginalName();
         $filename = pathinfo($fileInfo, PATHINFO_FILENAME);
         $extension = pathinfo($fileInfo, PATHINFO_EXTENSION);
         $file_name= $filename.'-'.time().'.'.$extension;
     
         $imageName = 'jtv_'.time().'.'.$image->extension();
         $image->move(public_path('assets/sorot'),$imageName);

         $image_full_path  = $path. $imageName;

         $saveFile       = new Gallery();
         $saveFile->original_filename = $imageName;
         $saveFile->filename = $image_full_path;
         $saveFile->tipe = 'sorot';
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
         $path = public_path('assets/sorot/').$name;
        
         if (file_exists($path)) {
             unlink($path);
         }
         return response()->json([ 'name'=>$name, 'src'=>$filename]);
     } 
     
     public function getImages_gallerySorot1()
     {
         $tableImages[] =array();
         $images = Gallery::where('tipe', 'sorot')->get()->toArray();

    
         if($images){
             $data['images'] = $images;
             $data['success'] = 1;
             $data['message'] = 'Data Images get Successfully!';
         }else{
             $data['success'] = 0;
             $data['message'] = 'Failed to load images!';
         }
         


        /*  foreach ( $files as $file ) {
             if ($file !='.' && $file !='..' && in_array($file,$tableImages)) {       
                 $obj['name'] = $file;
                 $file_path = public_path('assets/upload-gambar/').$file;
                 $obj['size'] = filesize($file_path);          
                 $obj['path'] = url('public/assets/upload-gambar/'.$file);
                 $data[] = $obj;
                 dd($obj);
             }
           
         } */
         // dd($data);
         return response()->json($data);
     }

     
    public function getImageSearchsorot(Request $request)
    {
        $search =  $request->keyword;

        $images_gets = '';

        if (trim($request->keyword)) {
            $images_gets = Gallery::where('original_filename','LIKE',"%{$search}%")
                            ->where('tipe', 'sorot')
                            ->orderBy('created_at','DESC')->limit(12)->get();

                            
        }

        return response()->json([
            'list_getimages' => $images_gets
         ]);    
    }


    public function getImages_sorot_gallery(Request $request)
    {
   
            $card_class = "crd-img";
            $img_class = "img_gallerys";
     

        $tableImages[] =array();
        // $images = Gallery::all()->toArray();
        $images = Gallery::latest('created_at')->where('tipe','sorot')->paginate(48);
        $exist = '';
        $html = '';
        if ($request->ajax()) {
          
            foreach ($images as $row) {
                $filename =  $row->original_filename;
                $filepath = public_path('assets/sorot/').$filename;
                $filepath_img = config('jp.path_url_be').config('jp.path_img_sorot').$filename;
                if (file_exists($filepath_img)) {
                    $path_server =  $filepath_img;
                }else{
                    $path_server = 'https://www.portaljtv.com/images/broken.webp';    
                }

                if (file_exists($filepath)) {
                    $exist = 1;
                    $file_path_img = public_path('assets/sorot/').$filename;
                    $file_size = filesize($file_path_img);  
                    $file_path = url('assets/sorot/'.$filename);             
                    // echo "The file $file_path exists";
                } else {
                    $exist = 0;
                    $file_size = 0;
                    $file_path = 'https://www.portaljtv.com/images/broken.webp';    
                    // echo "The file $file_path does not exist";
                }


                $html.='<div class="col-2 mb-2">
                <div class="d-inline-flex position-relative mt-3">
                      
              
                <div class="card '.$card_class.'  gap-2 d-grid border-light" id="lib_img_'.$row->id.'" >
                    <div id="image_'.$row->id.'">
                    <img class="b-lazy '.$img_class.' img rounded-top" 
                    data-id="img_'.$row->id.'" 
                    data-originalname="'.$row->original_filename.'"
                    data-filename="'.$row->filename.'"
                    data-imagecaption="'.$row->caption.'"
                    data-imagedescription="'.$row->description.'"
                    data-src="'.$file_path.'"
                    data-bs-toggle="tooltip"
                    title="'.$row->caption.'"
                    alt="'.$row->original_filename.'"
                    src="'.$file_path.'" max-width: 60px; height="120px">
                    <div class="w-100 badge bg-label-light text-muted rounded-bottom img_name_preview">'.substr($row->caption, 0, 13).'</div>
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

}
