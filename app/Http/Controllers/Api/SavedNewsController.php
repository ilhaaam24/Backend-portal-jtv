<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TbSimpanBerita;
use Illuminate\Http\Request;

class SavedNewsController extends Controller
{
    // Menyimpan berita (POST /api/saved-news/{id_berita})
    public function store(Request $request, $id_berita)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['message' => 'Silakan login terlebih dahulu.'], 401);
        }

        $userId = $user->id_penulis;

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
    public function destroy(Request $request, $id_berita)
    {
        $user = $request->user();
        if (!$user) return response()->json(['message' => 'Unauthorized'], 401);
        
        $userId = $user->id_penulis;

        $savedEntry = TbSimpanBerita::where('id_user', $userId)
                                    ->where('id_berita', $id_berita)
                                    ->firstOrFail();

        $savedEntry->delete();

        return response()->json(['message' => 'Berita berhasil dihapus dari daftar simpanan.', 'is_saved' => false], 200);
    }

    // Mengecek status (GET /api/saved-news/check/{id_berita})
    public function check(Request $request, $id_berita)
    {
        $user = $request->user();
        if (!$user) return response()->json(['is_saved' => false]);
        
        $userId = $user->id_penulis;

        $isSaved = TbSimpanBerita::where('id_user', $userId)
                                 ->where('id_berita', $id_berita)
                                 ->exists();

        return response()->json(['is_saved' => $isSaved]);
    }

    // Mendapatkan daftar (GET /api/saved-news)
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user) return response()->json(['data' => []], 401);

        $savedNews = TbSimpanBerita::where('id_user', $user->id_penulis)
                                   ->with('berita')
                                   ->latest('created_at')
                                   ->get();

        return response()->json(['data' => $savedNews]);
    }
}
