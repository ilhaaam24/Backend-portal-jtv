<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function pengguna(): HasOne
    {
        return $this->hasOne(Pengguna::class, 'id_user');
    }

    public function imageUsers()
    {

        $img = Auth::user()->pengguna->gambar_pengguna;

        if (Storage::exists("foto-profil/$img")) {
            $path_server = asset("assets/foto-profil/$img");
        }else{
            $path_server =  asset(config('jp.path_url_no_img'));
        }

        return $path_server;

    }
    
    public function userMenu()
    {
        $relation = $this->belongsToMany(
            Navigation::class,
           RoleHasMenu::class,
            'role_id',
        );
        return $relation;
    }

 
    public function role()
    {
        $relation = $this->hasOneThrough(
            config('permission.models.role'),
            ModelHasRole::class,
            'model_id',
            'id',
            'id',
            'role_id'
        );

        return $relation;
    }

    public function modelHasRole()
    {
        return $this->morphOne('App\Models\ModelHasRole', 'model');
    }

  
}
