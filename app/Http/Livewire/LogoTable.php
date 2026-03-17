<?php

namespace App\Http\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Logo;
use Illuminate\Database\Eloquent\Builder;

class LogoTable extends DataTableComponent
{
    protected $model = Logo::class;

    public function builder(): Builder
    {
        return  Logo::query()
                    ->oldest('id_logo')
                    ->select('tb_logo.*');
    }


    public function configure(): void
    {
        $this->setPrimaryKey('id_logo');
    }

    public function columns(): array
    {
        return [
            Column::make("", "id_logo")
            ->searchable(),
        Column::make("Nama", "nama_file")
            ->searchable()
            ->sortable(),
   
        Column::make("Gambar", 'url')
        ->format(function($url) {
                $btn_detail = '<button type="button" data-img="'.$url.'"
                data-bs-toggle="modal" data-bs-target="#modalGetImage"
                class="btn btn-sm btn-info get-image-logo">
                <i class="mdi mdi-image-search mdi-12px"></i></button>';
                return  $btn_detail;
        })
        ->html(),
     

        Column::make("Keterangan", "keterangan")
            ->searchable()    
            ->sortable(),
        Column::make("Action", "id_logo")
            ->format(function( $id_logo) {
                    $edit = '<a href="'.route('layout.editlogo','id='.$id_logo.'').'" class="btn btn-sm btn-secondary"><i class="mdi mdi-pencil mdi-12px"></i></a>';
                
                return $edit;
            })
            ->html(),
        ];
    }
}
