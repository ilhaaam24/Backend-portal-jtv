<?php

namespace App\Http\Livewire\Berita;

use Livewire\Component;
use Illuminate\Support\Str;

class SeoBerita extends Component
{
    
    public $judul; 
    public $seo;
    public $link;


    public function mount($data)
    {
        if($data){
            $this->judul = $data->judul_berita;
            $this->seo = $data->seo_berita;
        }
       
    }

    public function generateSlug()
    {
        $final_seo = Str::slug($this->judul);
         $this->seo = $final_seo;
    }

    public function render()
    {
        return view('livewire.berita.seo-berita');
    }
}
