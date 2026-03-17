<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use App\Models\User;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    //
    public function index()
    {
        $user = User::orderBy('id','DESC')->paginate(5);
        $all_users_with_all_their_roles = User::with('roles')->get();
        $all_users_with_all_direct_permissions = User::with('permissions')->get();
        $all_roles_in_database = Role::all()->pluck('name');
        $users_without_any_roles = User::doesntHave('roles')->get();


        $all_users_admin = Role::where('name', 'admin')->get();
        // $users = User::role('admin')->get();
        
        // $roles = $user->getRoleNames();

        $data = array();
        $data = [   
                // 'abc ' =>  $user->hasAllRoles(Role::all()), 
                //  'all_users_admin ' => $users,  
                 'all_users_with_all_their_roles'=> $all_users_with_all_their_roles,      
                 'all_users_with_all_direct_permissions'=> $all_users_with_all_direct_permissions,      
                 'all_roles_in_database'=> $all_roles_in_database,      
                 'users_without_any_roles'=> $users_without_any_roles,      
                 'user'=> $user,         
                //  'roles'=> $roles,         
         ];
        //  return $data;
         return view('konfigurasi.users', $data);
    }

    private function slugify($string){
        return $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
     }

    public function fetchUser(Request $request){

        $id_user =  $request->id;
        $detail = User::findOrFail($id_user);
        $data['user_detail'] =  $detail->with('pengguna')->first();
        return response()->json($data);

    }

   
}
