<?php

use App\Http\Controllers\AdminBeritaController;
use App\Http\Controllers\Api\AuthCheckController;
use App\Http\Controllers\Api\CommentController; // Import Controller Komentar
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\BeritaController;
use App\Http\Controllers\Api\FooterController;
use App\Http\Controllers\Api\IklanController;
use App\Http\Controllers\Api\KategoriBeritaController;
use App\Http\Controllers\Api\OpiniController;
use App\Http\Controllers\Api\VideoController;
use App\Http\Controllers\Api\PenulisController;
use App\Http\Controllers\Api\SorotController;
use App\Http\Controllers\Api\LivereportController;
use App\Http\Controllers\Api\PertanyaanController;
use App\Http\Controllers\Api\TipeTulisanController;
use App\Http\Controllers\Api\ForYouController;
use App\Http\Controllers\FootbarController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Api\FcmTokenController;
use App\Http\Controllers\Api\SavedNewsController;

use App\Http\Controllers\Api\CurhatanController;
use App\Http\Controllers\AdminCurhatanController;

/*
|--------------------------------------------------------------------------
| API Routes - PORTAL JTV (FULL REVAMP)
|--------------------------------------------------------------------------
*/

// 1. Public Routes (Gak butuh login)
// ---------------------------------------------------

// Navbar & Kategori
Route::prefix('navbar')->controller(KategoriBeritaController::class)->group(function () {
    Route::get('kategori', 'index');
    Route::get('rubrik', 'rubrikAktif');
});

// Berita Umum
Route::prefix('news')->controller(BeritaController::class)->group(function () {
    Route::get('headline/{id}', 'beritaHeadline');
    Route::get('terbaru/{id}', 'beritaTerbaru');
    Route::get('pilihan/{id}', 'beritaPilihan');
    Route::get('populer/{id}', 'beritaPopuler');
    Route::get('breaking/{id}', 'beritaBreaking');
    Route::get('terbaik', 'beritaTerbaik');
    Route::get('detail/{id}', 'detailBerita');
    Route::get('kategori/{id}', 'kategori');
    Route::get('kanal/{id}', 'kanal');
    Route::get('search/{id}', 'search');
    Route::get('tag/{id}', 'tag');
    Route::get('author/{id}', 'author');
    Route::post('search', 'searchBerita');
    Route::post('index-berita', 'indexBerita');
    Route::get('list-author', 'listAuthor');
});

// For You (Public Part)
Route::prefix('news')->group(function () {
    Route::get('/categories-list', [ForYouController::class, 'getCategories']);
    Route::get('/for-you', [ForYouController::class, 'getRecommendations']);
});

// Auth Public
Route::post('/auth/sign-up', [AuthCheckController::class, 'signup']);
Route::post('/auth/sign-in', [AuthCheckController::class, 'index']);
Route::post('/auth/firebase', [AuthCheckController::class, 'firebase']);
Route::post('/auth/reset-password', [AuthCheckController::class, 'resetPassword']);

// Konten Lain (Public)
Route::prefix('sorot')->controller(SorotController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/{id}', 'detailSorot');
});
// --- API KOMENTAR (PUBLIC - FULL CRUD) ---
// GET (Public) - Siapapun bisa baca komentar
Route::get('/berita/{id}/comments', [CommentController::class, 'index']);

// Protected Routes (Hanya User Login)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/berita/{id}/comments', [CommentController::class, 'store']);
    Route::put('/comments/{id}', [CommentController::class, 'update']);
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']);

    // [UPDATE] Route Baru untuk Like Komentar
    Route::post('/comments/{id}/like', [CommentController::class, 'toggleLike']);
});


Route::prefix('tulisan')->controller(OpiniController::class)->group(function () {
    Route::get('/{id}', 'index');
    Route::get('/{id}/{id2}', 'konsultasikategori');
});

Route::prefix('tulisan-tag')->get('/{id}', [OpiniController::class, 'tagOpini']);
Route::get('/tulisan-detail/{slug}', [OpiniController::class, 'detailkonsultasikategori']);
Route::prefix('tulisan-author')->get('/{id}', [OpiniController::class, 'konsultasiauthor']);
Route::get('/tulisan-list-author', [OpiniController::class, 'tulisanListAuthor']);

Route::prefix('video')->controller(VideoController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/{id}', 'detailVideo');
});

Route::get('/livestream', [LivereportController::class, 'index']);
Route::get('/socialmedia', [FooterController::class, 'index']);
Route::post('/hit', [BeritaController::class, 'hit_counter']);
Route::get('/iklan', [IklanController::class, 'index']);
Route::get('/tipe-tulisan', [TipeTulisanController::class, 'index']);
Route::get('/warga', [OpiniController::class, 'jurnalismeWarga']);
Route::get('/penulis', [PenulisController::class, 'index']);

