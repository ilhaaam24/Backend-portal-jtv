<?php

namespace App\Http\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Livereport;
use Illuminate\Database\Eloquent\Builder;

class LivereportTable extends DataTableComponent
{
    protected $model = Livereport::class;

    public function builder(): Builder
    {
        return  Livereport::query()
                    ->oldest('id_livereport')
                    ->select('tb_livereport.*');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id_livereport')
        ->setThAttributes(function(Column $column) {
            if ($column->isField('deskripsi')) {
                return ['class' => 'text-center'];
            }

            if ($column->isField('status_livereport')) {
                return ['class' => 'text-center'];
            }

            return ['default' => true];
        })
        ->setTrAttributes(function($row, $index) {
            if ($index  >= 0 )  {
                return ['class' => 'text-center'];
            }


            return ['default' => true];
        })
          ;
    }

  
    public function columns(): array
    {
        return [
            Column::make("Id", "id_livereport")
                ->searchable()
                ->hideIf(true),
            Column::make("Keterangan", "deskripsi")
                ->searchable(),
          /*   Column::make("Status", "status_livereport")
                ->searchable()
                ->sortable(), */
            Column::make("Status", 'status_livereport')
                ->format(function($status_livereport) {
                    if($status_livereport == '1'){
                        $btn_detail = '<button type="button"
                        data-bs-toggle="modal" data-bs-target="#modalGetImage"
                        class="btn btn-sm btn-success get-antenna">
                        <i class="mdi mdi-access-point mdi-12px"></i></button>';
                    }else{
                        $btn_detail = '<button type="button"
                        data-bs-toggle="modal" data-bs-target="#modalGetImage"
                        class="btn btn-sm btn-secondary get-image-iklan">
                        <i class="mdi mdi-access-point-off mdi-12px"></i></button>';
                    }
                       
                        return  $btn_detail;
                })
                ->html(),
        ];
    }
}
