<?php

namespace App\Http\Livewire\Berita;

use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\TbBerita;

class CreateBerita extends Component
{
 

    public function render()
    {
        return view('livewire.berita.create-berita');
    }

    protected $rules = [
        'judul' => 'required',
        'seo' => 'required',
        'artikel_berita' => 'required'
    ];

    public function storePost()
    {
        return $this->validate();

        try {
            TbBerita::create(
                $this->validate()
                /* [
                'title' => $this->title,
                'description' => $this->description
                ] */
            );
            session()->flash('success','Post Created Successfully!!');
            // $this->resetFields();
        } catch (\Exception $ex) {
            session()->flash('error','Something goes wrong!!');
        }
    }

}
