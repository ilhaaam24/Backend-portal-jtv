<?php

namespace App\Http\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Iklan;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;

class IklanTable extends DataTableComponent
{
    protected $model = Iklan::class;

    public function builder(): Builder
    {
        return  Iklan::query()
                    ->oldest('id_iklan')
                    ->select('tb_iklan.*');
    }


    public function configure(): void
    {
        $this->setPrimaryKey('id_iklan');
    }

    public function columns(): array
    {
        return [
            Column::make("", "id_iklan")
                ->searchable(),
            Column::make("Nama", "nama_iklan")
                ->searchable()
                ->sortable(),
            Column::make("Posisi", "posisi_iklan")
                ->searchable()
                ->sortable()
                ->hideIf(true),
            Column::make("Kategori", "kategori")
                ->searchable()
                ->sortable(),
            Column::make("Gambar", 'gambar_iklan')
            ->format(function($gambar_iklan) {
                    $btn_detail = '<button type="button" data-img="'.$gambar_iklan.'"
                    data-bs-toggle="modal" data-bs-target="#modalGetImage"
                    class="btn btn-sm btn-info get-image-iklan">
                    <i class="mdi mdi-image-search mdi-12px"></i></button>';
                    return  $btn_detail;
            })
            ->html(),
         
            Column::make("Keterangan", "keterangan_iklan")
                ->searchable()    
                ->sortable(),
            Column::make("Action", "id_iklan")
                ->format(function( $id_iklan) {
                        $edit = '<a href="'.route('konten.editiklan','id='.$id_iklan.'').'" class="btn btn-sm btn-secondary"><i class="mdi mdi-pencil mdi-12px"></i></a>';
                    
                    return $edit;
                })
                ->html(),
            Column::make("Status")                
                ->label(function($row) {
                    if($row->status_iklan=='1'){
                        $stat_iklan = '<span class="badge bg-success" id="status_iklan" data-val="'.$row->status_iklan.'" data-id="'.$row->id_iklan.'"><span class="mdi mdi-check"></span></span>';
                    }elseif($row->status_iklan=='0'){
                        $stat_iklan = '<span class="badge bg-danger"  id="status_iklan" data-val="'.$row->status_iklan.'" data-id="'.$row->id_iklan.'"><span class="mdi mdi-minus"></span></span>';
                    }
                    return $stat_iklan;
                
                })
                ->html(),
        ];
    }
}
