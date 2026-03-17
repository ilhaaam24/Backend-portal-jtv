<?php

namespace App\Http\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Permission;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Permission as ModelsPermission;

class PermissionTable extends DataTableComponent
{
    protected $model = Permission::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id')
        ->setPerPageAccepted([10, 25, 50, 100])
        ->setPerPage(25)
        ->setThAttributes(function(Column $column) {
            if ($column->isField('id')) {
                return [
                  'width' => '10%',
                ];
              }

            if ($column->isField('name')) {
              return [
                'width' => '25%',
              ];
            }

            if ($column->isField('navigation.name')) {
              return [
                'width' => '15%',
              ];
            }

            if ($column->isField('created_at')) {
                return [
                  'width' => '20%',
                ];
              }
            
        
            return [];
          })
        ->setSingleSortingEnabled();
    }
    
    public function builder(): Builder
    {
  
        return Permission::query()
        // ->where('status_berita', '!=', 'trash')
        ->oldest('id')
        ->latest('navigation_id');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Nama Permission", "name")
                ->searchable()
                ->sortable(),
            Column::make("Menu", "navigation_id")
                ->sortable()
                ->hideIf(true),
            Column::make("Menu", "navigation.name")
                ->searchable()
                ->sortable(),
            Column::make("Created at", "created_at")
                ->sortable()
                ->format(function( $created_at ) {
                    echo  Carbon::parse($created_at)->format('j M y, H:i');
                   })
                   ->html(),
            Column::make("Action")
                ->label(function($row) {
                        $update = '<button data-nav="'.$row->navigation_id.'" data-name="'.$row->name.'" data-id="'.$row->id.'" class="btn btn-xs btn-secondary" id="get_edit_permission"><i class="mdi mdi-pencil mdi-12px"></i></a></button>';  
                        $delete = '<button data-id="'.$row->id.'" class="btn btn-xs btn-danger" id="trash_permission"><i class="mdi mdi-trash-can mdi-12px"></i></a></button>';  
                       return $update.'&nbsp;&nbsp;'.$delete;                            
                })
                ->html(),
      
        ];
    }
}
