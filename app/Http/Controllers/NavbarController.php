<?php

namespace App\Http\Controllers;

use App\Models\Navbar;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as Requested;

class NavbarController extends Controller
{
    //
    public function index()
    {
        //menu
      $navbars = Navbar::orderby('no_urut', 'asc')->get();
      $id_parent = Navbar::where('id_parent', null)
                ->orderby('no_urut', 'asc')->get();

      $navbar = new navbar;
      $navbar = $navbar->getHTMLNav($navbars);
      $data = array();
      $data = [   
           
              'id_parent' => $id_parent,
      ];

       
        return view('layout.navbar.navbar_index', $data);

    }

    public function nestableNavbar()
    {
      //menu
      $navbars = Navbar::orderby('no_urut', 'asc')->get();
    
      $navbar = new navbar;
      $navbar = $navbar->getHTMLNav($navbars);
      $data = array();
      $data = [   
              'navbar' => $navbar,
      ];

      return $data;
    }

    public function deleteNavbar(Request $request)
    {
        $id =  $request->get('id');
        $deleted = Navbar::where('id_navbar', $id)->delete();
        
        $data = array();
        if($deleted){
            $data = [ 
                'status'    => 'success',
                'messages' => 'berhasil',  
            ];
        }else{
            $data = [ 
                'status'    => 'error',
                'messages' => 'gagal',  
            ];
        }
        return $data;
    }  

    public function getLastNavbar(Request $request){
        $getid = $request->id;

        $maxValue  = Navbar::where('id_parent', $getid)
        ->orderBy('no_urut', 'desc')->first();
        !empty($maxValue->no_urut) ? $newSeq=$maxValue->no_urut+1 : $newSeq='0';
    
        $data = array();
        $data = [   
                'status'    => 'success',
                'messages' => 'berhasil',
                'hasil' => $newSeq,
        ];
        return $data;
    }


    public function getEditNavbar(Request $request){
        $getid = $request->id;

        $dataNavbar  = Navbar::where('id_navbar',$getid)->first();

        $data = array();
        $data = [   
                'status'    => 'success',
                'messages' => 'berhasil',
                'hasil' => $dataNavbar,
        ];
        return $data;
    }

    private function slugify($string){
        return $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
     }


    public function navbarStore(Request $request){
        $Now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
        $data = $request->all(); 
      
        // return $data;
        $validated = $request->validate([
            'judul_navbar' => 'required',
        ]);

        $param_insert = [
           
            'judul_navbar' => $request->judul_navbar,
            'tag_judul' => $this->slugify($request->judul_navbar),
            
            'id_parent' => $request->id_parent,
            'is_active' => 1,
            'rubrik' => 1,
            'ket_navbar' => 0,
            'no_urut' => $request->navbar_urut,   
        ];
    
        $create = Navbar::create($param_insert);
        $lastId = $create->id;
        if($create) {
            $result['lastId'] = $lastId;
            $result['status'] = "success";
            $result['message'] = "Navbar Created successfully!";
            return response()->json($result);
        }else{
            $result['status'] = "failed";
            $result['message'] = "Navbar Updated Failed!";
            return response()->json($result);
        }
        return abort(500);
    }
    public function navbarStoreUpdate(Request $request){
        $Now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
        $data = $request->all(); 
      
        // return $data;
        $validated = $request->validate([
            'judul_navbar' => 'required',
           
        ]);

        $param_update = [
            'judul_navbar' => $request->judul_navbar,
            'id_parent' => $request->id_parent,
            'tag_judul' => $this->slugify($request->judul_navbar),
            'no_urut' => $request->navbar_urut, 
        ];
    
        $update = Navbar::where('id_navbar',  $request->id_navbar)
        ->update($param_update);

        if($update) {
            $result['status'] = "success";
            $result['message'] = "Navbar Updated successfully!";
            return response()->json($result);
        }else{
            $result['status'] = "failed";
            $result['message'] = "Navbar Updated Failed!";
            return response()->json($result);
        }
        return abort(500);
    }

    
    public function reorderNavbar(Request $request)
    {
      /* $source = $request->input('list');
      $data = json_decode($source);
 */
      $ordering = json_decode(Requested::input('order'));
      $rootOrdering = json_decode(Requested::input('rootOrder'));


      /* echo "<pre>";print_r($ordering);echo "<br>";
      echo "<pre>";print_r($rootOrdering);echo "<br>";
      echo json_encode($source);
      exit; */
      $source = $request->input('source');
      $destination = $request->input('destination');
      $item = Navbar::find($source);
      $item->id_parent = $destination;
      $item->save();

      $ordering = json_decode(Requested::input('order'));
      $rootOrdering = json_decode(Requested::input('rootOrder'));
      if($ordering){
        foreach($ordering as $order => $item_id){
          if($itemToOrder = Navbar::find($item_id)){
          $itemToOrder->no_urut = $order;
          $itemToOrder->save();
          }
        }
      } else {
        foreach($rootOrdering as $order=>$item_id){
          // echo $order;
        //   echo $item_id;
          if($itemToOrder = Navbar::find($item_id)){
          $itemToOrder->no_urut = $order;
          $itemToOrder->save();
          }
        }
      }
      return $itemToOrder;
    }

}
