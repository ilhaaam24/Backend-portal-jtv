<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Berita;
use App\Models\NewKategori;
use Illuminate\Support\Facades\Log;

class ForYouController extends Controller
{
    // 1. API LIST KATEGORI (Aman, gak gue ubah)
    public function getCategories()
    {
        $categories = NewKategori::where('status_kategori_berita', 1)
            ->orderBy('urut', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id_kategori_berita,
                    'name' => $item->nama_kategori_berita,
                ];
            });

        return response()->json([
            'status' => 'success',
            'data' => $categories
        ]);
    }

    // 2. API BERITA REKOMENDASI (FIXED LOGIC)
    public function getRecommendations(Request $request)
    {
        $limit = $request->query('limit', 10);
        $user = $request->user(); // Cek user dari token
        $debugMode = 'DEFAULT_LATEST';

        // Base Query
        $query = Berita::with(['kategori'])
                    ->where('status_berita', 'publish');

        // Init collection kosong biar gak error undefined variable
        $finalNewsCollection = collect([]);

        if ($user) {
            // === SKENARIO A: USER LOGIN (SMART MIXING) ===
            $debugMode = 'SMART_MIXING_USER';

            // 1. Ambil Minat User (Urut Score Tertinggi)
            $userInterests = $user->minat()
                                ->orderByDesc('tb_minat_penulis.score')
                                ->get();

            $sortedCatIds = $userInterests->pluck('id_kategori')->toArray();

            if (empty($sortedCatIds)) {
                // User login tapi belum set minat? Kasih latest aja
                $finalNewsCollection = $query->latest('date_publish_berita')->take($limit)->get();
            } else {
                // 2. Ambil Stok Berita (Fetch lebih banyak buat di-mix)
                $fetchLimit = $limit * 3;

                $rawNews = $query->whereIn('id_kategori', $sortedCatIds)
                                ->latest('date_publish_berita')
                                ->take($fetchLimit)
                                ->get();

                // 3. Grouping
                $groupedNews = $rawNews->groupBy('id_kategori');

                // Variabel penampung hasil mixing (INI YANG KEMAREN ERROR)
                $finalCollection = collect([]);

                // 4. Algoritma Round Robin
                $batchSize = 2;
                $maxLoop = 20;

                while ($groupedNews->isNotEmpty() && $maxLoop > 0 && $finalCollection->count() < $limit) {
                    foreach ($sortedCatIds as $catId) {
                        if ($groupedNews->has($catId)) {
                            // Ambil potongan berita
                            $chunk = $groupedNews[$catId]->splice(0, $batchSize);

                            if ($chunk->isNotEmpty()) {
                                $finalCollection = $finalCollection->merge($chunk);
                            }

                            // Kalau stok habis, hapus key biar gak diloop lagi
                            if ($groupedNews[$catId]->isEmpty()) {
                                $groupedNews->forget($catId);
                            }

                            // Cek kuota
                            if ($finalCollection->count() >= $limit) break 2;
                        }
                    }
                    $maxLoop--;
                }

                // Assign hasil mixing ke variable utama
                $finalNewsCollection = $finalCollection;
            }

        } else {
            // === SKENARIO B: GUEST (FILTER MANUAL) ===
            $debugMode = 'GUEST_FILTER';
            $categoryInput = $request->query('categories');

            if ($categoryInput) {
                $cats = explode(',', $categoryInput);

                // Translator: Pastikan dapet ID yang valid
                $catIds = NewKategori::whereIn('id_kategori_berita', $cats) // Cek ID langsung
                            ->orWhereIn('nama_kategori_berita', $cats)      // Cek Nama
                            ->orWhereIn('seo_kategori_berita', $cats)       // Cek SEO
                            ->pluck('id_kategori_berita');

                if($catIds->isNotEmpty()){
                    $query->whereIn('id_kategori', $catIds);
                }
            }

            // Guest selalu latest based on filter
            $finalNewsCollection = $query->orderByDesc('date_publish_berita')
                                         ->take($limit)
                                         ->get();
        }

        // Mapping Output JSON
        $mapped = $finalNewsCollection->map(function ($item) use ($user) {
            $score = 0;
            if ($user && $item->kategori) {
                 // Cek score manual (karena query builder di atas gak join pivot)
                 $minat = $user->minat->where('id_kategori', $item->id_kategori)->first();
                 $score = $minat ? $minat->pivot->score : 0;
            }

            return [
                'id'            => $item->id_berita,
                'title'         => $item->judul_berita,
                'seo'           => $item->seo_berita,
                'photo'         => $item->imageNews(), // Pastikan method ini ada di Model Berita
                'date'          => $item->date_publish_berita,
                'category_name' => optional($item->kategori)->nama_kategori_berita ?? 'Umum',
                'score'         => $score,
            ];
        });

        return response()->json([
            'status' => 'success',
            'mode'   => $debugMode,
            'count'  => $mapped->count(),
            'data'   => $mapped
        ]);
    }

    // 3. TRACK INTEREST (Gak gue ubah, aman)
    public function trackInterest(Request $request)
    {
        $user = $request->user();
        $inputKategori = $request->kategori_id;

        if (!$user || !$inputKategori) return response()->json(['status' => 'ok']);

        $kategori = NewKategori::where('id_kategori_berita', $inputKategori)
                        ->orWhere('nama_kategori_berita', $inputKategori)
                        ->orWhere('seo_kategori_berita', $inputKategori)
                        ->first();

        if (!$kategori) {
            return response()->json(['status' => 'error', 'message' => 'Kategori 404'], 404);
        }

        $idValid = $kategori->id_kategori_berita;

        $existing = $user->minat()->where('tb_minat_penulis.id_kategori', $idValid)->first();

        if ($existing) {
            $newScore = min($existing->pivot->score + 35, 9999999999);
            $user->minat()->updateExistingPivot($idValid, ['score' => $newScore]);
        } else {
            $user->minat()->attach($idValid, ['score' => 35]);
        }

        return response()->json(['status' => 'success']);
    }

    // 4. SAVE INTERESTS (Gak gue ubah, aman)
    public function saveInterests(Request $request)
    {
        $request->validate(['categories' => 'required|array|min:1']);
        $user = $request->user();
        $syncData = [];

        foreach($request->categories as $inputCat) {
            $kategori = NewKategori::where('id_kategori_berita', $inputCat)
                        ->orWhere('nama_kategori_berita', $inputCat)
                        ->orWhere('seo_kategori_berita', $inputCat)
                        ->first();

            if ($kategori) {
                $validId = $kategori->id_kategori_berita;
                $syncData[$validId] = ['score' => 100];
            }
        }

        if (empty($syncData)) return response()->json(['status' => 'error', 'message' => 'No valid categories']);

        $user->minat()->sync($syncData);
        return response()->json(['status' => 'success', 'message' => 'Preferensi disimpan!']);
    }

    // 5. GET USER INTERESTS (Gak gue ubah, aman)
    public function getUserInterests(Request $request)
    {
        $user = $request->user();
        if (!$user) return response()->json(['status' => 'error', 'data' => []], 401);
        $interestIds = $user->minat()->pluck('tb_kategori_berita.id_kategori_berita');
        return response()->json(['status' => 'success', 'data' => $interestIds]);
    }
}
