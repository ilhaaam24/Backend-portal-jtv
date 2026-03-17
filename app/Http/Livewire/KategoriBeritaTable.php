<?php

namespace App\Http\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

use App\Models\TbKategoriBerita;
use Illuminate\Database\Eloquent\Builder;

class KategoriBeritaTable extends DataTableComponent
{
    protected $model = TbKategoriBerita::class;

    public function builder(): Builder
    {
        return  TbKategoriBerita::query()
                    ->where('status_kategori_berita', '=', '1')
                    ->oldest('id_kategori_berita')
                    ->select('tb_kategori_berita.*');
    }

    

    public function configure(): void
    {
        $this->setPrimaryKey('id_kategori_berita');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id_kategori_berita")
                ->sortable()
                ->hideIf(true),
            Column::make("Nama", "nama_kategori_berita")
                ->searchable()
                ->sortable(),
            Column::make("Seo", "seo_kategori_berita")
                ->searchable()
                ->sortable(),
            Column::make("Action", "id_kategori_berita")
                ->format(function( $id_kategori_berita) {
                        $edit = '<a href="'.route('layout.editkategori','id='.$id_kategori_berita.'').'" class="btn btn-sm btn-secondary"><i class="mdi mdi-pencil mdi-12px"></i></a>';
                    
                    return $edit;
                })
                ->html(),
        ];
    }
}
