<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\Opini;
use App\Models\Penulis;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use DateTime;
use DateTimeZone;

class DashboardController extends Controller
{
    //
    public function index(): View
    {
        $Now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
        $datetime = $Now->format('Y-m-d H:i:s');
        $month = $Now->format('M');
        $month =3;

        $role_id = auth()->user()->roles[0]->id;
        $penggunas = auth()->user()->pengguna->biro->penggunaz->pluck('id_pengguna');

   
        $this_month = Berita::whereYear('date_publish_berita', $Now->format('Y'))
                    ->whereMonth('date_publish_berita',$month)
                    ->whereIn('id_pengguna',  $penggunas)->count();
        $berita = Berita:: whereIn('id_pengguna',  $penggunas)->count();
        $publish_news = Berita::where('status_berita','=','Publish')->whereIn('id_pengguna',  $penggunas)->count();
        $draft_news = Berita::where('status_berita','=','Draft')->whereIn('id_pengguna',  $penggunas)->count();
        $opini = Opini::where('tipe_opini','=','opini')->whereIn('id_penulis_opini',  $penggunas)->count();
        $publish_opini = Opini::where('status_opini','=','Publish')
                                ->where('tipe_opini','=','opini')->whereIn('id_penulis_opini',  $penggunas)->count();
        $draft_opini = Opini::where('status_opini','=','Draft')
                        ->where('tipe_opini','=','opini')->whereIn('id_penulis_opini',  $penggunas)->count();
        $penulis = Penulis::count();
        $pengguna = User::count();


        $DataBerita = Berita::select(Berita::raw("COUNT(*) as count"), 
                            DB::raw("Year(date_publish_berita) year"), 
                            DB::raw("Month(date_publish_berita) month"),
                            DB::raw("(case WHEN month(date_publish_berita)=1  then 'JAN'
                            WHEN month(date_publish_berita)=2  then 'FEB'
                            WHEN month(date_publish_berita)=3  then 'MAR'
                            WHEN month(date_publish_berita)=4  then 'APR'
                            WHEN month(date_publish_berita)=5  then 'MEI'
                            WHEN month(date_publish_berita)=6  then 'JUN'
                            WHEN month(date_publish_berita)=7  then 'JUL'
                            WHEN month(date_publish_berita)=8  then 'AGT'
                            WHEN month(date_publish_berita)=9  then 'SEPT'
                            WHEN month(date_publish_berita)=10 then 'OKT'
                            WHEN month(date_publish_berita)=11 then 'NOV'
                            WHEN month(date_publish_berita)=12 then 'DES' END) as BULAN"), 
                            DB::raw("(case WHEN month(date_publish_berita)=1  then '#D81B60'
                            WHEN month(date_publish_berita)=2  then '#00a65a'
                            WHEN month(date_publish_berita)=3  then '#D81C10'
                            WHEN month(date_publish_berita)=4  then '#d2d6de'
                            WHEN month(date_publish_berita)=5  then '#1d9141'
                            WHEN month(date_publish_berita)=6  then '#d1962b'
                            WHEN month(date_publish_berita)=7  then '#a049bd'
                            WHEN month(date_publish_berita)=8  then '#3e70e3'
                            WHEN month(date_publish_berita)=9  then '#1d9142'
                            WHEN month(date_publish_berita)=10 then '#e34a4a'
                            WHEN month(date_publish_berita)=11 then '#d1962b'
                            WHEN month(date_publish_berita)=12 then '#b1962b' END) as COLOR"),
                            DB::raw("(case WHEN month(date_publish_berita)=1 then '#D81B60'
                            WHEN month(date_publish_berita)=2  then '#00a65a'
                            WHEN month(date_publish_berita)=3  then '#D81C10'
                            WHEN month(date_publish_berita)=4  then '#d2d6de'
                            WHEN month(date_publish_berita)=5  then '#1d9141'
                            WHEN month(date_publish_berita)=6  then '#d1962b'
                            WHEN month(date_publish_berita)=7  then '#a049bd'
                            WHEN month(date_publish_berita)=8  then '#3e70e3'
                            WHEN month(date_publish_berita)=9  then '#1d9142'
                            WHEN month(date_publish_berita)=10 then '#e34a4a'
                            WHEN month(date_publish_berita)=11 then '#d1962b'
                            WHEN month(date_publish_berita)=12 then '#b1962b' END) as HIGHLIGHT"),
                            ) 
                    ->where('status_berita','Publish')
                    ->whereIn('id_pengguna',  $penggunas)
                    ->groupBy(DB::raw("Month(date_publish_berita)"))
                    ->groupBy(DB::raw("Year(date_publish_berita)"))
                    ->groupBy("BULAN","COLOR","HIGHLIGHT")
                    
                    ->oldest(DB::raw("Year(date_publish_berita)"))
                    ->oldest(DB::raw("Month(date_publish_berita)"))
                    // ->pluck('count','year', 'month');
                    ->get()->toArray(); 
                    
                    $dataku = json_encode($DataBerita);
                  

        $data = array();
        $data = [   
                'DataBerita'=> $dataku,
                'this_month'=> $this_month,
                'news_count'=> $berita,
                'count_publish_news' => $publish_news,
                'count_draft_news' => $draft_news,
                'opini_count'=> $opini,
                'count_publish_opini' => $publish_opini,
                'count_draft_opini' => $draft_opini,
                'penulis' => $penulis,
                'pengguna' => $pengguna     
        ];

        // echo "<pre>";print_r($data);exit;
        return view ('dashboardadmin',$data);

    }
}
