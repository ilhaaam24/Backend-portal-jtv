<?php

namespace App\Http\Controllers;

use App\Models\Navigation;
use App\Models\RoleHasMenu;
use App\Models\RoleHasPermission;
use Spatie\Permission\Models\Role;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    // $roles = Role::get();
   $roles = Role::withCount('users')->with(['users' => function($query){
        $query->with(['pengguna' => function($query_p){
            $query_p->get();
        }])->get();
    }])->with(['permissions' => function($query){
        $query->get();
     } ])->get();

     $user = User::with('roles')->get();
    // $rolesUsers = $user->getRoleNames();

    //navigation
    $navigation = Navigation::with(['submenus' => function($query_p){
                                $query_p->with(['permissions' => function($query){
                                    $query->get();
                                } ]);
                            }])->where('main_menu', null)->oldest('short')->get();
    
    $permission = Permission::get();
        $data = array();
        $data = [   
                'user'=> $user,       
                'roles'=> $roles,      
                'navigation'=> $navigation,      
                'permission'=> $permission,      
                // 'rolesUsers'=> $rolesUsers,      
        ];
        // return $data;
        return view('konfigurasi.roles', $data);
    }

    function updateRoleMenu(Request $request){
        $get_role_menu_by_role =  RoleHasMenu::where('role_id',  $request->role_id)->get();
        $get_permissions_by_role =  RoleHasPermission::where('role_id',  $request->role_id)->get();
        $get_permissions = Role::where('id', $request->role_id)
                        ->with(['permissions' => function($query){
                            $query->select('permissions.id')->get();
                        } ])
                    ->get();


        $id_nav = array();
        foreach($get_role_menu_by_role as $data){
        array_push($id_nav, $data['navigation_id']);

        }

        $id_permissions = array();
        foreach($get_permissions_by_role as $val){
        array_push($id_permissions, $val['permission_id']);

        }

        if($get_role_menu_by_role && $id_nav) {
            $result['list_nav_id'] = $id_nav;
            $result['get_permissions'] = $id_permissions;
            $result['status'] = "success";
            $result['message'] = "Tersedia Data Role Menu";
            return response()->json($result);
        }else{
            $result['status'] = "error";
            $result['message'] = "Roles Menu Belum diatur";
            return response()->json($result);
        }

    }

    public function SubmitRoleMenu(Request $request){
        // request data
        $role_id = $request->role_id;
        $nav_id = $request->nav_id;
        $permission_id = $request->permission_id;
       

        // Select Old Data Role Menu + Role Permission
        $old_role_menu = RoleHasMenu::where('role_id', $request->role_id)->get(); //old item
        $old_role_permission = RoleHasPermission::where('role_id', $request->role_id)->get(); //old item

        $ada = [];
        $adaPermission = [];
        $hapusRoleMenu = [];
        $hapusRolePermission = [];
        $datahapusPermission = [];

        // foreach permission input
        foreach($permission_id as $value){
            $adaPermission[$value] = "hapus";
            
            // $role = Role::find($role_id);
            // $role->syncPermissions($value);

            $cek_permission = RoleHasPermission::where('permission_id', $value)
                        ->where('role_id',$role_id)->first();
            // Jika Role Permission Belum Ada
            if($cek_permission == null){
               $param_insert_permission= [
                 'permission_id' => $value,
                 'role_id' => $role_id,
               ];
               //Create New Role Permission
               $create_permission = RoleHasPermission::create($param_insert_permission);
         
           }
       }

  
       foreach($old_role_permission as $old_item_perm){
        if(!isset($adaPermission[$old_item_perm->permission_id])){
            $hapusRolePermission[$old_item_perm->permission_id] = "hapus";

            $datahapusPermission[$old_item_perm->permission_id] = $old_item_perm->permission_id;
            RoleHasPermission::where('permission_id', $old_item_perm->permission_id)
                               ->where('role_id', $request->role_id)->delete();
        
        }
    } 
    // return  $datahapusPermission;


        foreach($nav_id as $item){
            $ada[$item] = "hapus";
            $cek_nav = RoleHasMenu::where('navigation_id', $item)
                        ->where('role_id',$role_id)->first();
            // Jika Role Menu Belum Ada
            if($cek_nav == null){
               $param_insert_nav= [
                 'navigation_id' => $item,
                 'role_id' => $role_id,
               ];
               //Create New Role Menu
               $create_nav = RoleHasMenu::create($param_insert_nav);
         
           }
       }

       foreach($old_role_menu as $old_item){
            if(!isset($ada[$old_item->navigation_id])){
                $hapusRoleMenu[$old_item->navigation_id] = "hapus";

                // $datahapus[$old_item->navigation_id] = $old_item->navigation_id;
               RoleHasMenu::where('navigation_id', $old_item->navigation_id)
                                                    ->where('role_id', $request->role_id)->delete();
            
            }
        } 

      

        $get_role_menu_by_role =  RoleHasMenu::oldest('navigation_id')->where('role_id',  $request->role_id)->get();
        $get_role_permission_by_role =  RoleHasPermission::where('role_id',  $request->role_id)->get();
        
        $id_nav = array();
        foreach($get_role_menu_by_role as $data){
        array_push($id_nav, $data['navigation_id']);
        }
        

        $id_permission = array();
        foreach($get_role_permission_by_role as $data){
        array_push($id_permission, $data['permission_id']);
        }

        if($get_role_menu_by_role && $id_nav) {
            $result['list_nav_id'] = $id_nav;
            $result['list_permission_id'] = $id_permission;
            /* $result['nav_id'] = $nav_id;
            $result['perm_id'] = $permission_id; */
            $result['role_id'] = $role_id;
            $result['status'] = "success";
            $result['message'] = "Roles Menu & Permission Berhasil Diupdate";
        }else{
            $result['status'] = "error";
            $result['message'] = "Roles Menu & Permission Belum diatur";
        }


        return  response()->json($result);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $roles)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $roles)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $roles)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $roles)
    {
        //
    }
}
