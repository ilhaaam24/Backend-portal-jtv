<?php

namespace App\Http\Livewire\TipeTulisan;

use App\Models\Tipetulisan;
use Livewire\Component;
use Illuminate\Support\Str;

class Create extends Component
{
    // public function render()
    // {
    //     return view('livewire.tipe-tulisan.create');
    // }
    public $judul, $kategori , $seo;

    

    protected $rules = [
        'judul' => 'required',
        'kategori' => 'required',
        'seo' => 'required',
    ];

    public function generateSlug()
    {
        $this->seo = Str::slug($this->judul);
    }

    public function store()
    {
        Tipetulisan::create($this->validate());

        session()->flash('message', 'Tipetulisan successfully created.');

        return redirect()->route('tipetulisan.index');
    }

    public function render()
    {
        return view('livewire.tipe-tulisan.create')
            ->extends('layouts.materialize')
            ->section('content');
    }
}
