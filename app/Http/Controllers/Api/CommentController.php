<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\CommentLike; // Pastikan Model Like di-import
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    // GET: Ambil Daftar Komentar (FLAT LIST - UNTUK RECURSIVE FRONTEND)
    public function index($id_berita)
    {
        // 1. Ambil SEMUA data komentar aktif (Kita HAPUS whereNull('parent_id'))
        // Kita hanya butuh load 'user' penulisnya. Tidak perlu 'replies' karena kita ambil flat.
        $query = Comment::with(['user']) 
            ->where('status', 'active')
            ->orderBy('created_at', 'asc'); // Urutkan dari terlama biar alur percakapan nyambung

        // 2. Filter Berita (0 = Komentar Umum)
        if ($id_berita == 0 || $id_berita == '0') {
            $query->where(function($q) {
                $q->whereNull('id_berita')->orWhere('id_berita', 0);
            });
        } else {
            $query->where('id_berita', $id_berita);
        }

        $comments = $query->get();

        // 3. Manual Loop untuk Hitung Like & Status Liked
        // Karena datanya flat (rata), kita cukup loop sekali saja.
        $user = auth('sanctum')->user(); 

        $comments->each(function ($comment) use ($user) {
            // A. Hitung Total Like
            $comment->likes_count = CommentLike::where('comment_id', $comment->id)->count();
            
            // B. Cek apakah user login sudah like?
            $comment->is_liked = $user 
                ? CommentLike::where('comment_id', $comment->id)->where('id_penulis', $user->id_penulis)->exists() 
                : false;
        });

        return response()->json([
            'success' => true,
            'data' => $comments
        ]);
    }

    // POST: Simpan Komentar
    public function store(Request $request, $id_berita)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = $request->user(); 
        if (!$user) {
            return response()->json(['message' => 'Unauthorized.'], 401);
        }

        $finalIdBerita = ($id_berita == 0 || $id_berita == '0') ? null : $id_berita;

        // [FITUR SENSOR] Bersihkan konten
        $cleanContent = $this->filterBadWords($request->content);

        $comment = Comment::create([
            'id_berita' => $finalIdBerita,
            'user_id'   => $user->id_penulis, 
            'content'   => $cleanContent, 
            'parent_id' => $request->parent_id ?? null,
            'status'    => 'active' 
        ]);

        // Load data user agar saat response balik, frontend bisa langsung menampilkan nama/foto
        $comment->load('user');

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil dikirim',
            'data' => $comment
        ], 201);
    }

    // POST: Toggle Like (Like / Unlike)
    public function toggleLike(Request $request, $id)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Cek existing like
        $existingLike = CommentLike::where('id_penulis', $user->id_penulis)
                                    ->where('comment_id', $id)
                                    ->first();

        if ($existingLike) {
            $existingLike->delete();
            $liked = false;
            $msg = 'Unliked';
        } else {
            CommentLike::create([
                'id_penulis' => $user->id_penulis,
                'comment_id' => $id
            ]);
            $liked = true;
            $msg = 'Liked';
        }

        // Hitung total like terbaru
        $totalLikes = CommentLike::where('comment_id', $id)->count();

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'total_likes' => $totalLikes,
            'message' => $msg
        ]);
    }

    // UPDATE: Edit Komentar
    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);
        if (!$comment) return response()->json(['message' => 'Not found'], 404);
        
        $currentUser = $request->user();
        if ($comment->user_id !== $currentUser->id_penulis) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $cleanContent = $this->filterBadWords($request->content);

        $comment->update(['content' => $cleanContent]);
        return response()->json(['success' => true, 'message' => 'Updated']);
    }

    // DELETE: Hapus Komentar
    public function destroy(Request $request, $id)
    {
        $comment = Comment::find($id);
        if (!$comment) return response()->json(['message' => 'Komentar tidak ditemukan'], 404);
        
        $currentUser = $request->user();
        // Cek kepemilikan
        if ($comment->user_id !== $currentUser->id_penulis) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        $comment->delete();
        return response()->json(['success' => true, 'message' => 'Komentar dihapus']);
    }

    // --- FUNGSI SENSOR KATA KASAR ---
    private function filterBadWords($text)
    {
        $badWords = [
            // Bahasa Indonesia & Slang Umum
            'anjing', 'babi', 'monyet', 'bangsat', 'bgst', 'anjir', 'njir', 
            'goblok', 'tolol', 'bego', 'idiot', 'tai', 'taik', 'kampret', 
            'setan', 'iblis', 'bajingan', 'jancok', 'jancuk', 'asu', 'kontol', 
            'memek', 'pantek', 'puki', 'ngentot', 'sialan', 'brengsek', 'cukimay', 
            'pepek', 'jembut', 'lonte', 'pelacur', 'bencong', 'banci', 'jablay', 
            'maho', 'ngehe', 'sompret', 'kampang', 'kimak', 'pukimak', 'silit',
            'bodat', 'bujang inam', 'keparat', 'biadab', 'nyet', 'tot', 'kntl', 'mmk', 'judol', 'tikus',

            // Bahasa Jawa
            'matamu', 'ndasmu', 'cangkemmu', 'dancok', 'damput', 'diancuk', 'gathel', 'cok', 
            'gatel', 'picek', 'wedhus', 'kere', 'jamput', 'jaran', 'celeng', 'modar','kerek', 'mpek',
            'congor', 'cocot', 'tempek', 'kontol', 'telek', 'itil', 'turuk','taek', 'taik', 'asu', 'susu', 'tetek', 'empek', 
            'manuk', 'utekmu','edyan', 'edan', 'gendeng','kopler', 'kucluk', 'bokep', 'peler', 'tekos', 'jaancookkk',
        
            // Bahasa Inggris
            'fuck', 'shit', 'bitch', 'asshole', 'bastard', 'dick', 'pussy', 'cunt', 
            'motherfucker', 'whore', 'slut', 'idiot', 'stupid', 'moron', 'damn', 
            'nigger', 'nigga', 'faggot', 'retard', 'cock', 'tits', 'boobs', 'baby',
        ];

        foreach ($badWords as $word) {
            $replacement = str_repeat('*', strlen($word));
            $text = str_ireplace($word, $replacement, $text);
        }

        return $text;
    }
}