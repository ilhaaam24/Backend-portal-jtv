<?php

// namespace App\Helpers;
use App\Models\Navigation;
use App\Models\RoleHasMenu;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

if(!function_exists('getMenusRole')){
    function getMenusRole()
    {
        // get role id from auth login
        $data_menu = Auth::user()->role->id;

        //query get submenu
        $data_submenu = RoleHasMenu::select('navigations.id')
            ->where('role_has_menus.role_id', $data_menu)
            ->join('navigations', 'role_has_menus.navigation_id', '=', 'navigations.id')
            ->where('navigations.main_menu','!=', null)
            ->get();

                    
            $id_nav = array();
            foreach($data_submenu as $submenu){
            array_push($id_nav, $submenu['id']);
            }
        
        // Query get Main Menu with Submenu aktif by role
        return RoleHasMenu::where('role_has_menus.role_id', $data_menu)
                ->join('navigations', 'role_has_menus.navigation_id', '=', 'navigations.id')
                ->where('navigations.main_menu',null)
                ->with(['subMenus' => function($query) use ($id_nav){
                    $query->whereIn('navigations.id', $id_nav);
                }])
                ->orderBy('short', 'asc')
                ->get();
    }
}


if(!function_exists('getIdRole')){
    function getIdRole($id)
    {
    //   return  $data_menu = Auth::user()->role->id;
    return $all_users_admin = Role::where('id', $id)->get();
      return $role_get = Role::where('id', $id)->first();
        // return  $id!= null ? $id : 'now';
        $role_get = Role::where('id', $id)->first();
        // return $role_get;
        dd($role_get);


        
         $data_submenu = RoleHasMenu::select('navigations.id')
        ->where('role_has_menus.role_id', $id)
        ->join('navigations', 'role_has_menus.navigation_id', '=', 'navigations.id')
        ->where('navigations.main_menu','!=', null)
        ->get();
   
        $id_nav = array();
        foreach($data_submenu as $submenu){
        array_push($id_nav, $submenu['id']);
        }
       
     
        return RoleHasMenu::where('role_has_menus.role_id', $id)
                    ->join('navigations', 'role_has_menus.navigation_id', '=', 'navigations.id')
                    ->where('navigations.main_menu',null)
                    ->with(['subMenus' => function($query) use ($id_nav){
                        $query->whereIn('navigations.id', $id_nav);
                    }])
                    ->orderBy('short', 'asc')
                    ->get();
        // return $id;

    }
}

if(!function_exists('getRoleMenus')){
 function getRoleMenus()
        {
           
            // get role id from auth login
            $data_menu = Auth::user()->role->id;

            //query get submenu
            $data_submenu = RoleHasMenu::select('navigations.id')
                ->where('role_has_menus.role_id', $data_menu)
                ->join('navigations', 'role_has_menus.navigation_id', '=', 'navigations.id')
                ->where('navigations.main_menu','!=', null)
                ->get();
           
                $id_nav = array();
                foreach($data_submenu as $submenu){
                array_push($id_nav, $submenu['id']);
                }
            
            // Query get Main Menu with Submenu aktif by role
            return RoleHasMenu::where('role_has_menus.role_id', $data_menu)
                    ->join('navigations', 'role_has_menus.navigation_id', '=', 'navigations.id')
                    ->where('navigations.main_menu',null)
                    ->with(['subMenus' => function($query) use ($id_nav){
                        $query->whereIn('navigations.id', $id_nav);
                    }])
                    ->orderBy('short', 'asc')
                    ->get();
        }
    }



?>