<?php

namespace App\Http\Controllers;

use App\DataTables\ReportBeritaDataTable;
use App\Http\Resources\BeritaResource;
use App\Models\Berita;
use App\Models\Biro;
use App\Models\Gallery;
use App\Models\HitCounter;
use App\Models\NewKategori;
use App\Models\Pengguna;
use App\Models\Tag;
use App\Models\TaxonomyTagging;
use App\Models\TbBerita;
use App\Models\TbSubKategoriBerita;
use App\Models\TbSubnavbar;
use App\Models\User;
use App\Services\FcmNotificationService;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DateTime;
use DateTimeZone;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Return_;
use Yajra\DataTables\Facades\DataTables;

class AdminBeritaController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');

    }

    public function paginate($items, $perPage = 50, $page = null, $options = [])
        {
            $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
            $items = $items instanceof Collection ? $items : Collection::make($items);
            return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
        }

    //
    public function index()
    {
        return view('konten.berita.index');
    }


    public function reportBerita(){
        return view('report.berita.index');
    }

    public function getReportNews(Request $request, ReportBeritaDataTable $dataTable){
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $filter_by = $request->input('filter_by');

        if($filter_by == 'author'){
            $berita =  Berita::from('tb_berita as a')
            ->select('b.nama_pengguna', 'd.nama_biro', DB::raw('count(a.id_berita) as total'))
            ->Join('tb_pengguna as b', 'a.id_author', '=', 'b.id_pengguna')
            ->Join('users as c', 'b.id_user', '=', 'c.id')
            ->Join('tb_biro as d', 'b.id_biro', '=', 'd.id')
            ->whereBetween('date_publish_berita', [$startDate, $endDate])
            ->groupBy('a.id_author')
            ->orderBy('d.nama_biro', 'DESC')
            ->orderBy('b.nama_pengguna', 'ASC')
            ->get();
        }else{
            $berita =  Berita::from('tb_berita as a')
            ->select('b.nama_pengguna', 'd.nama_biro', DB::raw('count(a.id_berita) as total'))
            ->Join('tb_pengguna as b', 'a.editor_berita', '=', 'b.id_pengguna')
            ->Join('users as c', 'b.id_user', '=', 'c.id')
            ->Join('tb_biro as d', 'b.id_biro', '=', 'd.id')
            ->whereBetween('date_publish_berita', [$startDate, $endDate])
            ->where('a.editor_berita', '!=', null)
            ->groupBy('a.editor_berita')
            ->orderBy('d.nama_biro', 'DESC')
            ->orderBy('b.nama_pengguna', 'ASC')
            ->get();

        }


        $result = [];
        if($berita) {
            $result['status'] = "success";
            $result['report'] = $berita;
        }else{
            $result['status'] = "error";

        }
        return response()->json($result);

    }

    public function getBiro(){
        $list_biro  = Biro::orderBy('id', 'asc')->get();

        $result = [];
        if($list_biro) {
            $result['status'] = "success";
            $result['list_biro'] = $list_biro;
        }else{
            $result['status'] = "error";

        }
        return response()->json($result);
    }

    public function getImages_gallery(Request $request)
    {
        $card_class = "crd-img";
        $img_class = "img_gallerys";

        $tableImages[] =array();
        $images = Gallery::latest('updated_at')
                        ->latest('created_at')
                        ->whereNotIn('tipe', ['profil'])->paginate(36);
        // $exist = '';
        $html = '';
        if ($request->ajax()) {

            foreach ($images as $row) {
                $filename =  $row->original_filename;
                $url_path =  $row->filename;

                if (Storage::exists("upload-gambar/$filename")) {
                    $path_server = asset("assets/upload-gambar/$filename");
                }else{
                    $path_server =  asset(config('jp.path_url_no_img'));
                }

                $html.='<div class="mb-2 col-4 col-md-2">
                <div class="mt-3 d-inline-flex position-relative">
                <div class="card '.$card_class.'  gap-2 d-grid border-light" id="lib_img_'.$row->id.'" >
                    <div id="image_'.$row->id.'">
                    <img class="b-lazy '.$img_class.' img rounded-top" id="imageId_'.$row->id.'"
                    data-id="img_'.$row->id.'"
                    data-idgallery="'.$row->id.'"
                    data-title="'.$row->title.'"
                    data-tipe="'.$row->tipe.'"
                    data-originalname="'.$row->original_filename.'"
                    data-filename="'.$row->filename.'"
                    data-imagecaption="'.$row->caption.'"
                    data-imagedescription="'.$row->description.'"
                    data-src="'.$path_server.'"
                    data-bs-toggle="tooltip"
                    title="'.$row->caption.'"
                    alt="'.$row->original_filename.'"
                    src="'.$path_server.'" max-width: 60px; height="120px">
                    <div class="w-100 badge bg-label-light text-muted rounded-bottom img_name_preview">'.substr($row->caption, 0, 13).'</div>
                    </div>
                </div>
            </div>
            </div>
            ';
            }
        }

        if($images){

            $data['status'] = "success";
            $data['message'] = 'Data Images get Successfully!';
            $data['html'] = mb_convert_encoding($html, 'UTF-8', 'UTF-8');


            $data['data'] = $images;

        }else{
            $data['status'] = "error";
            $data['message'] = 'Failed to load images!';
        }

        return response()->json($data);
    }

    public function url_exists($url){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

       return ($code == 200);
    }

    public function getImageSearch(Request $request)
    {
        $search =  $request->keyword;
            $card_class = "crd-img";
            $img_class = "img_gallerys";

        $images_gets = '';

        if (trim($request->keyword)) {
            $images_gets = Gallery::latest('updated_at')
                            ->latest('created_at')
                            ->where('original_filename','LIKE',"%{$search}%")
                            ->orWhere('caption','LIKE',"%{$search}%")
                            ->where('tipe','berita')
                           ->paginate(36);
        }

        $html = '';
        if ($request->ajax()) {

            foreach ($images_gets as $row) {
                $filename =  $row->original_filename;
                $filepath_img = config('jp.path_url_be').config('jp.path_img').$filename;

                $filename =  $row->original_filename;
                $url_path =  $row->filename;
                $filepath = public_path('assets/upload-gambar/').$filename;
                $filepath_img = config('jp.path_url_be').config('jp.path_img').$filename;
                $filelocal = url($url_path);

                if (Storage::exists("upload-gambar/$filename")) {
                    $path_server = asset("assets/upload-gambar/$filename");
                }else{
                    $path_server =  asset(config('jp.path_url_no_img'));
                }

                $src_img = $row->imageGallery();
                $html.='<div class="mb-2 col-4 col-md-2">
                <div class="card '.$card_class.' gap-2 d-grid border-light" id="lib_img_'.$row->id.'" >
                    <div id="image_'.$row->id.'">
                    <img class="b-lazy '.$img_class.' img rounded-top"  id="imageId_'.$row->id.'"
                    data-id="img_'.$row->id.'"
                    data-idgallery="'.$row->id.'"
                    data-title="'.$row->title.'"
                    data-tipe="'.$row->tipe.'"
                    data-originalname="'.$row->original_filename.'"
                    data-filename="'.$row->filename.'"
                    data-imagecaption="'.$row->caption.'"
                    data-imagedescription="'.$row->description.'"
                    data-src="'.$path_server.'"
                    data-bs-toggle="tooltip"
                    title="'.$row->caption.'"
                    alt="'.$row->original_filename.'"
                    src="'.$path_server.'" max-width: 60px; height="120px">
                    <div class="w-100 badge bg-label-light text-muted rounded-bottom img_name_preview">'.substr($row->caption, 0, 13).'</div>
                    </div>
                </div>
            </div>
            ';
            }
        }

        return response()->json([
            'list_getimages' => $images_gets,
            'html'           => $html,
            'filepath'           => $filepath
         ]);
    }

    public function create()
    {
        $pengguna_list = Pengguna::where('level', '=', '3')
        ->oldest('id_pengguna')->get();


        // return   $dataPengguna;
        $kategori_list = NewKategori::where('main_kategori_berita', null)
        ->where('status_kategori_berita', 1)->get();

        $id_pengguna = auth()->user();
        $pengguna_data = Pengguna::where('id_user', auth()->user()->id)->firstOrFail();

        if($pengguna_data->level == 4){
            $dataEditor = User::with(['pengguna' => function($query){
                $query->orderBy('id_pengguna', 'asc');
                }])->where('id', $id_pengguna->id)
                ->get();

            $dataPengguna =  User::with(['pengguna' => function($query){
                $query->orderBy('id_pengguna', 'asc');
                }])->where('id', $id_pengguna->id)
                ->get();
        }else{
            $dataEditor = User::with(['pengguna' => function($query){
                $query->orderBy('id_pengguna', 'asc');
                }])->whereHas("roles", function($q){ $q->where("name", "editor"); })->get();

            $dataPengguna = User::with(['pengguna' => function($query){
                $query->orderBy('id_pengguna', 'asc');
                }])->whereHas("roles", function($q){ $q->where("name", "author"); })->get();
        }

        $data = array();
        $data = [
                'pengguna_data' => $pengguna_data,
                'kategori_list' => $kategori_list,
                'status_form'   => 'create_form',
                'id_pengguna'   => $id_pengguna,
                'list_editor'   => $dataEditor,
                'list_pengguna' => $dataPengguna,
        ];
      return view('konten.berita.create', $data);
    }

    //get edit page
    // public function getEdit($id)
    public function getEdit(Request $request)
    {
        $id_pengguna = auth()->user();
        $get_biro = auth()->user()->pengguna->biro;
        $id_biro =  $get_biro->seo;
        $id = $request->id;
        $pengguna_list = Pengguna::where('level', '=', '3')
        ->oldest('id_pengguna')->get();

        $pengguna_data = Pengguna::where('id_user', auth()->user()->id)->firstOrFail();

        $kategori_list = NewKategori::where('main_kategori_berita', null)
                            ->where('status_kategori_berita', 1)->get();

        if($pengguna_data->level == 4){
            $dataEditor = User::with(['pengguna' => function($query){
                $query->orderBy('id_pengguna', 'asc');
                }])->where('id', $id_pengguna->id)
                ->get();

            $dataPengguna =  User::with(['pengguna' => function($query){
                $query->orderBy('id_pengguna', 'asc');
                }])->where('id', $id_pengguna->id)
                ->get();
        }else{

            $dataPengguna = User::with(['pengguna' => function($query){
                                    $query->orderBy('id_pengguna', 'asc');
                                    }])->whereHas("roles", function($q){ $q->where("name", "author"); })->get();

            $dataEditor = User::with(['pengguna' => function($query){
                $query->orderBy('id_pengguna', 'asc');
                }])->whereHas("roles", function($q){ $q->where("name", "editor"); })->get();
        }

        $tags_berita = TaxonomyTagging::select('tb_tag.nama_tag as namatag', 'tb_tag.seo_tag as seo_tag')
        ->where('tagging.id_berita', $id)
        ->join('tb_berita', 'tb_berita.id_berita', '=', 'tagging.id_berita')
        ->join('tb_tag', 'tb_tag.id_tag', '=', 'tagging.id_tag')
        ->get();

        $tag_value = [];
        foreach ($tags_berita as $data)
        {
            $tag_value[] = $data->namatag;
        }

        $tags_berita = implode(", ", $tag_value);

        $item = TbBerita::where('id_berita',$id)->firstOrFail();
        $filename =  $item->gambar_depan_berita;
        if($item->tipe_gambar_utama=='image'){
            $filepath_img = config('jp.path_url_be').config('jp.path_img').$filename;

            $img_gallery = Gallery::where('original_filename',$filename)->firstOrFail();

                $original_filename =  $img_gallery->original_filename;    //path original
                $url_path =  $img_gallery->filename;    //path original
                $filepath = public_path($url_path);     //local path
                $filepath_img = config('jp.path_url_be').config('jp.path_img').$filename; //path server
                    $filelocal = url($url_path); // url local

                    if (Storage::exists("upload-gambar/$original_filename")) {
                        $path_server = asset("assets/upload-gambar/$original_filename");
                    }else{
                        $path_server =  asset(config('jp.path_url_no_img'));
                    }

        }else if($item->tipe_gambar_utama=='video'){
            $path_server = 'https://img.youtube.com/vi/'. $item->gambar_depan_berita .'/sddefault.jpg';
        }else{
            $path_server = 'https://www.portaljtv.com/images/broken.webp';
        }

        $link_news = config('jp.path_url_fe').'news/'.$id_biro.'/'.$item->seo_berita;

     $data = array();
     $data = [
            'pengguna_data' => $pengguna_data,
             'img_utama'     => $path_server,
             'list_pengguna' => $dataPengguna,
             'kategori_list' => $kategori_list,
             'tags_berita'      => $tags_berita,
             'item'          => $item,
             'list_editor'   => $dataEditor,
             'link_news'    => $link_news,
             'status_form'   => 'edit_form',
     ];

      return view('konten.berita.create', $data);
    }

    public function store(Request $request)
    {
        $validator =  Validator::make($request->all(),[
            'judul_berita' => 'required',
            'seo_berita' => 'required',
            'id_pengguna' => 'required',
            'id_author' => 'required',
            // 'id_editor' => 'required',
            'rangkuman_berita' => 'required',
            'status_berita' => 'required',
//            'tags_berita' => 'required',
        ]);

        // Retrieve the validated input...
        $validator->validated();

        $ada = [];

        $param_insert = [
            'judul_berita' => $request->judul_berita,
            'seo_berita' => $request->seo_berita,
            'id_pengguna' => $request->id_pengguna,
            'id_author' => $request->id_author,
            'editor_berita' => $request->id_editor,
            'jabatan_author' => $request->jabatan_author,
            'id_approver' => '0',
            'kota_berita' => $request->kota_berita,
            'artikel_berita' => $request->artikel_berita,
            'rangkuman_berita' => $request->rangkuman_berita,
            'status_berita'             => $request->status_berita,
            'date_publish_berita'       => $request->date_publish_berita,
            'date_perubahan_berita'     => now(),
            'jam_publish_berita'        => date('H:i', strtotime($request->date_publish_berita)),
            'id_kategori'               => $request->id_kategori,
            'pengunjung_berita'         => '-',
            'tipe_berita'               => '-',
            'gambar_depan_berita'       => $request->addimage_berita,
            'caption_gambar_berita'     => $request->caption_gambar_berita,
            'watermark_gambar_berita'   => $request->watermark_gambar_berita,
            'tipe_berita_utama'         => $request->tipe_berita_utama,
            'tipe_berita_pilihan'       => $request->tipe_berita_pilihan,
            'tipe_gambar_utama'         => $request->tipe_gambar_utama,
        ];


        $param_hit_counter = [
            'seo_berita' => $request->seo_berita,
            'hit' => 0,
            'tipe' => 'berita',
        ];

        $hitcounter_cek = HitCounter::where('seo_berita', $request->seo_berita)->first();
        if($hitcounter_cek){
           $result['status'] = "error";
            $result['message'] = "SEO Berita sudah Digunakan!";
            return response()->json($result);
        }else{
            $createBerita = TbBerita::create($param_insert);

            if($createBerita){
                $lastId = $createBerita->id_berita;
                //Create Hit Counter
              $create_hitcounter = HitCounter::create($param_hit_counter);
                if($create_hitcounter){
                    //Create into Tb_berita
                    $create = TbBerita::create($param_insert);
                    $lastId = $create->id_berita;
                }else{
                    $result['status'] = "error";
                    $result['message'] = "Gagal Input Berita Baru!";
                    return response()->json($result);
                }
        }

        if($request->tags_berita){
            $data_tags = array_column(json_decode($request->tags_berita), 'value'); // item

            foreach($data_tags as $item){
                $ada[$item] = "hapus";
                $cek_tags = Tag::where('nama_tag', $item)->first();
                // Jika Tagging Belum Ada
                if($cek_tags == null){
                   $param_insert_tb_tag= [
                     'nama_tag' => $item,
                     'seo_tag' => $this->slugify($item),
                   ];
                   //Create New Tagging
                   $create_tb_tag = Tag::create($param_insert_tb_tag);
                   $lastIdTag = $create_tb_tag->id;

                   if($create_tb_tag){
                       $param_insert_tagging= [
                           'id_tag' => $lastIdTag,
                           'id_berita' => $lastId,
                         ];
                         // Create Taxonomy Tagging with Id Berita
                       $create_tagging = TaxonomyTagging::create($param_insert_tagging);
                       $lastId_tagging = $create_tagging->id;
                   }
               }
               // Jika Tagging Sudah Ada
               if($cek_tags){
                   $param_ins_tagging= [
                       'id_tag' => $cek_tags->id_tag,
                       'id_berita' => $lastId,
                     ];
                     // Create Taxonomy Tagging with Id Berita
                   $create_tagging = TaxonomyTagging::create($param_ins_tagging);
               }
           }
        }

        if($create) {
            // ✅ Kirim push notification jika status = Publish
            if ($request->status_berita === 'Publish') {
                try {
                    // Bangun URL gambar berita untuk notifikasi
                    $imageUrl = null;
                    if ($request->addimage_berita && $request->tipe_gambar_utama === 'image') {
                        $imageUrl = config('jp.path_url_be') . config('jp.path_img') . $request->addimage_berita;
                    }

                    app(FcmNotificationService::class)->sendToAll(
                        title: $request->judul_berita,
                        body: Str::limit(strip_tags($request->rangkuman_berita), 100),
                        data: ['seo' => $request->seo_berita, 'type' => 'berita_baru'],
                        imageUrl: $imageUrl,
                    );
                } catch (\Exception $e) {
                    \Log::error('[FCM] Gagal kirim notif saat store: ' . $e->getMessage());
                }
            }

            $result['lastId'] = $lastId;
            $result['status'] = "success";
            $result['message'] = "Post Created successfully!";
            $result['url'] = route('berita.update', ['id' => $lastId]);
            return response()->json($result);
        }else{
            $result['status'] = "failed";
            $result['message'] = "Post Updated Failed!";
            return response()->json($result);
        }
        return abort(500);
    }
}

    public function TrashBerita(Request $request)
    {
        $Now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
        $data = $request->all();
        $validated = $request->validate([
            'id_berita' => 'id',
        ]);

        $param_update_trash = [
            'status_berita'          => 'trash',
            'is_berita_terbaru'      => 0,
            'date_perubahan_berita'  => $Now->format('Y-m-d H:i:s'),
        ];

        $trashed =  TbBerita::where('id_berita',  $request->id)
        ->update($param_update_trash);

        if($trashed) {
            $result['status'] = "success";
            $result['message'] = "Berita berhasil di hapus ke Trash!";

        }else{
            $result['status'] = "failed";
            $result['message'] = "Berita gagal di hapus!";
        }
        return response()->json($result);
    }

    public function destroyBerita(Request $request)
    {
        $id =  $request->get('id');
        $databerita = TbBerita::where('id_berita',$id)->firstOrFail();

        $filename =  $databerita->gambar_depan_berita;
        // $filename =  $request->get('filename');
        Gallery::where('filename',$filename)->delete();
        $path = app_path($filename);

        $deleted_image = Gallery::where('filename', $filename)->delete();

        if (file_exists($path)) {
            unlink($path);
        }
        $deleted = TbBerita::where('id_berita', $id)->delete();


        if( $deleted){
            return redirect()->route('konten.berita');
        }
        return response()->json([ 'status'=>'success', 'src'=>$filename, 'path'=>$path]);
    }

    private function slugify($string){
       return $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
    }

    public function updateStore(Request $request)
    {

        $date = Carbon::createFromFormat('Y-m-d H:i', $request->date_publish_berita);
        $daysToAdd = 7;
        $date_exprd = $date->addDays($daysToAdd);

        $date_exp = Carbon::parse($date_exprd)->format('Y-m-d H:i');

        $validated = $request->validate([
            'judul_berita' => 'required',
            'seo_berita' => 'required',
            'id_pengguna' => 'required',
            'id_author' => 'required',
            'rangkuman_berita' => 'required',
            // 'id_editor' => 'required',
            'status_berita' => 'required',
            'date_publish_berita' => 'required',
        ]);

        //CEK Update Hit Counter
        $cek_berita = Berita::where('id_berita', $request->id_berita)->first();
        $old_seo =  $cek_berita->seo_berita;

        if ($old_seo != $request->seo_berita){
            $hitcounter_cek = HitCounter::where('seo_berita', $old_seo)->first();
            $id_counter = $hitcounter_cek->id_hit_counter;

            if($hitcounter_cek){
                $param_hit_counter_update = [
                    'seo_berita' => $request->seo_berita,
                ];
                //update hit counter
                HitCounter::where('id_hit_counter', $id_counter)->update($param_hit_counter_update);
            }
        }

        //Error jika Tags Kosong
       if(!isset($request->tags_berita)){
        $result['status'] = "error";
        $result['message'] = "Silakan Input Tags Berita";
        return response()->json($result);
       }

        $data_tags = array_column(json_decode($request->tags_berita), 'value'); // item

        $old_tagging = TaxonomyTagging::where('id_berita', $request->id_berita)
                            ->join('tb_tag', 'tagging.id_tag', '=', 'tb_tag.id_tag')->get(); //old item

        $ada = [];
        $hapustag = [];

        foreach($data_tags as $item){
            $ada[$item] = "hapus";
            // echo $item.'<br>';
            $cek_tags = Tag::where('nama_tag', $item)->first();

            if(!$cek_tags){
                $param_insert_tb_tag= [
                  'nama_tag' => $item,
                  'seo_tag' => $this->slugify($item),
                ];

                $create_tb_tag = Tag::create($param_insert_tb_tag);
                $lastId_tagging = $create_tb_tag->id;

                if($create_tb_tag){
                    $param_insert_tagging= [
                        'id_tag' => $lastId_tagging,
                        'id_berita' => $request->id_berita,
                      ];
                    $create_tagging = TaxonomyTagging::create($param_insert_tagging);
                    $lastId_tagging = $create_tagging->id;
                }
            }else{
                $cek_tagging = TaxonomyTagging::where('id_tag', $cek_tags->id_tag)
                ->where('id_berita', $request->id_berita)
                ->first();

                if(!$cek_tagging && $cek_tags){
                $param_ins_tagging= [
                    'id_tag' => $cek_tags->id_tag,
                    'id_berita' => $request->id_berita,
                ];

                $create_tagging = TaxonomyTagging::create($param_ins_tagging);
                }
            }
        }

        //hapus old tagging
        foreach($old_tagging as $old_item){
            if(!isset($ada[$old_item->nama_tag])){
                $hapustag[$old_item->nama_tag] = "hapus";

                $cek_tags_hapus = Tag::where('nama_tag', $old_item->nama_tag)->first();
                if($cek_tags_hapus){
                    $id_tag = $cek_tags_hapus->id_tag;
                    $id_berita = $request->id_berita;

                    TaxonomyTagging::where('id_tag', $id_tag)
                            ->where('id_berita',  $id_berita)
                            ->delete();
                }
            }

        //Param Update Tb Berita
        $param_update = [
            'judul_berita' => $request->judul_berita,
            'seo_berita' => $request->seo_berita,
            'id_pengguna' => $request->id_pengguna,
            'id_author' => $request->id_author,
            'editor_berita' => $request->id_editor,
            'jabatan_author' => $request->jabatan_author,
            'kota_berita' => $request->kota_berita,
            'artikel_berita' => $request->artikel_berita,
            'rangkuman_berita' => $request->rangkuman_berita,

            'status_berita'             => $request->status_berita,
            'date_publish_berita'       => $request->date_publish_berita,
            // 'expired_berita'            => $date_exp,
            'date_perubahan_berita'     => now(),
            'jam_publish_berita'        => date('H:i', strtotime($request->date_publish_berita)),
            'id_kategori'               => $request->id_kategori,
            // 'id_subkategori_berita'     => $request->id_subkategori_berita,
            'pengunjung_berita'         => '-',
            'tipe_berita'               => '-',

            'tipe_gambar_utama'         => $request->tipe_gambar_utama,
            'gambar_depan_berita'       => $request->addimage_berita,
            'caption_gambar_berita'     => $request->caption_gambar_berita,
            'watermark_gambar_berita'   => $request->watermark_gambar_berita,

            'tipe_berita_utama'         => $request->tipe_berita_utama,
            'tipe_berita_pilihan'       => $request->tipe_berita_pilihan,

        ];



        $updated =  TbBerita::where('id_berita',  $request->id_berita)
            ->update($param_update);


        if($updated) {
            // ✅ Kirim push notification jika status berubah ke Publish
            if ($request->status_berita === 'Publish' && $cek_berita->status_berita !== 'Publish') {
                try {
                    // Bangun URL gambar berita untuk notifikasi
                    $imageUrl = null;
                    if ($request->addimage_berita && $request->tipe_gambar_utama === 'image') {
                        $imageUrl = config('jp.path_url_be') . config('jp.path_img') . $request->addimage_berita;
                    }

                    app(FcmNotificationService::class)->sendToAll(
                        title: $request->judul_berita,
                        body: Str::limit(strip_tags($request->rangkuman_berita), 100),
                        data: ['seo' => $request->seo_berita, 'type' => 'berita_baru'],
                        imageUrl: $imageUrl,
                    );
                } catch (\Exception $e) {
                    \Log::error('[FCM] Gagal kirim notif saat update: ' . $e->getMessage());
                }
            }

            $result['status'] = "success";
            $result['message'] = "Post Updated successfully!";
            return response()->json($result);
        }else{
            $result['status'] = "failed";
            $result['message'] = "Post Updated Failed!";
            return response()->json($result);
        }
    }
}

    public function fetchSubmenu(Request $request)
    {
        $id_navbar =  $request->idmenu;
        $data['submenu'] = TbSubnavbar::where("id_navbar", $id_navbar)
                                ->oldest('sub_no_urut')
                                ->get(["judul_subnavbar", "id_subnavbar", "tag_subnavbar"]);
        return response()->json($data);
    }

    public function fetchKategori(Request $request)
    {
        $id_subnavbar =  $request->idsubmenu;
        $data['kategori'] = NewKategori::where("id_subnavbar_kategori_berita", $id_subnavbar)
                                ->oldest('id_kategori_berita')
                                ->get(["nama_kategori_berita", "id_kategori_berita", "seo_kategori_berita"]);
        return response()->json($data);
    }

    public function fetchSubKategori(Request $request)
    {
        $id_kategori_berita =  $request->idkategori;
        $data['subkategori'] = TbSubKategoriBerita::where("id_kategori", $id_kategori_berita)
                                ->oldest('id_subkategori')
                                ->get(["nama_subkategori", "seo_subkategori", "id_subkategori"]);
        return response()->json($data);
    }

    public function fetchSubKategoriNew(Request $request)
    {
        $id_kategori_berita =  $request->idkategori;
        $data['subkategori'] = NewKategori::where("main_kategori_berita", $id_kategori_berita)
                                ->oldest('id_kategori_berita')
                                ->get(["nama_kategori_berita", "seo_kategori_berita", "id_kategori_berita"]);
        return response()->json($data);
    }

       // same as update function when you make resource controller
       public function postEdit(Request $request, $id) //done
       {
         $item = TbBerita::where('id_berita',$id)->firstOrFail();
         $item->title = $request->input('judul_berita');
         $item->seo = $request->input('seo_berita');
         $item->save();
         return redirect()->route('menus', $item->id)->with('success', 'Item, '. $item->title.' updated');
       }

        public function storeDzuploads(Request $request)
        {
            $path = ('assets/upload-gambar/');

            (!file_exists($path)) && mkdir($path, 0755, true);

            $file = $request->file('file');
            $fileInfo = $file->getClientOriginalName();
            $filename = pathinfo($fileInfo, PATHINFO_FILENAME);
            $extension = pathinfo($fileInfo, PATHINFO_EXTENSION);

            $name = 'jtv' .uniqid() . '.' .$file->extension();
            $image_full_path  = $path. $name;
            $file->move(public_path('assets/upload-gambar/'),$name);

            $saveFile       = new Gallery();
            $saveFile->original_filename = $name;
            $saveFile->filename = $image_full_path;
            $saveFile->tipe = 'berita';
            $saveFile->save();

            if($saveFile->save())
            {
                $data['image_full_path'] = asset('').$image_full_path;
                $data['original_name'] = $file->getClientOriginalName();
                $data['image_path'] = $image_full_path;
                $data['image_name'] = $name;
                $data['success'] = 1;
                $data['message'] = 'Uploaded Successfully!';
            }
            else{
                $data['success'] = 0;
                $data['message'] = 'File not uploaded.';
            }
            return response()->json($data);
        }

        //CEK IMAGE
        public Function cekImage(Request $request){
            $Now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
            $path = ('assets/upload-gambar/');
            !is_dir($path) &&
            mkdir($path, 0755, true);

        $image = $request->file('file');

        $fileInfo = $image->getClientOriginalName();
        $filename = pathinfo($fileInfo, PATHINFO_FILENAME);
        $extension = pathinfo($fileInfo, PATHINFO_EXTENSION);
        $file_name= $filename.'-'.time().'.'.$extension;

        $imageName = time().'.'.$image->extension();
        $image->move(public_path('uploads/gallery'),$imageName);

        $image_full_path  = $path. $imageName;

        $saveFile       = new Gallery();
        $saveFile->original_filename = $imageName;
        $saveFile->filename = $image_full_path;
        $saveFile->save();

            if($saveFile->save())
            {
                $data['image_full_path'] = asset('').$image_full_path;
                $data['image_path'] = $image_full_path;
                $data['image_name'] = $imageName;
                $data['success'] = 1;
                $data['message'] = 'Uploaded Successfully!';
            }
            else{
                $data['success'] = 0;
                $data['message'] = 'File not uploaded.';
            }
        }

        // UPLOAD SINGLE DROPZONE
        public function dropzoneStore(Request $request)
        {
            $Now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
            $image = $request->file('file');
            return $folder_path = $request->folder_path;

            if($folder_path=='berita' || $folder_path=='opini'){
                $path = ('assets/upload-gambar/');
            }else if($folder_path=='iklan'){
                $path = ('assets/iklan/');
            }else if($folder_path=='sorot'){
                $path = ('assets/sorot/');
            }

            !is_dir($path) &&
                mkdir($path, 0755, true);
            $fileInfo = $image->getClientOriginalName();
            $filename = pathinfo($fileInfo, PATHINFO_FILENAME);
            $extension = pathinfo($fileInfo, PATHINFO_EXTENSION);
            $file_name= $filename.'-'.time().'.'.$extension;

            $imageName = 'jtv_'.time().'.'.$image->extension();
            $new_folder = public_path($path);
            $image->move($new_folder,$imageName);

            $image_full_path  = $path. $imageName;

            $saveFile       = new Gallery();
            $saveFile->original_filename = $imageName;
            $saveFile->filename = $image_full_path;
            $saveFile->tipe = 'berita';
            $saveFile->save();

            if($saveFile->save())
            {
                $data['image_full_path'] = asset('').$image_full_path;
                $data['image_path'] = $image_full_path;
                $data['image_name'] = $imageName;
                $data['success'] = 1;
                $data['message'] = 'Uploaded Successfully!';
            }
            else{
                $data['success'] = 0;
                $data['message'] = 'File not uploaded.';
            }
            return response()->json($data);
        }

        public function getImages()
        {
            $images = Gallery::all()->toArray();
            foreach($images as $image){
                $tableImages[] = $image['filename'];
            }
            $storeFolder = public_path('assets/upload-gambar');
            $file_path = public_path('assets/upload-gambar/');
            $files = scandir($storeFolder);
            foreach ( $files as $file ) {
                if ($file !='.' && $file !='..' && in_array($file,$tableImages)) {
                    $obj['name'] = $file;
                    $file_path = public_path('assets/upload-gambar/').$file;
                    $obj['size'] = filesize($file_path);
                    $obj['path'] = url('public/assets/upload-gambar/'.$file);
                    $data[] = $obj;
                }
            }
            return response()->json($data);
        }

        public function destroyImages(Request $request)
        {
            $filename =  $request->get('filename');
            $name =  $request->get('name');
            Gallery::where('filename',$filename)->delete();
            $path = public_path('assets/upload-gambar/').$name;

            if (file_exists($path)) {
                unlink($path);
            }
            return response()->json([ 'name'=>$name, 'src'=>$filename]);
        }


        public function list_terbaru(Request $request)
        {
            $Now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
            $data = $request->all();
            $validated = $request->validate([
                'id_berita' => 'id',
            ]);

            $value = 0;
            $databerita =  TbBerita::where('id_berita',  $request->id)->firstOrFail();
            $val_terbaru =  $databerita->is_berita_terbaru;
            $val_terbaru == 1 ? $value=0 : $value=1;

            $dataupdate['date_perubahan_berita'] = $Now->format('Y-m-d H:i:s');
            $dataupdate['is_berita_terbaru'] = $value;

            $updated =  TbBerita::where('id_berita',  $request->id)->update($dataupdate);
            // return $value;
            if($updated &&  $value==1) {
                $result['status'] = "success";
                $result['message'] = "Berhasil Aktifkan berita terbaru!";

            }else if($updated &&  $value==0) {
                $result['status'] = "success";
                $result['message'] = "Berhasil Non Aktif berita terbaru!";

            }else{
                $result['status'] = "failed";
                $result['message'] = "data gagal!";
            }
            return response()->json($result);
      }


      public function list_terbaik(Request $request)
        {
            $Now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
            $id_berita = $request->id;

            $value = 0;
            $databerita =  TbBerita::where('id_berita',  $id_berita)->firstOrFail();
            $val_terbaik=  $databerita->is_berita_terbaik;
            $value = $val_terbaik == 1 ? 0 : 1;

            $dataupdate['date_perubahan_berita'] = $Now->format('Y-m-d H:i:s');
            $dataupdate['is_berita_terbaik'] = $value;

            $updated =  TbBerita::where('id_berita',  $id_berita)->update($dataupdate);
            // return $value;
            if($updated &&  $value==1) {
                $result['status'] = "success";
                $result['message'] = "Berita Terbaik Berhasil di pilih!";

            }else if($updated &&  $value==0) {
                $result['status'] = "success";
                $result['message'] = "Berhasil Non Aktif Berita Terbaik!";

            }else{
                $result['status'] = "failed";
                $result['message'] = "data gagal!";
            }
            return response()->json($result);
      }

}
