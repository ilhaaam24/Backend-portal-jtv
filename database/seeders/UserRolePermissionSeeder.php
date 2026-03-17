<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $default_user_value = [
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];


        


        DB::beginTransaction();
        try {
           
            $author = User::create(array_merge([
                'email' => 'author@gmail.com',
                'name'  => 'author',
            ], $default_user_value));
    
            $editor = User::create(array_merge([
                'email' => 'editor@gmail.com',
                'name'  => 'editor',
            ], $default_user_value));
    
            $admin = User::create(array_merge([
                'email' => 'admin@gmail.com',
                'name'  => 'admin',
            ], $default_user_value));
    
    
            $role_author = Role::create(['name' => 'author']);
            $role_editor = Role::create(['name' => 'editor']);
            $role_admin = Role::create(['name' => 'admin']);
    
    
            $permission = Permission::create(['name' => 'read role']);
            $permission = Permission::create(['name' => 'create role']);
            $permission = Permission::create(['name' => 'update role']);
            $permission = Permission::create(['name' => 'delete role']);


            $role_admin->givePermissionTo('read role');
            $role_admin->givePermissionTo('create role');
            $role_admin->givePermissionTo('update role');
            $role_admin->givePermissionTo('delete role');
    
            $author->assignRole('author');
            $editor->assignRole('editor');
            $admin->assignRole('admin');
            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
        }
       


    }
}
