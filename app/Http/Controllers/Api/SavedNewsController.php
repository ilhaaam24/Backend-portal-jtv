<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TbSimpanBerita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavedNewsController extends Controller
{
    // Menyimpan berita (POST /api/saved-news/{id_berita})
    public function store($id_berita)
    {
        $userId = Auth::id();

        // Cek apakah sudah disimpan
        $existingSave = TbSimpanBerita::where('id_user', $userId)
                                    ->where('id_berita', $id_berita)
                                    ->first();

        if ($existingSave) {
            return response()->json(['message' => 'Berita sudah disimpan sebelumnya.', 'is_saved' => true], 200);
        }

        // Simpan ke database
        TbSimpanBerita::create([
            'id_user' => $userId,
            'id_berita' => $id_berita,
        ]);

        return response()->json(['message' => 'Berita berhasil disimpan.', 'is_saved' => true], 201);
    }

    // Menghapus berita (DELETE /api/saved-news/{id_berita})
    public function destroy($id_berita)
    {
        $userId = Auth::id();

        $savedEntry = TbSimpanBerita::where('id_user', $userId)
                                    ->where('id_berita', $id_berita)
                                    ->firstOrFail();

        $savedEntry->delete();

        return response()->json(['message' => 'Berita berhasil dihapus dari daftar simpanan.', 'is_saved' => false], 200);
    }

    // Mengecek status (GET /api/saved-news/check/{id_berita})
    public function check($id_berita)
    {
        $userId = Auth::id();

        $isSaved = TbSimpanBerita::where('id_user', $userId)
                                 ->where('id_berita', $id_berita)
                                 ->exists();

        return response()->json(['is_saved' => $isSaved]);
    }

    // Mendapatkan daftar (GET /api/saved-news)
    public function index()
    {
        $savedNews = TbSimpanBerita::where('id_user', Auth::id())
                                   ->with('berita')
                                   ->latest('created_at')
                                   ->get();

        return response()->json(['data' => $savedNews]);
    }
}
