<?php

namespace App\Http\Controllers;

use App\Models\Biro;
use App\Models\Gallery;
use App\Models\Navigation;
use App\Models\Pengguna;
use App\Models\User;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class PenggunaController extends Controller
{
    //
    public function index()  
    {
        $tb_pengguna = Pengguna::oldest('id_pengguna')
                ->get();
        $navigation = Navigation::oldest('id')
                ->get();
        $biro = Biro::oldest('id')->get();
        
                $user = User::orderBy('id','DESC')->paginate(5);
                $all_users_with_all_their_roles = User::with('roles')->get();
                $all_users_with_all_direct_permissions = User::with('permissions')->get();
                $all_roles_in_database = Role::all()->pluck('name');
                $all_roles= Role::all();
                $users_without_any_roles = User::doesntHave('roles')->get();
        
        
                $all_users_admin = Role::where('name', 'admin')->get();

        $data = array();
        $data = [   
                'pengguna'=> $tb_pengguna,
                'roles'=> $all_roles,
                'menu'=> $navigation,
                'list_biro'=> $biro,
        ];

        return view('master.pengguna.index', $data);
    }

    public function getEditPengguna(Request $request){
        $getid = $request->id;

        $dataUser  = User::with(['pengguna' => function($query){
                                 $query->orderBy('id_pengguna', 'asc');
                         }])->with('roles')
                         ->findOrFail($getid);

        $img_pengguna = $dataUser->pengguna->gambar_pengguna;

        $url_img = config('jp.path_url_be').config('jp.path_img_profile').$img_pengguna;
        $path = public_path('assets/foto-profil/').$img_pengguna;
        $local_img = url('assets/foto-profil/'.$img_pengguna);   

        $handle = @fopen($url_img, 'r');
        if(!$handle){
                if (file_exists($path) && $img_pengguna!='') {
                        $filepath_img=  $local_img;
                }else{
                        $filepath_img= 'https://www.portaljtv.com/images/broken.webp'; 
                }
        }else{
                // return true;
                $filepath_img =  $url_img;
        }

        $data = array();
        $data = [   
                'status'    => 'success',
                'messages' => 'berhasil',
                'hasil' => $dataUser,
                'url_src' => $filepath_img,
        ];
        return $data;
    }


    private function slugify($string){
        return $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
     }

    public function penggunaStore(Request $request){
        $Now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
        $data = $request->all(); 

        $request->validate([
                'username' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255'],
                'password' => 'required',
                // 'nama_pengguna' => 'required',
                // 'gambar_pengguna' => 'required',
        ]);


        $create = User::create([
            'name' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ])->assignRole($request->id_role);


        $lastId = $create->id;
  
        if($create) {
            $insert_pengguna = [
                'username' => $request->username,
                'level' => $request->id_role,
                'nama_pengguna' => $request->nama_pengguna,
                'gambar_pengguna' => $request->gambar_pengguna,
                'seo' => $this->slugify($request->username),
                'id_user' => $lastId ,
                'id_biro' =>$request->id_biro,
            ];

            $create_pengguna = Pengguna::create($insert_pengguna);
            
            $path = ('assets/foto-profil/');
            $saveFile       = new Gallery();
            $saveFile->original_filename = $request->gambar_pengguna;
            $saveFile->title = $request->gambar_pengguna;
            $saveFile->caption = $request->gambar_pengguna;
            $saveFile->filename = $path .$request->gambar_pengguna;
            $saveFile->tipe = 'profil';
            $saveFile->save();

            $result['lastId'] = $lastId;
            $result['status'] = "success";
            $result['message'] = "Users Created successfully!";
            return response()->json($result);

        }else{
            $result['status'] = "failed";
            $result['message'] = "Video Updated Failed!";
            return response()->json($result);
        }

        return abort(500);
    }

    //Save update users
    public function penggunaStoreUpdate(Request $request){
        $Now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
        $data = $request->all(); 
      
        $request->validate([
                'username' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255'],
        ]);

        $param_update = [
                'name' => $request->username,
        ];

        $param_update_pengguna = [
                'username' => $request->username,
                'level' => $request->id_role,
                'nama_pengguna' => $request->nama_pengguna,
                'gambar_pengguna' => $request->gambar_pengguna,
                'seo' => $this->slugify($request->username),
                'id_biro' =>$request->id_biro,
        ];
    
        $update_user = User::findOrFail($request->id_user)->syncRoles($request->id_role)
        ->update($param_update);
        

        if($update_user) {
                $update_user = Pengguna::where('id_user',$request->id_user)
                ->update($param_update_pengguna);

                $path = ('assets/foto-profil/');
                $saveFile       = new Gallery();
                $saveFile->original_filename = $request->gambar_pengguna;
                $saveFile->title = $request->gambar_pengguna;
                $saveFile->caption = $request->gambar_pengguna;
                $saveFile->filename = $path .$request->gambar_pengguna;
                $saveFile->tipe = 'profil';
                $saveFile->save();

            $result['status'] = "success";
            $result['message'] = "User Updated successfully!";
            return response()->json($result);
        }else{
            $result['status'] = "failed";
            $result['message'] = "User Updated Failed!";
            return response()->json($result);
        }
        return abort(500);
    }

    //non aktif kan users
    public function updateIsActive(Request $request)
    {
        $id =  $request->get('id');
        $getUser = User::findOrFail($id);
        $status = $getUser->is_active;
        $is_active='aktif';
        
        $status == 'aktif' ? $is_active='nonaktif' : $is_active='aktif';
        $param_update = [
                'is_active' => $is_active,
        ];

        $updated = User::findOrFail($id)->update($param_update);
             
        $data = array();
        if($updated){
                if($is_active=='aktif'){
                        $data = [ 
                                'status'    => 'success',
                                'messages' => 'User Berhasil Active',  
                                'is_active' => $is_active,  
                            ];
                }else if($is_active=='nonaktif'){
                        $data = [ 
                                'status'    => 'success',
                                'messages' => 'User Berhasil Non Active', 
                                'is_active' => $is_active,   
                            ];
                }
          
        }else{
            $data = [ 
                'status'    => 'error',
                'messages' => 'gagal',  
            ];
        }
        return $data;
    }  

      // UPLOAD SINGLE DROPZONE
      public function dropzoneStore(Request $request)
      {
          $Now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
          $path = ('assets/foto-profil/');
          !is_dir($path) &&
              mkdir($path, 0755, true);
           $image = $request->file('file');
        
          $fileInfo = $image->getClientOriginalName();
          $filename = pathinfo($fileInfo, PATHINFO_FILENAME);
          $extension = pathinfo($fileInfo, PATHINFO_EXTENSION);
          $file_name= $filename.'-'.time().'.'.$extension;
      
          $imageName = 'jtv_'.time().'.'.$image->extension();
          $uploaded =  $image->move(public_path('assets/foto-profil'),$imageName);
 
          $image_full_path  = $path. $imageName;
 
    
          if($uploaded)
          {
              // $data['image_full_path'] = config('jp.path_url_web').$image_full_path;
              $data['image_full_path'] = asset('').$image_full_path;
              $data['image_path'] = $image_full_path;
              $data['image_name'] = $imageName;
              $data['status'] = 'success';
              $data['success'] = $filename;
              $data['message'] = 'Uploaded Successfully!';
          }
          else{
              $data['status'] = 'error';
              $data['success'] = $filename;
              $data['message'] = 'File not uploaded.'; 
          }      
          return response()->json($data);
         
      }
 
      public function destroyImages(Request $request)
      {
          $filename =  $request->get('filename');
          $name =  $request->get('name');
          Gallery::where('filename',$filename)->delete();
          $path = public_path('assets/foto-profil/').$name;
         
          if (file_exists($path)) {
              unlink($path);
          }
          return response()->json([ 'name'=>$name, 'src'=>$filename]);
      }  

      public function deleteGambarPengguna(Request $request)
      {
          $id =  $request->id;
            $dataPengguna = Pengguna::where('id_user',$id)->first();
            $dataPengguna->gambar_pengguna != '' ? $gmbr='' : $gmbr=$dataPengguna->gambar_pengguna;
            $dataPengguna->gambar_pengguna;
        //     return  $gmbr;
        //  return $dataPengguna->gambar_pengguna;
   
      
        if($dataPengguna->gambar_pengguna!=''){
               
                $param_update = [
                        'gambar_pengguna' => $gmbr,
                ];
                $updated = Pengguna::where('id_user',$id)->update($param_update);

                Gallery::where('original_filename',$dataPengguna->gambar_pengguna)->delete();
                $path = public_path('assets/foto-profil/').$dataPengguna->gambar_pengguna;
               
                if (file_exists($path)) {
                    unlink($path);
                }
                $data['status'] = 'success';
                $data['success'] = $dataPengguna->gambar_pengguna;
                $data['message'] = 'File deleted.'; 
       
        }else{
                $data['status'] = 'error';
                $data['success'] = $dataPengguna->gambar_pengguna;
                $data['message'] = 'File is empty.'; 
       
        }
        return response()->json($data);
      }  
    
}
