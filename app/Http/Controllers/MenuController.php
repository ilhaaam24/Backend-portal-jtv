<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Menuitem;
use App\Models\Category;
use App\Models\Navigation;
use App\Models\Post;
use Illuminate\Support\Facades\Request as Requested;

class MenuController extends Controller
{
    //

    public function getNavigation()
    {
      //menu
      $menus = Menu::orderby('order', 'asc')->get();

      $menu = new menu;
      $menu = $menu->getHTML($menus);

      return view('konfigurasi.menus', compact('menus', 'menu'));
    }

   
    public function getMenuNavigation()
    {
      //menu
      $menus = Menu::orderby('order', 'asc')->get();

      $menu = new menu;
      $menu = $menu->getHTML($menus);

      return view('konfigurasi.menu_nav', compact('menus', 'menu'));
    }

    //get edit page
    public function getEdit($id)
    {
      $item = Menu::findOrFail($id);
      return view('konfigurasi.menus_edit', compact('item'));
    }

    // same as update function when you make resource controller
    public function postEdit(Request $request, $id) //done
    {
      $item = Menu::find($id);
      $item = Menu::where('id',$id)->first();
      $item->title = $request->input('title');
      $item->slug = $request->input('slug');
      $item->parent_id = $request->input('parent_id');
      $item->save();
      return redirect()->route('menus', $item->id)->with('success', 'Item, '. $item->title.' updated');
    }

    // AJAX Reordering function (update menu item orders by ajax)
    public function postIndex(Request $request)
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
      $item = Menu::find($source);
      $item->parent_id = $destination;
      $item->save();

