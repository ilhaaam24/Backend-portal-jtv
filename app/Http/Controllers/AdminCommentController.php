<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Penulis;
use App\Models\Berita;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminCommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        // --- [PERBAIKAN LOGIKA] HITUNG JUMLAH KOMENTAR TOXIC ---
        // Sebelumnya menghitung user (distinct), sekarang menghitung total baris komentarnya
        $totalPenulisToxic = Comment::where('content', 'LIKE', '%*%')->count();

        // [FITUR REALTIME] 
        // Jika index.blade.php mengirim request AJAX khusus tanya jumlah toxic
        if ($request->ajax() && $request->has('get_toxic_count')) {
            return response()->json(['total' => $totalPenulisToxic]);
        }

        // --- STATISTIK DASHBOARD ---
        $totalKomentar = Comment::count();
        $totalPenulis = Penulis::count(); 
        $komentarMingguIni = Comment::whereBetween('created_at', [Carbon::now('Asia/Jakarta')->startOfWeek(), Carbon::now('Asia/Jakarta')->endOfWeek()])->count();
        $penulisLoginMingguIni = Penulis::whereBetween('tgl_loginterakhir', [Carbon::now('Asia/Jakarta')->startOfWeek(), Carbon::now('Asia/Jakarta')->endOfWeek()])->count();

        // --- QUERY UTAMA UNTUK TABEL ---
        $query = Comment::with(['user', 'berita']);

        // 1. Logika Pencarian (Search)
        if ($request->has('q') && $request->q != '') {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('content', 'LIKE', '%' . $search . '%')
                  ->orWhereHas('user', function($subQ) use ($search) { 
                      $subQ->where('nama_penulis', 'LIKE', '%' . $search . '%'); 
                  });
            });
        }

        // 2. Logika Filter Toxic (Jika kotak merah diklik)
        if ($request->has('filter') && $request->filter == 'toxic') {
            $query->where('content', 'LIKE', '%*%');
        }

        // Ambil data (Pagination 20 per halaman)
        $comments = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('konten.komentar.index', compact(
            'comments', 
            'totalKomentar', 
            'totalPenulis', 
            'komentarMingguIni', 
            'penulisLoginMingguIni',
            'totalPenulisToxic'
        ));
    }

    public function create()
    {
        $penulis = Penulis::orderBy('nama_penulis', 'asc')->get();
        
        // Ambil 30 berita terbaru saja agar ringan
        $berita = Berita::select('id_berita', 'judul_berita', 'date_publish_berita')
                        ->orderBy('date_publish_berita', 'desc')
                        ->take(30)
                        ->get();

        return view('konten.komentar.create', compact('penulis', 'berita'));
    }

    public function store(Request $request)
    {
        $request->validate(['user_id' => 'required', 'content' => 'required|string']);

        // [FITUR SENSOR] Filter kata kasar sebelum simpan
        $cleanContent = $this->filterBadWords($request->content);

        Comment::create([
            'user_id'   => $request->user_id,
            'id_berita' => $request->id_berita,
            'content'   => $cleanContent, // Simpan konten yang sudah bersih
            'status'    => 'active',
            'parent_id' => null
        ]);

        return redirect()->route('admin.comments.index')->with('success', 'Komentar berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $comment = Comment::findOrFail($id);
        $penulis = Penulis::orderBy('nama_penulis', 'asc')->get();
        
        $berita = Berita::select('id_berita', 'judul_berita', 'date_publish_berita')
                        ->orderBy('date_publish_berita', 'desc')
                        ->take(30)
                        ->get();

        return view('konten.komentar.edit', compact('comment', 'penulis', 'berita'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['user_id' => 'required', 'content' => 'required|string']);
        
        $comment = Comment::findOrFail($id);

        // [FITUR SENSOR] Filter kata kasar saat update juga
        $cleanContent = $this->filterBadWords($request->content);

        $comment->update([
            'user_id'   => $request->user_id,
            'id_berita' => $request->id_berita,
            'content'   => $cleanContent,
        ]);
        
        return redirect()->route('admin.comments.index')->with('success', 'Komentar berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $comment = Comment::find($id);
        if ($comment) {
            $comment->delete();
            return redirect()->back()->with('success', 'Komentar berhasil dihapus.');
        }
        return redirect()->back()->with('error', 'Komentar tidak ditemukan.');
    }

    // --- [FUNGSI BARU] HAPUS SEMUA KOMENTAR NEGATIF ---
    public function destroyAllToxic()
    {
        // Hapus semua komentar yang mengandung karakter sensor (*)
        $deleted = Comment::where('content', 'LIKE', '%*%')->delete();

        if ($deleted > 0) {
            return redirect()->back()->with('success', $deleted . ' komentar negatif berhasil dihapus semua.');
        } else {
            return redirect()->back()->with('error', 'Tidak ada komentar negatif untuk dihapus.');
        }
    }

    // --- [FUNGSI SENSOR KATA KASAR] ---
    private function filterBadWords($text)
    {
        // Daftar kata kasar
        $badWords = [
            'anjing', 'babi', 'monyet', 'bangsat', 'bgst', 'anjir', 'njir', 
            'goblok', 'tolol', 'bego', 'idiot', 'tai', 'taik', 'kampret', 
            'setan', 'iblis', 'bajingan', 'jancok', 'jancuk', 'asu', 'kontol', 
            'memek', 'pantek', 'puki', 'ngentot', 'sialan', 'brengsek', 'taek', 'taik', 'asu'
        ];

        foreach ($badWords as $word) {
            $replacement = str_repeat('*', strlen($word));
            $text = str_ireplace($word, $replacement, $text);
        }

        return $text;
    }
}