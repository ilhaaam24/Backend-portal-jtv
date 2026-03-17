<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Penulis;     
use App\Models\Berita;      
use App\Models\CommentLike; 

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comments';

    protected $fillable = [
        'id_berita', 
        'user_id',
        'parent_id', 
        'content', 
        'status'
    ];

    // --- RELASI USER (Penulis) ---
    public function user()
    {
        // Parameter 2: user_id (kolom di tabel comments)
        // Parameter 3: id_penulis (primary key di tb_penulis)
        return $this->belongsTo(Penulis::class, 'user_id', 'id_penulis');
    }

    // --- RELASI BERITA ---
    public function berita()
    {
        return $this->belongsTo(Berita::class, 'id_berita', 'id_berita');
    }

    // --- RELASI LIKES ---
    public function likes()
    {
        return $this->hasMany(CommentLike::class, 'comment_id');
    }

    // --- ACCESSOR: IS LIKED? ---
    public function getIsLikedAttribute()
    {
        // Cek user login via Sanctum
        $currentUser = auth('sanctum')->user();

        if (!$currentUser) {
            return false;
        }

        // Cek apakah user ini (id_penulis) ada di tabel likes
        return $this->likes->where('id_penulis', $currentUser->id_penulis)->count() > 0;
    }

    // --- RELASI BALASAN (REPLIES) ---
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->with('user');
    }
}