      $ordering = json_decode(Requested::input('order'));
      $rootOrdering = json_decode(Requested::input('rootOrder'));
      if($ordering){
        foreach($ordering as $order => $item_id){
          if($itemToOrder = Menu::find($item_id)){
      $itemToOrder->order = $order;
      $itemToOrder->save();
          }
        }
      } else {
        foreach($rootOrdering as $order=>$item_id){
          if($itemToOrder = Menu::find($item_id)){
      $itemToOrder->order = $order;
      $itemToOrder->save();
          }
        }
      }
      return 'ok ';
    }

    //store function (create new item)
    public function postNew(Requested $request)
    {
        $item = new Menu;
        $item->title = $request->input('title');
        $item->slug = $request->input('slug');
        $item->order = Menu::max('order')+1;
        $item->save();

        return redirect()->back();
    }


    //destroy function
    public function postDelete(Request $request)
    {
      $id = $request->input('delete_id');
      // Find all items with the parent_id of this one and reset the parent_id to null
      $items = Menu::where('parent_id', $id)->get()->each(function($item)
      {
        $item->parent_id = '';
        $item->save();
      });
      // Find and delete the item that the user requested to be deleted
      $item = Menu::findOrFail($id);
      $item->delete();
      // Session::flash('danger', 'Menu Item successfully deleted.');
      return redirect()->back();
    }



    public function index(){
        $menuitems = '';
        $desiredMenu = '';  
        if(isset($_GET['id']) && $_GET['id'] != 'new'){
          $id = $_GET['id'];
          $desiredMenu = Menu::where('id',$id)->first();
          if($desiredMenu->content != ''){
            $menuitems = json_decode($desiredMenu->content);
            $menuitems = $menuitems[0]; 
            foreach($menuitems as $menu){
              $menu->title = Menuitem::where('id',$menu->id)->value('title');
              $menu->name = Menuitem::where('id',$menu->id)->value('name');
              $menu->slug = Menuitem::where('id',$menu->id)->value('slug');
              $menu->target = Menuitem::where('id',$menu->id)->value('target');
              $menu->type = Menuitem::where('id',$menu->id)->value('type');
              $menu->children = Menuitem::where('id',$menu->id)->value('parent_id');
              if(!empty($menu->children[0])){
                foreach ($menu->children[0] as $child) {
                  $child->title = Menuitem::where('id',$child->id)->value('title');
                  $child->name = Menuitem::where('id',$child->id)->value('name');
                  $child->slug = Menuitem::where('id',$child->id)->value('slug');
                  $child->target = Menuitem::where('id',$child->id)->value('target');
                  $child->type = Menuitem::where('id',$child->id)->value('type');
                }  
              }
            }
          }else{
            $menuitems = Menuitem::where('menu_id',$desiredMenu->id)->get();                    
          }             
        }else{
          $desiredMenu = Menu::orderby('id','DESC')->first();
          if($desiredMenu){
            if($desiredMenu->content != ''){
              $menuitems = json_decode($desiredMenu->content);
              $menuitems = $menuitems[0]; 
              foreach($menuitems as $menu){
                $menu->title = Menuitem::where('id',$menu->id)->value('title');
                $menu->name = Menuitem::where('id',$menu->id)->value('name');
                $menu->slug = Menuitem::where('id',$menu->id)->value('slug');
                $menu->target = Menuitem::where('id',$menu->id)->value('target');
                $menu->type = Menuitem::where('id',$menu->id)->value('type');
                if(!empty($menu->children[0])){
                  foreach ($menu->children[0] as $child) {
                    $child->title = Menuitem::where('id',$child->id)->value('title');
                    $child->name = Menuitem::where('id',$child->id)->value('name');
                    $child->slug = Menuitem::where('id',$child->id)->value('slug');
                    $child->target = Menuitem::where('id',$child->id)->value('target');
                    $child->type = Menuitem::where('id',$child->id)->value('type');
                  }  
                }
              }
            }else{
              $menuitems = Menuitem::where('menu_id',$desiredMenu->id)->get();
            }                                   
          }           
        }
/* 
        $menu = new menu;
        $menu = $menu->getHTML($menus); */

        $navigations = Navigation::orderby('short', 'asc')->get();
        // return $navigations;

        $navigation = new navigation;
        $navigation = $navigation->getHTMLNav($navigations);
  
        return view ('konfigurasi.menu',['categories'=>Category::all(),'posts'=>Post::all(),'menu'=> $navigation,
        'menus'=> $navigations, 'desiredMenu'=>$desiredMenu,'menuitems'=>$menuitems]);
      }	
    
      public function store(Request $request){
        $data = $request->all(); 
        if(Menu::create($data)){ 
          $newdata = Menu::orderby('id','DESC')->first();          
                     
          return redirect("manage-menus?id=$newdata->id");
        }else{
          return redirect()->back()->with('error','Failed to save menu !');
        }
      }	
    
      public function addCatToMenu(Request $request){
        $data = $request->all();
        $menuid = $request->menuid;
        $ids = $request->ids;
        $menu = Menu::findOrFail($menuid);
        if($menu->content == ''){
          foreach($ids as $id){
            $data['title'] = Category::where('id',$id)->value('title');
            $data['slug'] = Category::where('id',$id)->value('slug');
            $data['type'] = 'category';
            $data['menu_id'] = $menuid;
            $data['updated_at'] = NULL;
            Menuitem::create($data);
          }
        }else{
          $olddata = json_decode($menu->content,true); 
          foreach($ids as $id){
            $data['title'] = Category::where('id',$id)->value('title');
            $data['slug'] = Category::where('id',$id)->value('slug');
            $data['type'] = 'category';
            $data['menu_id'] = $menuid;
            $data['updated_at'] = NULL;
            Menuitem::create($data);
          }
          foreach($ids as $id){
            $array['title'] = Category::where('id',$id)->value('title');
            $array['slug'] = Category::where('id',$id)->value('slug');
            $array['name'] = NULL;
            $array['type'] = 'category';
            $array['target'] = NULL;
            $array['id'] = Menuitem::where('slug',$array['slug'])->where('name',$array['name'])->where('type',$array['type'])->value('id');
            $array['children'] = [[]];
            array_push($olddata[0],$array);
            $oldata = json_encode($olddata);
            $menu->update(['content'=>$olddata]);
          }
        }
      }
    
      public function addPostToMenu(Request $request){
        $data = $request->all();
        $menuid = $request->menuid;
        $ids = $request->ids;
        $menu = Menu::findOrFail($menuid);
        if($menu->content == ''){
          foreach($ids as $id){
            $data['title'] = Post::where('id',$id)->value('title');
            $data['slug'] = Post::where('id',$id)->value('slug');
            $data['type'] = 'post';
            $data['menu_id'] = $menuid;
            $data['updated_at'] = NULL;
            Menuitem::create($data);
          }
        }else{
          $olddata = json_decode($menu->content,true); 
          foreach($ids as $id){
            $data['title'] = Post::where('id',$id)->value('title');
            $data['slug'] = Post::where('id',$id)->value('slug');
            $data['type'] = 'post';
            $data['menu_id'] = $menuid;
            $data['updated_at'] = NULL;
            Menuitem::create($data);
          }
          foreach($ids as $id){
            $array['title'] = Post::where('id',$id)->value('title');
            $array['slug'] = Post::where('id',$id)->value('slug');
            $array['name'] = NULL;
            $array['type'] = 'post';
            $array['target'] = NULL;
            $array['id'] = Menuitem::where('slug',$array['slug'])->where('name',$array['name'])->where('type',$array['type'])->orderby('id','DESC')->value('id');                
            $array['children'] = [[]];
            array_push($olddata[0],$array);
            $oldata = json_encode($olddata);
            $menu->update(['content'=>$olddata]);
          }
        }
      }
    
      public function addCustomLink(Request $request){
        $data = $request->all();
        $menuid = $request->menuid;
        $menu = Menu::findOrFail($menuid);
        if($menu->content == ''){
          $data['title'] = $request->link;
          $data['slug'] = $request->url;
          $data['type'] = 'custom';
          $data['menu_id'] = $menuid;
          $data['updated_at'] = NULL;
          Menuitem::create($data);
        }else{
          $olddata = json_decode($menu->content,true); 
          $data['title'] = $request->link;
          $data['slug'] = $request->url;
          $data['type'] = 'custom';
          $data['menu_id'] = $menuid;
          $data['updated_at'] = NULL;
          Menuitem::create($data);
          $array = [];
          $array['title'] = $request->link;
          $array['slug'] = $request->url;
          $array['name'] = NULL;
          $array['type'] = 'custom';
          $array['target'] = NULL;
          $array['id'] = Menuitem::where('slug',$array['slug'])->where('name',$array['name'])->where('type',$array['type'])->orderby('id','DESC')->value('id');                
          $array['children'] = [[]];
          array_push($olddata[0],$array);
          $oldata = json_encode($olddata);
          $menu->update(['content'=>$olddata]);
        }
      }
    
      public function updateMenu(Request $request){
        $newdata = $request->all(); 
        $menu=Menu::findOrFail($request->menuid);            
        $content = $request->data; 
        $newdata = [];  
        $newdata['location'] = $request->location;       
        $newdata['content'] = json_encode($content);
        $menu->update($newdata); 
      }
    
      public function updateMenuItem(Request $request){
        $data = $request->all();        
        $item = Menuitem::findOrFail($request->id);
        $item->update($data);
        return redirect()->back();
      }
    
      public function deleteMenuItem($id,$key,$in=''){        
        $menuitem = Menuitem::findOrFail($id);
        $menu = Menu::where('id',$menuitem->menu_id)->first();
        if($menu->content != ''){
          $data = json_decode($menu->content,true);            
          $maindata = $data[0];            
          if($in == ''){
            unset($data[0][$key]);
            $newdata = json_encode($data); 
            $menu->update(['content'=>$newdata]);                         
          }else{
            unset($data[0][$key]['children'][0][$in]);
            $newdata = json_encode($data);
            $menu->update(['content'=>$newdata]); 
          }
        }
        $menuitem->delete();
        return redirect()->back();
      }	
    
      public function destroy(Request $request){
        Menuitem::where('menu_id',$request->id)->delete();  
        Menu::findOrFail($request->id)->delete();
        return redirect('manage-menus')->with('success','Menu deleted successfully');
      }		
}
