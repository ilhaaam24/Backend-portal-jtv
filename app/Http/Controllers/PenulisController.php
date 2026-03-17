<?php

namespace App\Http\Controllers;

use App\Models\Penulis;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PenulisController extends Controller
{
    //
    public function index()  
    {
        $tb_penulis = Penulis::oldest('id_penulis')
                ->get();

        $data = array();
        $data = [   
                'penulis'=> $tb_penulis
        ];

        return view('master.penulis.index', $data);
    }

    public function getEditPengguna(Request $request){
        $getid = $request->id;
        $data = [];

        $getPenulis = Penulis::where('id_penulis', $getid)->first();

       if($getPenulis){
            $data = [   
                'status'    => 'success',
                'messages' => 'berhasil',
                'hasil' => $getPenulis,
            ];
       }else{
        $data = [   
            'status'    => 'error',
            'messages' => 'data tidak ditemukan',
        ];
       }
        return $data;
    }


    private function slugify($string){
        return $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
     }

    public function penulisStore(Request $request){
        $Now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
        $data = $request->all(); 
        $result = [];

        $request->validate([
                'username' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255'],
                'password' => 'required',
        ]);

            $insert_penulis = [
                'usernames' => $request->username,
                'email_penulis' => $request->email,
                'password' => Hash::make($request->password),
                'nama_penulis' => $request->nama_penulis,
                'telp_penulis' => $request->telp_penulis,
                'tipe_penulis' => 1,
                'seo' => $this->slugify($request->username),
            ];

            $create_penulis = Penulis::create($insert_penulis);
            
          if($create_penulis){
            $result['status'] = "success";
            $result['message'] = "Penulis Created successfully!";
          }else{
            $result['status'] = "error";
            $result['message'] = "Penulis Fail to creat!";
          }
          
          return response()->json($result);
    }

    //Save update users
    public function penulisStoreUpdate(Request $request){
        $Now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
        $data = $request->all(); 
        $result = [];
      
        $request->validate([
                'username' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255'],
        ]);


        $param_update_penulis = [
            'nama_penulis' => $request->nama_penulis,
            'telp_penulis' => $request->telp_penulis,
        ];

        $update_penulis = Penulis::where('id_penulis',$request->id_penulis)
        ->update($param_update_penulis);

        if($update_penulis) {
            $result['status'] = "success";
            $result['message'] = "Penulis Updated successfully!";
        }else{
            $result['status'] = "error";
            $result['message'] = "Penulis Updated Failed!";
        }
        return response()->json($result);
    }

    //non aktif kan users
    public function updateIsActive(Request $request)
    {
        $data = [];
        $id =  $request->get('id');
        $getPenulis = Penulis::where('id_penulis', $id)->first();

        if($getPenulis){
            $status = $getPenulis->tipe_penulis;
            $is_active='1';

            $status == '1' ? $is_active='0' : $is_active='1';
            $param_update = [
                    'tipe_penulis' => $is_active,
            ];
    
            $updated =  $getPenulis->update($param_update);
            if($updated){
                if($is_active=='1'){
                        $data = [ 
                                'status'    => 'success',
                                'messages' => 'Penulis Berhasil Active',  
                                'is_active' => $is_active,  
                            ];
                }else if($is_active=='0'){
                        $data = [ 
                                'status'    => 'success',
                                'messages' => 'Penulis Berhasil Non Active', 
                                'is_active' => $is_active,   
                            ];
                }
          
            }else{
                $data = [ 
                    'status'    => 'error',
                    'messages' => 'gagal',  
                ];
            }
        }else{
            $data = [ 
                'status'    => 'error',
                'messages' => 'data tidak ditemukan',  
            ];
        }
        
        return $data;
    }  

}
