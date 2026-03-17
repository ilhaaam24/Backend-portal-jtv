<?php

namespace App\Http\Controllers;

use App\Models\Navigation;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    { 
        //

        $navigation = Navigation::where('main_menu', '!=', null)
                    ->oldest('id')->get();
        $permission = Permission::get();

        $data = array();
        $data = [   
                'navigation'=> $navigation,       
                'permission'=> $permission,      
        ];
        return view('konfigurasi.permission',  $data);
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
    public function permissionStore(Request $request)
    {
        //

        $create = Permission::create(['name' => $request->name_permission, 
                                'navigation_id' => $request->id_navigation]);
        $lastId = $create->id;
        if($create) {
        
            $result['lastId'] = $lastId;
            $result['status'] = "success";
            $result['message'] = "Permission Created successfully!";
            // $result['url'] = route('berita.update', ['id' => $lastId]);
            return response()->json($result);
        }else{
            $result['status'] = "failed";
            $result['message'] = "Permission Updated Failed!";
            return response()->json($result);
        }
        return abort(500);

    }


    public function permissionUpdate(Request $request, Permission $permission)
    {
        //

        $get_data = Permission::where('id', $request->permission_id)
                                ->first();
        $updated =  $get_data->update(['name' => $request->name_permission, 
        'navigation_id' => $request->id_navigation]);
     
        if($updated) {

        $result['status'] = "success";
        $result['message'] = "Permission Updated successfully!";
        // $result['url'] = route('berita.update', ['id' => $lastId]);
        return response()->json($result);
        }else{
        $result['status'] = "failed";
        $result['message'] = "Permission Updated Failed!";
        return response()->json($result);
        }
        return abort(500);
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
   

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        //
    }
}
