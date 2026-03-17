<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PertanyaanResource;
use App\Models\Penulis;
use App\Models\Pertanyaan;
use App\Services\Query\Tulisan\PertanyaanServis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PertanyaanController extends Controller
{
    /**
     * SUBMIT PERTANYAAN (Kirim Pesan)
     */
    public function submitTanya(Request $request)
    {
        // 1. CEK PENGIRIM (Dari Token)
        $pengirim = $request->user();

        if (!$pengirim) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        // 2. VALIDASI INPUT
        $request->validate([
            'pertanyaan' => 'required|string',
            'kepada'     => 'required', // Bisa ID atau SEO, kita handle di bawah
        ]);

        // 3. CEK PENERIMA (Target)
        // Frontend ngirim ID atau SEO? Kita cari user-nya dulu biar aman.
        $penerima = Penulis::where('id_penulis', $request->kepada)
                    ->orWhere('seo', $request->kepada)
                    ->first();

        if (!$penerima) {
            return response()->json(['status' => 'error', 'message' => 'Penulis tujuan tidak ditemukan'], 404);
        }

        // 4. SIAPKAN DATA
        $data = [
            'dari'               => $pengirim->id_penulis, // ID Saya
            'kepada'             => $penerima->id_penulis, // ID Dia
            'pertanyaan'         => $request->pertanyaan,  // Isi Pesan
            'tanggal_pertanyaan' => now()->format('Y-m-d H:i:s'),
            // 'jawaban' => null (Default DB)
        ];

        // 5. EKSEKUSI
        $create = Pertanyaan::create($data);

        if ($create) {
            return response()->json([
                'status'  => 'success',
                'message' => 'Pertanyaan berhasil dikirim!'
            ]);
        } else {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menyimpan ke database'
            ], 500);
        }
    }

    /**
     * LIST PERTANYAAN (KOTAK MASUK - INBOX)
     * Menampilkan pertanyaan yang MASUK ke user yang sedang login
     */
    public function akunTanyaList(Request $request, $id = null)
    {
        // 1. AUTH CHECK: Siapa SAYA?
        $user = $request->user();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $limit = $request->input('limit') ?? config('jp.api_paginate', 10);
        $limit = $limit > config('jp.maxlimit', 100) ? config('jp.maxlimit', 100) : $limit;

        // 2. QUERY LANGSUNG (Bypass Service biar logicnya pasti bener)
        // Cari Pertanyaan dimana 'kepada' == ID SAYA
        $data = Pertanyaan::query()
            ->where('kepada', $user->id_penulis)
            ->with(['dari_user', 'kepada_user']) // Eager Load relasi biar cepet (Pastikan relasi ada di Model Pertanyaan)
            ->latest('tanggal_pertanyaan')
            ->paginate($limit);

        // 3. FORMAT RESOURCE
        $formattedData = PertanyaanResource::collection($data);

        // 4. RETURN
        return $formattedData->additional([
            'status' => 'success',
            'section' => [
                'title' => 'Kotak Masuk',
                'link'  => config('jp.path_url_be') . "api/akun/list-pertanyaan/all",
            ]
        ]);
    }

    /**
     * LIST PERTANYAAN PUBLIC (Opsional, biarin aja kalau dipake di halaman lain)
     */
    public function listTanya(Request $request, PertanyaanServis $pertanyaan_servis)
    {
        $limit = request('limit') ?? config('jp.api_paginate');
        $limit = $limit > config('jp.maxlimit') ? config('jp.maxlimit') : $limit;

        $kepada = $request->input('kepada');

        $data = PertanyaanResource::collection($pertanyaan_servis->getListPertanyaan($limit, $kepada));

        $section = [
            'section' => [
                'title' => 'List Pertanyaan',
                'link'  => config('jp.path_url_be') . "api/pertanyaan/list",
            ]
        ];

        return $data->additional($section);
    }

    // JAWAB PERTANYAAN (Khusus Penerima)
    public function jawabPertanyaan(Request $request)
    {
        $user = $request->user(); // User yang lagi login (Penerima)

        $request->validate([
            'id_pertanyaan' => 'required|integer',
            'jawaban'       => 'required|string'
        ]);

        // Cari pertanyaan berdasarkan ID
        $pertanyaan = Pertanyaan::where('id_pertanyaan', $request->id_pertanyaan)->first();

        if (!$pertanyaan) {
            return response()->json(['status' => 'error', 'message' => 'Pertanyaan tidak ditemukan'], 404);
        }

        // PROTEKSI: Pastikan yang jawab adalah orang yang ditanya (Kepada)
        if ($pertanyaan->kepada != $user->id_penulis) {
            return response()->json(['status' => 'error', 'message' => 'Anda tidak berhak menjawab ini'], 403);
        }

        // Update Jawaban
        $update = $pertanyaan->update([
            'jawaban' => $request->jawaban,
            'tanggal_jawaban' => now() // Kalau ada kolom ini di DB
        ]);

        if ($update) {
            return response()->json([
                'status' => 'success',
                'message' => 'Jawaban berhasil dikirim!'
            ]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Gagal menyimpan jawaban'], 500);
        }
    }
}


