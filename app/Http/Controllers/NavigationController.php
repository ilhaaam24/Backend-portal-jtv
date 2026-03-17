<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Navigation;
use App\Models\Menuitem;
use DateTime;
use DateTimeZone;
// use Session;
use Illuminate\Support\Facades\Request as Requested;


class NavigationController extends Controller

{
    //
    public function index(){
        
        return view ('konfigurasi.menu',['menus'=>Navigation::all()]);
    }
    
    public function getNavigationCMS()
    {
      //menu
      $navigations = Navigation::orderby('short', 'asc')->get();
      $mainmenu = Navigation::where('main_menu', null)
                ->orderby('short', 'asc')->get();

      $navigation = new navigation;
      $navigation = $navigation->getHTMLNav($navigations);
      $data = array();
      $data = [   
              'mainmenu' => $mainmenu,
      ];
      return view('konfigurasi.navigation',  $data);
    }

    public function nestableCMS()
    {
      //menu
      $navigations = Navigation::orderby('short', 'asc')->get();
    
      $navigation = new navigation;
      $navigation = $navigation->getHTMLNav($navigations);
      $data = array();
      $data = [   
              'navigations' => $navigation,
      ];

      return $data;
    }

    public function deleteNav(Request $request)
    {
        $id =  $request->get('id');
        $deleted = Navigation::find($id)->delete();
        
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

    public function getLastSeq(Request $request){
        $getid = $request->id;

        $maxValue  = Navigation::where('main_menu', $getid)
        ->orderBy('short', 'desc')->first();
        if($maxValue) {
            $newSeq =$maxValue->short + 1;
        }else{
            $newSeq = 1;
        } 
        
        $data = array();
        $data = [   
                'status'    => 'success',
                'messages' => 'berhasil',
                'hasil' => $newSeq,
        ];

        return $data;

    }

    public function getParentMenu(){
        $main_menu  = Navigation::where('main_menu', null)
        ->orderBy('short', 'asc')->get();
  
        $result = [];
        if($main_menu) {
            $result['status'] = "success";
            $result['main_menu'] = $main_menu;
        }else{
            $result['status'] = "error";
           
        }
        return response()->json($result);
    }

    public function getEditNav(Request $request){
        $getid = $request->id;

        $dataNav  = Navigation::find($getid);

        $data = array();
        $data = [   
                'status'    => 'success',
                'messages' => 'berhasil',
                'hasil' => $dataNav,
        ];

        return $data;

    }

    public function reorderNav(Request $request)
    {
      /* $source = $request->input('list');
      $data = json_decode($source); */

      $ordering = json_decode(Requested::input('order'));
      $rootOrdering = json_decode(Requested::input('rootOrder'));


      /* echo "<pre>";print_r($ordering);echo "<br>";
      echo "<pre>";print_r($rootOrdering);echo "<br>"; */
      // echo json_encode($source);
      // exit;
      $source = $request->input('source');
      $destination = $request->input('destination');
      $item = Navigation::find($source);
      $item->main_menu = $destination;
      $item->save();

      $ordering = json_decode(Requested::input('order'));
      $rootOrdering = json_decode(Requested::input('rootOrder'));
      if($ordering){
        foreach($ordering as $order => $item_id){
          if($itemToOrder = Navigation::find($item_id)){
          $itemToOrder->short = $order;
          $itemToOrder->save();
          }
        }
      } else {
        foreach($rootOrdering as $order=>$item_id){
          // echo $order;
          echo $item_id;
          if($itemToOrder = Navigation::find($item_id)){
          $itemToOrder->short = $order;
          $itemToOrder->save();
          }
        }
      }
      return 'ok ';
    }

    public function navigasiStore(Request $request){
        $Now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
        $data = $request->all(); 
      
        // return $data;
        $validated = $request->validate([
            'nav_name' => 'required',
            'nav_url' => 'required',
           
        ]);

        $param_insert = [
            'name' => $request->nav_name,
            'url' => $request->nav_url,
            'main_menu' => $request->main_menu,
            'short' => $request->nav_short,   
        ];
    
        $create = Navigation::create($param_insert);
        $lastId = $create->id;
        if($create) {
            $result['lastId'] = $lastId;
            $result['status'] = "success";
            $result['message'] = "Navigation Created successfully!";
            return response()->json($result);
        }else{
            $result['status'] = "failed";
            $result['message'] = "Navigation Updated Failed!";
            return response()->json($result);
        }
        return abort(500);
    }
    public function navigasiStoreUpdate(Request $request){
        $Now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
        $data = $request->all(); 
      
        // return $data;
        $validated = $request->validate([
            'nav_name' => 'required',
            'nav_url' => 'required',
           
        ]);

        $param_update = [
            'name' => $request->nav_name,
            'url' => $request->nav_url,
            'main_menu' => $request->main_menu,
            'short' => $request->nav_short,   
        ];
    
        $update = Navigation::where('id',  $request->nav_id)
        ->update($param_update);

        if($update) {
            $result['status'] = "success";
            $result['message'] = "Navigation Updated successfully!";
            return response()->json($result);
        }else{
            $result['status'] = "failed";
            $result['message'] = "Navigation Updated Failed!";
            return response()->json($result);
        }
        return abort(500);
    }


      
}
