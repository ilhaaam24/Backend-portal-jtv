<?php

namespace App\Http\Livewire\Opini;

use Livewire\Component;
use Illuminate\Support\Str;

class SeoOpini extends Component
{
    public $judul; 
    public $seo;
    public $link;


    public function mount($data)
    {
        if($data){
            $this->judul = $data->judul_opini;
            $this->seo = $data->seo_opini;
        }
       
    }

    public function generateSlug()
    {
        $final_seo = Str::slug($this->judul);
         $this->seo = $final_seo;
    

    }

    public function render()
    {
        return view('livewire.opini.seo-opini');
    }
}
