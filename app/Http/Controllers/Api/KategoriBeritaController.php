<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BiroResource;
use Illuminate\Http\Request;
use App\Http\Resources\KategoriBeritaResource;
use App\Http\Resources\RubrikResource;
use App\Models\Biro;
use App\Services\Query\Navbar\Navbar;

class KategoriBeritaController extends Controller
{
    ////
     /**
     * index
     *
     * @return void
     */
    public function index(Navbar $navbar)
    {   
        $section =[
            'section' => [
                'title' => 'Kategori',
                'link' =>  config('jp.path_url_be')."api/navbar/kategori",
            ],
            'biro' => BiroResource::collection(Biro::all())
        
        ];

        $kategoriberita = KategoriBeritaResource::collection($navbar->getKategori())
            ->additional($section);

        return  $kategoriberita;
    }

    public function rubrikAktif(Navbar $navbar)
    {   
        $section =[
            'section' => [
                'title' => 'Rubrik',
                'link' =>  config('jp.path_url_be')."api/navbar/rubrik",
            ]];

        $rubrik_aktif = RubrikResource::collection($navbar->getRubrik())
            ->additional($section);
        
        return  $rubrik_aktif;
    }
}
