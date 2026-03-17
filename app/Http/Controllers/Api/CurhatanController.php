<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Curhatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CurhatanController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'message' => 'required|string|min:20',
            'image'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        // 2. Handle Upload Gambar
        $imagePath = null;
        if ($request->hasFile('image')) {
            // Simpan ke folder 'storage/app/public/curhat-images'
            $imagePath = $request->file('image')->store('curhat-images', 'public');
        }

        // 3. Simpan ke Database
        // Catatan: is_approved sudah dihapus karena fitur tayang dihilangkan
        // is_replied akan otomatis bernilai '0' (false) dari default database
        $curhatan = Curhatan::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'message'    => $request->message,
            'image_path' => $imagePath
        ]);

        // 4. Return Response JSON
        return response()->json([
            'success' => true,
            'message' => 'Curhatan berhasil dikirim!',
            'data'    => $curhatan
        ], 201);

    }
    // 2. READ (GET ALL)
    public function index()
    {
        $curhatans = Curhatan::latest()->get();
        return response()->json([
            'success' => true,
            'message' => 'List Data Curhatan',
            'data'    => $curhatans
        ], 200);
    }

    // 3. READ DETAIL (GET ONE)
    public function show($id)
    {
        $curhatan = Curhatan::find($id);

        if (!$curhatan) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail Data Curhatan',
            'data'    => $curhatan
        ], 200);
    }

    // 4. UPDATE (PUT) - Misalnya untuk mengubah status jadi sudah dibalas via API
    public function update(Request $request, $id)
    {
        $curhatan = Curhatan::find($id);

        if (!$curhatan) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        // Update status (Contoh: Admin Mobile ingin menandai sudah dibalas)
        $curhatan->update([
            'is_replied' => true
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status curhatan berhasil diupdate!',
            'data'    => $curhatan
        ], 200);
    }

    // 5. DELETE (DELETE)
    public function destroy($id)
    {
        $curhatan = Curhatan::find($id);

        if (!$curhatan) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        // Hapus Gambar jika ada
        if ($curhatan->image_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($curhatan->image_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($curhatan->image_path);
        }

        $curhatan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data curhatan berhasil dihapus!',
        ], 200);
    }
}