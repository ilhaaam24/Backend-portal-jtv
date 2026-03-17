<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pertanyaan extends Model
{
    use HasFactory;

    protected $table = 'tb_pertanyaan';
    protected $primaryKey = 'id_pertanyaan';

    // 👇 PENTING: Biar bisa langsung create() tanpa error mass assignment
    protected $guarded = [];

    public $timestamps = false; // Sesuai settingan DB lo

    // Relasi ke Pengirim
    public function dari_user(): BelongsTo
    {
        return $this->belongsTo(Penulis::class, 'dari', 'id_penulis');
    }

    // Relasi ke Penerima
    public function kepada_user(): BelongsTo
    {
        return $this->belongsTo(Penulis::class, 'kepada', 'id_penulis');
    }
}