// FCM Push Notification Token
Route::prefix('fcm')->controller(FcmTokenController::class)->group(function () {
    Route::post('register', 'register');     // Simpan device token
    Route::post('unregister', 'unregister'); // Hapus device token (logout/uninstall)
});

Route::prefix('page')->group(function () {
    Route::get('kontak', [OpiniController::class, 'kontak']);
    Route::get('pedoman', [OpiniController::class, 'pedoman']);
    Route::get('redaksi', [OpiniController::class, 'redaksi']);
    Route::get('tentang', [OpiniController::class, 'tentang']);
    Route::get('kebijakan', [OpiniController::class, 'kebijakan']);
    Route::get('/{id}', [FootbarController::class, 'pageStatic']);
});

Route::prefix('pertanyaan')->controller(PertanyaanController::class)->group(function () {
    Route::get('/list', 'listTanya');
});

Route::prefix('tag')->controller(TagController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/whitelist', 'WhitelistTags')->name('whitelist.tag');
});

// Admin Helper (Mungkin butuh auth admin, tapi biarin dulu sesuai asli)
Route::post('/fetch-submenu', [AdminBeritaController::class, 'fetchSubmenu']);
Route::post('/fetch-kategori', [AdminBeritaController::class, 'fetchKategori']);
Route::post('/fetch-subkategori', [AdminBeritaController::class, 'fetchSubKategori']);
Route::post('/fetch-subkategoriNew', [AdminBeritaController::class, 'fetchSubKategoriNew']);
Route::post('/fetch-user', [UserController::class, 'fetchUser']);


// 2. PRIVATE ROUTES (BUTUH LOGIN / TOKEN) 🔥
// ---------------------------------------------------
Route::middleware('auth:sanctum')->group(function () {

    // Test User
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Akun & Profil
    Route::get('/akun/profile', [AuthCheckController::class, 'akunProfil']);
    Route::post('/akun/update', [AuthCheckController::class, 'penulisUpdateProfile']);
    Route::post('/auth/logout', [AuthCheckController::class, 'logout']);

    // Fitur For You (Simpan Minat)
    Route::post('news/track-interest', [ForYouController::class, 'trackInterest']);
    Route::post('news/save-interests', [ForYouController::class, 'saveInterests']);
    Route::get('news/my-interests', [ForYouController::class, 'getUserInterests']);

    // Fitur Tulisan Penulis (INI YANG TADINYA DI LUAR)
    Route::post('/akun/tulisan/submit', [AuthCheckController::class, 'tulisanSubmit']);
    Route::post('/akun/tulisan/update/{opini}', [AuthCheckController::class, 'tulisanUpdate']);
    Route::delete('/akun/tulisan/delete/{opini}', [AuthCheckController::class, 'tulisanDestroy']);
    Route::get('/akun/tulisan/list', [AuthCheckController::class, 'tulisanList']);

    // Pertanyaan User
    Route::get('/akun/list-pertanyaan/all', [PertanyaanController::class, 'akunTanyaList']);
    Route::post('/pertanyaan/submit', [PertanyaanController::class, 'submitTanya']);
    Route::post('/pertanyaan/jawab', [PertanyaanController::class, 'jawabPertanyaan']);
});


// --- API CURHAT WARGA ---
Route::post('/curhatan', [CurhatanController::class, 'store']);
Route::get('/konten/curhatan', [AdminCurhatanController::class, 'index']);

// 2. READ (Lihat Semua Data)
Route::get('/curhatan', [CurhatanController::class, 'index']);

// 3. READ DETAIL (Lihat 1 Data)
Route::get('/curhatan/{id}', [CurhatanController::class, 'show']);

// 4. UPDATE (Update Status sudah dibalas)
Route::put('/curhatan/{id}', [CurhatanController::class, 'update']);

// 5. DELETE (Hapus Data)
Route::delete('/curhatan/{id}', [CurhatanController::class, 'destroy']);
// --- API CURHAT WARGA END ---

Route::get('/akun/list-pertanyaan/{id}', [PertanyaanController::class, 'akunTanyaList']);


/* -------------------------END API ADMIN FRONT END USER---------------------------- */

// Saved News Routes
Route::middleware(['auth:sanctum'])->prefix('saved-news')->group(function () {
    Route::post('/{id_berita}', [SavedNewsController::class, 'store']);
    Route::delete('/{id_berita}', [SavedNewsController::class, 'destroy']);
    Route::get('/check/{id_berita}', [SavedNewsController::class, 'check']);
    Route::get('/', [SavedNewsController::class, 'index']);
});
