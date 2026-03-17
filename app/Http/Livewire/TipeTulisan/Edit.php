<?php

namespace App\Http\Livewire\TipeTulisan;

use App\Models\Tipetulisan;
use Livewire\Component;

class Edit extends Component
{
  /*   public function render()
    {
        return view('livewire.tipe-tulisan.edit');
    } */

    public Tipetulisan $post;

    public $judul;
    public $kategori;

    public function mount()
    {
        // $this->judul = $this->post->judul;
        // $this->kategori = $this->post->kategori;
    }

    protected $rules = [
        'judul' => [
            'required'
        ],
        'kategori' => [
            'required'
        ]
    ];

    public function update()
    {

        $this->post->update($this->validate());

        session()->flash('message', 'Tipe Tulisan successfully updated.');

        return redirect()->route('tipetulisan.index');
    }

    public function render()
    {
        return view('livewire.tipe-tulisan.edit')
            ->extends('layouts.materialize')
            ->section('content');
    }
}
