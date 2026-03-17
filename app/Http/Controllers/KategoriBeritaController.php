<?php

namespace App\Http\Controllers;

use App\Models\NewKategori;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as Requested;

class KategoriBeritaController extends Controller
{
    //
    public function index()
    {
        //menu
      $kategoris = NewKategori::orderby('urut', 'asc')->get();
      $mainkategori = NewKategori::where('main_kategori_berita', null)
                ->orderby('urut', 'asc')->get();

      $kategori = new newkategori;
      $kategori = $kategori->getHTMLNav($kategoris);
      $data = array();
      $data = [   
            //   'navigations' => $navigation,
              'mainkategori' => $mainkategori,
      ];

       
        return view('layout.kategori.kategori_index', $data);

    }

    public function nestableKategori()
    {
      //menu
      $kategoris = NewKategori::orderby('urut', 'asc')->get();
    
      $kategori = new newkategori;
      $kategori = $kategori->getHTMLNav($kategoris);
      $data = array();
      $data = [   
              'kategori' => $kategori,
      ];

      return $data;
    }

    public function deleteKategori(Request $request)
    {
        $id =  $request->get('id');
        $deleted = NewKategori::where('id_kategori_berita', $id)->delete();
        
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

    public function getLastUrutKat(Request $request){
        $getid = $request->id;
        
        $maxValue  = NewKategori::where('main_kategori_berita', $getid)
        ->orderBy('urut', 'desc')->first();

        !empty($maxValue->urut) ? $newSeq=$maxValue->urut+1 : $newSeq='0';

        $data = array();
        $data = [   
                'status'    => 'success',
                'messages' => 'berhasil',
                'hasil' => $newSeq,
        ];

        return $data;

    }


    public function getEditKategori(Request $request){
        $getid = $request->id;

        $dataKat  = NewKategori::where('id_kategori_berita',$getid)->first();

        $data = array();
        $data = [   
                'status'    => 'success',
                'messages' => 'berhasil',
                'hasil' => $dataKat,
        ];

        return $data;

    }

    private function slugify($string){
        return $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
     }


    public function kategoriStore(Request $request){
        $Now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
        $data = $request->all(); 
      
        // return $data;
        $validated = $request->validate([
            'nama_kategori_berita' => 'required',
        ]);

        $param_insert = [
            'id_navbar_kategori_berita' => 0,
            'id_subnavbar_kategori_berita' => 0,
            'nama_kategori_berita' => $request->nama_kategori_berita,
            'main_kategori_berita' => $request->main_kategori_berita,
            'seo_kategori_berita' => $this->slugify($request->main_kategori_berita),
            'status_kategori_berita' => 1,
            'rubrik_kategori_berita' => 1,
            'urut' => $request->kategori_urut,   
        ];
    
        $create = NewKategori::create($param_insert);
        $lastId = $create->id;
        if($create) {
            $result['lastId'] = $lastId;
            $result['status'] = "success";
            $result['message'] = "Category Created successfully!";
            return response()->json($result);
        }else{
            $result['status'] = "failed";
            $result['message'] = "Category Updated Failed!";
            return response()->json($result);
        }
        return abort(500);
    }
    public function kategoriStoreUpdate(Request $request){
        $Now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
        $data = $request->all(); 
      
        // return $data;
        $validated = $request->validate([
            'nama_kategori_berita' => 'required',
           
        ]);

        $param_update = [
            'nama_kategori_berita' => $request->nama_kategori_berita,
            'main_kategori_berita' => $request->main_kategori_berita,
            'seo_kategori_berita' => $this->slugify($request->main_kategori_berita),
            'urut' => $request->kategori_urut, 
        ];
    
        $update = NewKategori::where('id_kategori_berita',  $request->id_kategori_berita)
        ->update($param_update);

        if($update) {
            $result['status'] = "success";
            $result['message'] = "Category Updated successfully!";
            return response()->json($result);
        }else{
            $result['status'] = "failed";
            $result['message'] = "Category Updated Failed!";
            return response()->json($result);
        }
        return abort(500);
    }

    
    public function reorderKategori(Request $request)
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
      $item = NewKategori::find($source);
      $item->main_kategori_berita = $destination;
      $item->save();

      $ordering = json_decode(Requested::input('order'));
      $rootOrdering = json_decode(Requested::input('rootOrder'));
      if($ordering){
        foreach($ordering as $order => $item_id){
          if($itemToOrder = NewKategori::find($item_id)){
          $itemToOrder->urut = $order;
          $itemToOrder->save();
          }
        }
      } else {
        foreach($rootOrdering as $order=>$item_id){
          // echo $order;
          echo $item_id;
          if($itemToOrder = NewKategori::find($item_id)){
          $itemToOrder->urut = $order;
          $itemToOrder->save();
          }
        }
      }
      return 'ok ';
    }


}
