<?php

use App\Http\Controllers\AdminBeritaController;
use App\Http\Controllers\AdminCommentController; // [BARU] Import Controller Komentar
use App\Http\Controllers\Api\BeritaController;
use App\Http\Controllers\Api\BiroController;
use App\Http\Controllers\Api\IklanController;
use App\Http\Controllers\Api\KategoriBeritaController;
use App\Http\Controllers\Api\PenulisController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FootbarController;
use App\Http\Controllers\FooterController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\IklanController as ControllersIklanController;
use App\Http\Controllers\KategoriBeritaController as ControllersKategoriBerita;
use App\Http\Controllers\LogoController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\NavbarController;
use App\Http\Controllers\NavigationController;
use App\Http\Controllers\OpiniController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\PenulisController as ControllersPenulisController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RoleController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\SorotController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;
use App\Models\Navigation;
use App\Models\Pengguna;
use Intervention\Image\Facades\Image;
use App\Http\Controllers\AdminCurhatanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

/* Route::get('/dashboard', function () {
    return view('dashboard_m');
   
})->middleware(['auth', 'verified'])->name('dashboard');
 */

Route::get('/dashboard2', function () {
    // return view('dashboard');
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard2');

Route::middleware(['auth', 'verified'])->group(function () {
   Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware('auth')->group(function(){
    //roles
    // Route::resource('roles', RoleController::class);
    Route::get('/konfigurasi/roles', [RolesController::class, 'index']);
    Route::post('getEditRole', [RolesController::class, 'updateRoleMenu'])->name('getEditRole'); //get edit nav
    Route::post('SubmitRoleMenu', [RolesController::class, 'SubmitRoleMenu'])->name('SubmitRoleMenu'); //get edit nav
    
    
    Route::get('/konfigurasi/permission', [PermissionController::class, 'index']);
    Route::post('permissionStore', [PermissionController::class, 'permissionStore'])->name('permission.store');
    Route::post('permissionUpdate', [PermissionController::class, 'permissionUpdate'])->name('permission.update');

    //user
    Route::get('/konfigurasi/user', [UserController::class, 'index'])->name('user_config');


    //Navigation CMS
    Route::get('/konfigurasi/navigation',[NavigationController::class, 'getNavigationCMS'])->name('navigation'); //index
    Route::get('nestableCMS',[NavigationController::class, 'nestableCMS'])->name('nestableCMS'); //index
    Route::post('reorder/nav', [NavigationController::class, 'reorderNav'])->name('reorderNav'); //re-order
    Route::post('getLastSeq', [NavigationController::class, 'getLastSeq'])->name('getLastSeq'); //get no urut
    Route::post('getParentMenu', [NavigationController::class, 'getParentMenu'])->name('getParentMenu'); //get no urut
    Route::post('navigasi/store', [NavigationController::class, 'navigasiStore'])->name('navigasi.store'); //re-order
    Route::post('navigasi/storeUpdate', [NavigationController::class, 'navigasiStoreUpdate'])->name('navigasi.storeUpdate'); //re-order
    Route::post('getEditNav', [NavigationController::class, 'getEditNav'])->name('getEditNav'); //get edit nav
    Route::post('deleteNav', [NavigationController::class, 'deleteNav'])->name('deleteNav'); //delete

    //REPORT
    Route::get('/report/berita',[AdminBeritaController::class, 'reportBerita'])->name('reportBerita'); //index
    Route::post('getBiro',[AdminBeritaController::class, 'getBiro'])->name('getBiro'); //index
    Route::post('getReportNews',[AdminBeritaController::class, 'getReportNews'])->name('getReportNews'); //index

    //
    Route::post('menustop/reorder', [MenuController::class, 'postIndex']); //re-order
    Route::post('menustop/new', [MenuController::class, 'postNew'])->name('topnew'); //create
    Route::get('menustop/{id}', [MenuController::class, 'getEdit']); //edit page
    Route::put('menustop/{id}', [MenuController::class, 'postEdit'])->name('topeditupdate'); //update data (edit)
    Route::delete('topmenudelete',  [MenuController::class, 'postDelete']); //delete item

    Route::get('getCategoryDetails/{id}', [MenuController::class, 'getCategoryDetails']); //get category title and slug based on selected option

    Route::get('manage-menus/{id?}',[MenuController::class,'index']);
    Route::post('create-menu',[MenuController::class,'store']); 
    Route::get('add-categories-to-menu',[MenuController::class,'addCatToMenu']);
    Route::get('add-post-to-menu',[MenuController::class,'addPostToMenu']);
    Route::get('add-custom-link',[MenuController::class,'addCustomLink']);  
    Route::get('update-menu',[MenuController::class,'updateMenu']); 
    Route::post('update-menuitem/{id}',[MenuController::class,'updateMenuItem']);
    Route::get('delete-menuitem/{id}/{key}/{in?}',[MenuController::class,'deleteMenuItem']);
    Route::get('delete-menu/{id}',[MenuController::class,'destroy']);   

    Route::get('/master/penulis/{id}', [PenulisController::class, 'listdata']);
    // Route::get('/master/penulis', [PenulisController::class, 'getpenulis']);
    Route::get('/opini/data', [OpiniController::class, 'index']);

    //livewire
    Route::get('/tipetulisan', App\Http\Livewire\TipeTulisan\Index::class)->name('tipetulisan.index');
    Route::get('/tipetulisan/create', App\Http\Livewire\TipeTulisan\Create::class)->name('tipetulisan.create');
    Route::get('/tipetulisan/edit/{id}', App\Http\Livewire\TipeTulisan\Edit::class)->name('tipetulisan.edit');

    // KONTEN -> BERITA POSTS
    // view, index, create, edit
    Route::get('/konten/berita', [AdminBeritaController::class, 'index'])->name('konten.berita');
    Route::get('/konten/berita/create', [AdminBeritaController::class, 'create'])->name('berita.create');
    Route::get('/konten/berita/edit', [AdminBeritaController::class, 'getEdit'])->name('berita.update');

    //simpan baru dan update
    Route::post('/konten/berita/create', [AdminBeritaController::class, 'store'])->name('berita.store');
    Route::post('/konten/berita/update', [AdminBeritaController::class, 'updateStore'])->name('berita.editstore');
    Route::post('/konten/berita/list_terbaru', [AdminBeritaController::class, 'list_terbaru'])->name('berita.list_terbaru');
    Route::post('/konten/berita/list_terbaik', [AdminBeritaController::class, 'list_terbaik'])->name('berita.list_terbaik');
    //delete data
    Route::get('/konten/berita/delete', [AdminBeritaController::class, 'destroyBerita'])->name('berita.destroy');
    Route::post('/konten/berita/trashed', [AdminBeritaController::class, 'TrashBerita'])->name('berita.trashed');
    
    // ---  MANAJEMEN KOMENTAR ---
    // --- [LENGKAP] MANAJEMEN KOMENTAR (CRUD) ---
    Route::get('/konten/komentar', [AdminCommentController::class, 'index'])->name('admin.comments.index');
    Route::get('/konten/komentar/create', [AdminCommentController::class, 'create'])->name('admin.comments.create'); // Halaman Tambah
    Route::post('/konten/komentar', [AdminCommentController::class, 'store'])->name('admin.comments.store');       // Proses Simpan
    Route::get('/konten/komentar/{id}/edit', [AdminCommentController::class, 'edit'])->name('admin.comments.edit'); // Halaman Edit
    Route::put('/konten/komentar/{id}', [AdminCommentController::class, 'update'])->name('admin.comments.update');  // Proses Update
    Route::delete('/konten/komentar/{id}', [AdminCommentController::class, 'destroy'])->name('admin.comments.destroy'); // Hapus
    Route::delete('/admin/comments/destroy-all-toxic', [AdminCommentController::class, 'destroyAllToxic'])
    ->name('admin.comments.destroyAllToxic');
    // -------------------------------------------

     //GALLERY data
     Route::post('/cek/image', [AdminBeritaController::class, 'cekImage'])->name('cek.image');
    //upload single image berita
    Route::post('/dropzone/store', [AdminBeritaController::class, 'dropzoneStore'])->name('dropzone.store');
    Route::post('/dropzone/uploads', [AdminBeritaController::class, 'storeDzuploads'])->name('uploads');

    Route::get('/dropzone/getimages',[AdminBeritaController::class, 'getImages'])->name('dropzone.getImages');
    Route::post('/dropzone/delete',[AdminBeritaController::class, 'destroyImages'])->name('dropzone.destroy');

    Route::get('/getimages_gallery',[AdminBeritaController::class, 'getImages_gallery'])->name('getImages_gallery');
    Route::post('/search_getimages_gallery',[AdminBeritaController::class, 'getImageSearch'])->name('getImageSearch');
    

    // KONTEN -> JURNALISME WARGA
    // view, index, create, edit
    Route::get('/konten/jurnalisme_warga', [OpiniController::class, 'jurnalisme_warga'])->name('konten.jurnalismewarga');
    Route::get('/konten/jurnalisme_warga/create', [OpiniController::class, 'createJurnal'])->name('jurnal.create');
    
    Route::get('/konten/jurnalisme_warga/edit', [OpiniController::class, 'getEditJurnalisme'])->name('edit.jurnalismewarga');

    //simpan update
    Route::post('/konten/jurnalisme_warga/create', [OpiniController::class, 'storeJurnal'])->name('jurnalismewarga.store');
    Route::post('/konten/jurnalisme_warga/update', [OpiniController::class, 'updateJurnal'])->name('jurnalismewarga.editstore');

    // delete data opini
    Route::get('/konten/jurnalisme_warga/delete', [OpiniController::class, 'destroyJurnal'])->name('destroy.jurnalismewarga');

    //gallery opini
    Route::post('/dropzone/store_opini', [OpiniController::class, 'dropzoneStore'])->name('dropzone.store_opini');
    Route::post('/dropzone/delete_opini',[OpiniController::class, 'destroyImages'])->name('dropzone.destroy_opini');
    
    Route::get('/getimages_gallery_opini',[OpiniController::class, 'getImages_gallery'])->name('getImages_galleryOpini');
    Route::post('/search_getimages_galleryopini',[OpiniController::class, 'getImageSearch'])->name('getImageSearchOpini');

    //menu iklan
    Route::get('/konten/iklan', [ControllersIklanController::class, 'index'])->name('konten.iklan');
    Route::get('/konten/iklan/edit', [ControllersIklanController::class, 'editiklan'])->name('konten.editiklan');
    Route::post('/konten/iklan/update', [ControllersIklanController::class, 'updateiklan'])->name('konten.updateiklan');
    Route::post('/konten/iklan/status_iklan', [ControllersIklanController::class, 'statusiklan'])->name('konten.statusiklan');

    //gallery iklan
    Route::get('/getImages_galleryiklan',[ControllersIklanController::class, 'getImages_galleryiklan'])->name('getImages_galleryiklan');
    Route::post('/search_getimages_galleryiklan',[ControllersIklanController::class, 'getImageSearchiklan'])->name('getImageSearchiklan');
    Route::post('/dropzone/store_iklan', [ControllersIklanController::class, 'dropzoneStore'])->name('dropzone.store_iklan');
    Route::post('/dropzone/delete_iklan',[ControllersIklanController::class, 'destroyImages'])->name('dropzone.destroy_iklan');

    //menu sorot
    Route::get('/konten/sorot', [SorotController::class, 'index'])->name('konten.sorot');
    Route::get('/konten/sorot/create', [SorotController::class, 'create'])->name('konten.createsorot');
    Route::get('/konten/sorot/edit', [SorotController::class, 'edit'])->name('konten.editsorot');
    Route::post('/konten/sorot/update', [SorotController::class, 'update'])->name('konten.updatesorot');
    Route::post('/konten/sorot/store', [SorotController::class, 'store'])->name('konten.storesorot');
    Route::post('/konten/sorot/delete', [SorotController::class, 'delete'])->name('konten.deletesorot');
    
    //gallery sorot
    Route::post('/dropzone/store_sorot', [SorotController::class, 'dropzoneStore'])->name('dropzone.store_sorot');
    Route::post('/dropzone/delete_sorot',[SorotController::class, 'destroyImages'])->name('dropzone.destroy_sorot');
    Route::get('/getImages_gallerysorot',[SorotController::class, 'getImages_gallerysorot'])->name('getImages_gallerysorot');
    Route::post('/search_getimages_gallerysorot',[SorotController::class, 'getImageSearchsorot'])->name('getImageSearchsorot');

    Route::get('/getimages_sorot_gallery',[SorotController::class, 'getImages_sorot_gallery'])->name('getImages_sorot_gallery');
    Route::post('/search_sorot_getimages_gallery',[SorotController::class, 'getImageSearch'])->name('getImageSearchSorot');

    //pengguna
    Route::get('/master/pengguna', [PenggunaController::class, 'index'])->name('master.pengguna');
    Route::post('getEditPengguna', [PenggunaController::class, 'getEditPengguna'])->name('getEditPengguna'); //get edit
    Route::post('updateIsActive', [PenggunaController::class, 'updateIsActive'])->name('updateIsActive'); //
    
    Route::post('pengguna/store', [PenggunaController::class, 'penggunaStore'])->name('pengguna.store'); //
    Route::post('pengguna/storeUpdate', [PenggunaController::class, 'penggunaStoreUpdate'])->name('pengguna.storeUpdate'); //

    Route::post('/pengguna/upload', [PenggunaController::class, 'dropzoneStore'])->name('pengguna.upload');
    Route::post('/pengguna/destroyImg',[PenggunaController::class, 'destroyImages'])->name('pengguna.destroyImg');
    Route::post('/pengguna/destroyImgPengguna',[PenggunaController::class, 'deleteGambarPengguna'])->name('deleteGambarPengguna');
    
    //penulis
    Route::get('/master/penulis', [ControllersPenulisController::class, 'index'])->name('master.penulis');
    Route::get('/konten/siaran_langsung', [ControllersIklanController::class, 'livereport'])->name('konten.livereport');
    Route::get('/konten/instagram_api', [ControllersIklanController::class, 'instagram_api'])->name('konten.instagram_api');

    Route::post('getEditPenulis', [ControllersPenulisController::class, 'getEditPenulis'])->name('getEditPenulis'); //get edit
    Route::post('penulis/store', [ControllersPenulisController::class, 'penulisStore'])->name('penulis.store'); //
    Route::post('penulis/storeUpdate', [ControllersPenulisController::class, 'penulisStoreUpdate'])->name('penulis.storeUpdate'); 
    
    //video
    Route::get('/konten/video', [VideoController::class, 'index'])->name('konten.video');
    Route::get('/konten/video/create', [VideoController::class, 'create'])->name('video.create');
    Route::get('/konten/video/edit', [VideoController::class, 'edit'])->name('video.edit');
    Route::post('/konten/video/update', [VideoController::class, 'update'])->name('video.update');
    Route::post('/konten/video/store', [VideoController::class, 'store'])->name('video.store');
    Route::post('/konten/video/delete', [VideoController::class, 'delete'])->name('video.delete');

    //logo
    Route::get('/layout/logo', [LogoController::class, 'index'])->name('layout.logo');
    Route::get('/layout/logo/edit', [LogoController::class, 'edit'])->name('layout.editlogo');

    //kategori
    Route::get('/layout/kategori', [ControllersKategoriBerita::class, 'index'])->name('layout.kategori');
    Route::get('/layout/kategori/edit', [ControllersKategoriBerita::class, 'edit'])->name('layout.editkategori');

    Route::get('nestableKategori',[ControllersKategoriBerita::class, 'nestableKategori'])->name('nestableKategori'); //index
    Route::post('reorder/kategori', [ControllersKategoriBerita::class, 'reorderKategori'])->name('reorderKategori'); //re-order
    
    Route::post('deleteKategori', [ControllersKategoriBerita::class, 'deleteKategori'])->name('deleteKategori'); //delete
    Route::post('getEditKategori', [ControllersKategoriBerita::class, 'getEditKategori'])->name('getEditKategori'); //get edit nav
    Route::post('deleteKategori', [ControllersKategoriBerita::class, 'deleteKategori'])->name('deleteKategori'); //delete
    Route::post('getLastUrutKat', [ControllersKategoriBerita::class, 'getLastUrutKat'])->name('getLastUrutKat'); //get no urut
     
    Route::post('kategori/store', [ControllersKategoriBerita::class, 'kategoriStore'])->name('kategori.store'); //re-order
    Route::post('kategori/storeUpdate', [ControllersKategoriBerita::class, 'kategoriStoreUpdate'])->name('kategori.storeUpdate'); //re-order
   
    //navbar
    Route::get('/layout/navbar', [NavbarController::class, 'index'])->name('layout.navbar');
    Route::get('/layout/navbar/edit', [NavbarController::class, 'edit'])->name('layout.editnavbar');

    Route::get('nestableNavbar',[NavbarController::class, 'nestableNavbar'])->name('nestableNavbar'); //index
    Route::post('reorder/navbar', [NavbarController::class, 'reorderNavbar'])->name('reorderNavbar'); //re-order
    
    Route::post('deleteNavbar', [NavbarController::class, 'deleteNavbar'])->name('deleteNavbar'); //delete
    Route::post('getEditNavbar', [NavbarController::class, 'getEditNavbar'])->name('getEditNavbar'); //get edit nav
    Route::post('deleteNavbar', [NavbarController::class, 'deleteNavbar'])->name('deleteNavbar'); //delete
    Route::post('getLastNavbar', [NavbarController::class, 'getLastNavbar'])->name('getLastNavbar'); //get no urut
    
    Route::post('navbar/store', [NavbarController::class, 'navbarStore'])->name('navbar.store'); //re-order
    Route::post('navbar/storeUpdate', [NavbarController::class, 'navbarStoreUpdate'])->name('navbar.storeUpdate'); //re-order
     
    //menubawah
    Route::get('/layout/menu_bawah', [FootbarController::class, 'index'])->name('layout.menu_bawah');
    Route::get('/layout/menu_bawah/edit', [FootbarController::class, 'edit'])->name('layout.editmenubawah');

    Route::get('nestableFootbar',[FootbarController::class, 'nestableFootbar'])->name('nestableFootbar'); //index
    Route::post('reorder/footbar', [FootbarController::class, 'reorderFootbar'])->name('reorderFootbar'); //re-order
    Route::post('footbar/storeUpdate', [FootbarController::class, 'footbarStoreUpdate'])->name('footbar.storeUpdate'); //re-order
    Route::post('getEditFootbar', [FootbarController::class, 'getEditFootbar'])->name('getEditFootbar'); //get edit nav
  
    //footer
    Route::get('/layout/footer', [FooterController::class, 'index'])->name('layout.footer');
    Route::get('/layout/footer/edit', [FooterController::class, 'edit'])->name('layout.editfooter');

    //media
    Route::get('/media', [GalleryController::class, 'index'])->name('media');
    
    Route::post('/media/store', [GalleryController::class, 'dropzoneStore'])->name('media.store');
    Route::post('/media/destroy',[GalleryController::class, 'destroyImages'])->name('media.destroy');
    Route::post('/media/delete',[GalleryController::class, 'deleteImages'])->name('media.delete');
    Route::post('/media/save',[GalleryController::class, 'save_img_gallery'])->name('media.save');
    Route::post('/media/update',[GalleryController::class, 'update_img_gallery'])->name('media.update');

    //Biro\
     Route::get('/master/biro', [BiroController::class, 'viewBiro'])->name('master.biro');
     Route::post('SubmitBiroUpdate', [BiroController::class, 'SubmitBiroUpdate'])->name('SubmitBiroUpdate');
   
// CURHAT WARGA
Route::get('/konten/curhatan', [AdminCurhatanController::class, 'index'])->name('konten.curhatan');

// 2. Approve (Terima) - Menggunakan GET
Route::get('/konten/curhatan/approve', [AdminCurhatanController::class, 'approve'])->name('curhatan.approve');

// 3. Delete (Hapus) - Menggunakan GET (Sesuai contoh Berita Anda)
Route::get('/konten/curhatan/delete', [AdminCurhatanController::class, 'destroy'])->name('curhatan.destroy');

// 4. Halaman Detail & Form Balas
Route::get('/konten/curhatan/detail', [AdminCurhatanController::class, 'detail'])->name('curhatan.detail');

// 5. Proses Kirim Email Balasan
Route::post('/konten/curhatan/reply', [AdminCurhatanController::class, 'reply'])->name('curhatan.reply');
// CURHAT WARGA END

});