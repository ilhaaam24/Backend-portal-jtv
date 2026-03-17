<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curhatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'message',
        'image_path',
        'is_replied' // is_approved sudah dihapus
    ];
}