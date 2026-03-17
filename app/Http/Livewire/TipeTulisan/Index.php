<?php

namespace App\Http\Livewire\TipeTulisan;

use App\Models\Tipetulisan;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class Index extends Component
{

    public $judul, $kategori, $seo, $idtipetulisan;

    public function generateSlug()
    {
        $this->seo = Str::slug($this->judul);
    }


    private function resetInputFields(){
        $this->judul = '';
        $this->kategori = '';
        $this->seo = '';
    }

   /*  public function render()
    {
        return view('livewire.tipe-tulisan.index');
    } */
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // protected $listeners = ['deleteTipetulisan'];


    public $tipeCount;
 
    protected $listeners = ['postAdded' => 'incrementTipeCount', 'deleteTipetulisan'];
 
    public function incrementTipeCount()
    {
        $this->tipeCount = Tipetulisan::count();
    }


    public function deleteTipetulisan($idtipetulisan)
    {
        Tipetulisan::where('id',$idtipetulisan)->delete();

        session()->flash('message', 'Tipe Tulisan successfully deleted.');
    }

    public function render()
    {
        $tipetulisan = Tipetulisan::paginate(5);

        return view('livewire.tipe-tulisan.index', ['tipetulisan' => $tipetulisan])
            ->extends('layouts.materialize')
            ->section('content');;
    }

    public function edit($idtipetulisan)
    {
        $post = Tipetulisan::findOrFail($idtipetulisan);
        $this->idtipetulisan = $idtipetulisan;
        $this->judul = $post->judul;
        $this->kategori = $post->kategori;
        $this->seo = $post->seo;

        $this->emit('updateModal');
    }
    public function cancel()
    {

        $this->resetInputFields();
    }

    public function store()
    {
        $validatedDate = $this->validate([
            'judul' => 'required',
            'kategori' => 'required',
            'seo' => 'required',
        ]);
  
        Tipetulisan::create($validatedDate);
  
        session()->flash('message', 'Post Created Successfully.');
  
        $this->resetInputFields();
    }

  
    public function update()
    {
        $validated = $this->validate([
            'judul' => 'required',
            'kategori' => 'required',
            'seo' => 'required',
        ]);
  
        $tipetulisan = Tipetulisan::find($this->idtipetulisan);
        $tipetulisan->update([
            'judul' => $this->judul,
            'kategori' => $this->kategori,
            'seo' => $this->seo,
        ]);
  
        session()->flash('message', 'Post Updated Successfully.');
        $this->resetInputFields();
        $this->emit('updateSuccess');

    }
   
}
