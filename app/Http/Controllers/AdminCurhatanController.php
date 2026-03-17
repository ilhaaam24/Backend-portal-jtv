<?php

namespace App\Http\Controllers;

use App\Models\Curhatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\CurhatanReplyMail;

class AdminCurhatanController extends Controller
{
    public function index(Request $request)
    {
        // 1. Mulai Query dasar
        $query = Curhatan::latest();

        // 2. LOGIKA SEARCH (Pencarian Teks)
        if ($request->has('search') && $request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('message', 'like', "%$search%");
            });
        }

        // 3. LOGIKA FILTER STATUS
        // Filter berdasarkan kolom 'is_replied' (0 atau 1)
        if ($request->has('status') && $request->status !== null && $request->status !== '') {
            $query->where('is_replied', $request->status);
        }

        // 4. Eksekusi Query
        $curhatans = $query->get();

        return view('konten.curhatan.index', compact('curhatans'));
    }

    // --- FUNGSI APPROVE SUDAH DIHAPUS KARENA TIDAK DIPAKAI ---

    // FUNGSI HAPUS DATA
    public function destroy(Request $request)
    {
        // Ambil id dari parameter ?id=...
        $id = $request->id;

        $curhatan = Curhatan::findOrFail($id);

        // Hapus file gambar jika ada
        if ($curhatan->image_path && Storage::disk('public')->exists($curhatan->image_path)) {
            Storage::disk('public')->delete($curhatan->image_path);
        }

        $curhatan->delete();

        return redirect()->back()->with('success', 'Curhatan berhasil dihapus!');
    }

    // FUNGSI HALAMAN DETAIL
    public function detail(Request $request)
    {
        $id = $request->id;
        $curhatan = Curhatan::findOrFail($id);

        return view('konten.curhatan.detail', compact('curhatan'));
    }

    // FUNGSI KIRIM BALASAN EMAIL
    public function reply(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'reply_message' => 'required|string'
        ]);

        $curhatan = Curhatan::findOrFail($request->id);

        try {
            // 1. Kirim Email
            Mail::to($curhatan->email)->send(new CurhatanReplyMail($curhatan, $request->reply_message));

            // 2. UPDATE DATABASE: Tandai sudah dibalas
            $curhatan->update([
                'is_replied' => true
            ]);

            return redirect()->back()->with('success', 'Balasan berhasil dikirim dan status diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengirim email: ' . $e->getMessage());
        }
    }
}