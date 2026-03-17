<?php

namespace App\Http\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Footbar;
use Illuminate\Database\Eloquent\Builder;

class FootbarTable extends DataTableComponent
{
    protected $model = Footbar::class;

    public function builder(): Builder
    {
        return  Footbar::query()
                    ->where('judul_status', '=', '1')
                    ->oldest('no_urut')
                    ->select('tb_footbar.*');
    }


    public function configure(): void
    {
        $this->setPrimaryKey('id_footbar');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id_footbar")
            ->sortable()
            ->hideIf(true),
        Column::make("Nama", "judul_footbar")
            ->searchable()
            ->sortable(),
        Column::make("Tag", "tag_judul")
            ->searchable()
            ->sortable(),
        Column::make("Action", "id_footbar")
            ->format(function( $id_footbar) {
                    $edit = '<a href="'.route('layout.editmenubawah','id='.$id_footbar.'').'" class="btn btn-sm btn-secondary"><i class="mdi mdi-pencil mdi-12px"></i></a>';
                
                return $edit;
            })
            ->html(),
        ];
    }
}
