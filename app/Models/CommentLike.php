<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentLike extends Model
{
    use HasFactory;

    protected $table = 'comment_likes'; // Nama tabel di database

    // Pastikan field ini boleh diisi (Mass Assignment)
    protected $fillable = [
        'id_penulis', // Sesuai dengan tb_penulis
        'comment_id'
    ];
}