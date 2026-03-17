<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FcmToken extends Model
{
    protected $table = 'tb_fcm_tokens';

    protected $fillable = [
        'id_penulis',
        'token',
        'device_type',
    ];

    /**
     * Relasi ke Penulis (nullable — bisa guest)
     */
    public function penulis(): BelongsTo
    {
        return $this->belongsTo(Penulis::class, 'id_penulis', 'id_penulis');
    }
}
