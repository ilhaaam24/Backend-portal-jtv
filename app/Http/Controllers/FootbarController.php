<?php

namespace App\Http\Controllers;

use App\Http\Resources\FootbarResource;
use App\Models\Footbar;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as Requested;

class FootbarController extends Controller
{
    //
    public function index()
    {
        $footbar = Footbar::orderby('no_urut', 'asc')->get();

        $data = array();

        $data = [
            'footbar' => $footbar,
        ];

        $footbars = Footbar::orderby('no_urut', 'asc')->get();
     
        $footbar = new footbar;
        $footbar = $footbar->getHTMLNav($footbars);
        $data = array();
        $data = [   
             
                'footbars' => $footbars,
        ];
  
        return view('layout.menubawah.footbar_index', $data);
    }

    public function nestableFootbar()
    {
      //menu
      $footbars = Footbar::orderby('no_urut', 'asc')->get();
    
      $footbar = new footbar;
      $footbar = $footbar->getHTMLNav($footbars);
      $data = array();
      $data = [   
              'footbars' => $footbar,
      ];

      return $data;
    }

    public function reorderFootbar(Request $request)
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
      $item = Footbar::find($source);
      $item->id_parent = $destination;
      $item->save();

      $ordering = json_decode(Requested::input('order'));
      $rootOrdering = json_decode(Requested::input('rootOrder'));
      if($ordering){
        foreach($ordering as $order => $item_id){
          if($itemToOrder = Footbar::find($item_id)){
          $itemToOrder->no_urut = $order;
          $itemToOrder->save();
          }
        }
      } else {
        foreach($rootOrdering as $order=>$item_id){
          // echo $order;
          echo $item_id;
          if($itemToOrder = Footbar::find($item_id)){
          $itemToOrder->no_urut = $order;
          $itemToOrder->save();
          }
        }
      }
      return 'ok ';
    }

    public function getEditFootbar(Request $request){
        $getid = $request->id;

        $dataFootbar  = Footbar::where('id_footbar',$getid)->first();

        $data = array();
        $data = [   
                'status'    => 'success',
                'messages' => 'berhasil',
                'hasil' => $dataFootbar,
        ];

        return $data;

    }

    public function footbarStoreUpdate(Request $request){
        $Now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
        $data = $request->all(); 
      
        // return $data;
        $validated = $request->validate([
            'id_footbar' => 'required',
            'isi_footbar' => 'required',
        ]);

        $param_update = [
            'isi_footbar' => $request->isi_footbar,
        ];
    
        $update = Footbar::where('id_footbar',  $request->id_footbar)
        ->update($param_update);

        if($update) {
            $result['status'] = "success";
            $result['message'] = "Footbar Updated successfully!";
            return response()->json($result);
        }else{
            $result['status'] = "failed";
            $result['message'] = "Footbar Updated Failed!";
            return response()->json($result);
        }
        return abort(500);
    }



    public function edit(Request $request){
        $id= $request->id;

        $footbar = Footbar::where('id_footbar', $id)
                ->paginate(10);

        $data = array();

        $data = [
            'footbar' => $footbar,
        ];

        return view('layout.menubawah.edit', $data);
    }


    /* ------------------------API FOOTBAR ---------------- */
    public function pageStatic($id){
      $title = Footbar::select('judul_footbar as judul')
      ->where('tag_judul', $id)
      ->first();

      $FootbarPage =   FootbarResource::make(Footbar::where('tag_judul', $id)
        ->first())
        ->additional([
            'section' => [
                "title" => ($title) ? $title->judul :'',
                "link" =>  config('jp.path_url_web')."api/page/".$id
            ]
        ]);
        return  $FootbarPage;
      }
    
}
