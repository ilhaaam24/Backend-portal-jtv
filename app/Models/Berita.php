<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Gallery;
use Illuminate\Support\Str;

class Berita extends Model
{
    use HasFactory, HasRoles;

    protected $table = 'v_berita';
    protected $primaryKey = 'id_berita';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $guarded = [];

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    public function is_youtube()
    {
        $artikel_berita = $this->artikel_berita;
        $find = "https://www.youtube.com/";
        $checking = Str::contains($artikel_berita, $find);
        return $checking ? '1' : null;
    }


    public function imageNews()
    {
        $filename = $this->gambar_depan_berita;
        $tipe_gambar = $this->tipe_gambar_utama;

        if ($tipe_gambar == 'image') {
            $img_gallery = Gallery::where('original_filename', $filename)->first();
            if ($img_gallery) {
                $url_path = $img_gallery->filename;    //path original
                $original_filename = $img_gallery->original_filename;    //path original
                $filepath = public_path($url_path);     //local path
                $filepath_img = config('jp.path_url_be') . config('jp.path_img') . $filename; //path server
                $filelocal = url($url_path); // url local

                if (Storage::exists("upload-gambar/$original_filename")) {
                    $path_server = asset("assets/upload-gambar/$original_filename");
                } else {
                    $path_server = asset(config('jp.path_url_no_img'));
                }
            } else {
                $path_server = asset(config('jp.path_url_no_img'));
            }
        } else if ($tipe_gambar == 'video') {
            $path_server = 'https://img.youtube.com/vi/' . $filename . '/sddefault.jpg';
        } else {
            $path_server = asset(config('jp.path_url_no_img'));
        }
        return $path_server;
    }

    public function imageNewsUsers()
    {
        $img = $this->gambar_pengguna;
        $filepath_img = config('jp.path_url_be') . config('jp.path_img_profile') . $img; //path server
        if (Storage::exists("foto-profil/$img")) {
            return asset("assets/foto-profil/$img");
        } else {
            return asset(config('jp.path_url_no_img'));
        }
    }

    public function kategori()
    {
        // Menghubungkan id_kategori (di Berita) ke id_kategori_berita (di NewKategori)
        return $this->belongsTo(NewKategori::class, 'id_kategori', 'id_kategori_berita');
    }

    public function data_asli()
    {
        // Relasi ke tabel fisik TbBerita pake id_berita
        return $this->belongsTo(TbBerita::class, 'id_berita', 'id_berita');
    }


}
