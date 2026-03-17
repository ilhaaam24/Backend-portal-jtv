<?php

namespace App\Http\Livewire;

use App\Http\Resources\PenulisResource;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Opini;
use App\Models\TbOpini;
use Carbon\Carbon;
use Rappasoft\LaravelLivewireTables\Views\Columns\ImageColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\MultiSelectDropdownFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\MultiSelectFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\NumberFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateTimeFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\TextFilter;
use Illuminate\Database\Eloquent\Builder;

class OpiniWargaTable extends DataTableComponent
{
    // protected $model = Opini::class;

    // To show/hide the modal
    public bool $viewingModal = false;

    // The information currently being displayed in the modal
    public $currentModal;

    public function builder(): Builder
    {
        return  Opini::query()
                    ->latest('id_opini')
                    ->latest('date_publish_opini')
                    ->latest('date_input_opini')
                    ->select('v_opini.*');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id_opini')
        ->setSecondaryHeaderEnabled()
        ->setSecondaryHeaderStatus(true)
        ->setRememberColumnSelectionDisabled()

        ->setHideReorderColumnUnlessReorderingEnabled()
        ->setFilterLayoutSlideDown()
        ->setRememberColumnSelectionDisabled()
        ->setBulkActionsEnabled()
        ->setSelectAllDisabled()
        ->setPerPageAccepted([10, 25, 50, 100])
        ->setPerPage(50)
        ->setSecondaryHeaderTrAttributes(function($rows) {
            return ['class' => 'bg-gray-100'];
        })
        ->setSecondaryHeaderTdAttributes(function(Column $column, $rows) {
            if ($column->isField('id_berita')) {
                return ['class' => 'text-red-500'];
            }

            return ['default' => true];
        })

        ->setFooterTrAttributes(function($rows) {
            return ['class' => 'bg-gray-100'];
        })
        ->setFooterTdAttributes(function(Column $column, $rows) {
            if ($column->isField('judul_berita')) {
                return ['class' => 'text-green-500'];
            }

            return ['default' => true];
        })

        ->setUseHeaderAsFooterEnabled()
        ->setHideBulkActionsWhenEmptyEnabled()

        ->setThAttributes(function(Column $column) {
            if ($column->getTitle() === 'Status') {
                return [
                  'width' => '10%',
                ];
              }

         
            if ($column->getTitle() === 'Created at') {
                return [
                  'width' => '15%',
                ];
              }

            if ($column->getTitle() === 'Publish at') {
                return [
                  'width' => '15%',
                ];
              }
            
            if ($column->getTitle() === 'Actions') {
                return [
                  'width' => '14%',
                ];
              }
            return [];
          })
        ;
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id_opini")
                ->searchable()
                ->sortable()
                ->hideIf(true),
            Column::make('Judul Opini', 'judul_opini')
                ->searchable()
                ->hideIf(true),
                Column::make("Status", "status_opini")
                ->searchable()
                ->sortable()
                ->hideIf(true),

            Column::make('Title')
                ->searchable()
                ->label(
                    function ($row)  { 
                        if($row->status_opini=='Publish') {
                        return  '<a href="https://www.portaljtv.com/baca/'.$row->seo_opini.'" target="_blank">'.$row->judul_opini.'</a>' ;
                      }else{
                        return $row->judul_opini;
                      }
                    })
                 ->html(),
            
            Column::make("Category", "tipetulisan.judul")                
                ->searchable()
                ->sortable()
                ->format(function( $status_opini ) {
                    return $status_opini;
                    
                })
                ->html(),

            Column::make("Status", "status_opini")                
                ->searchable()
                ->sortable()
                ->format(function( $status_opini ) {
                    if($status_opini=='Publish'){
                        return '<span class="badge bg-success">Publish</span>';
                    }elseif($status_opini=='Schedule'){
                        return '<span class="badge bg-info">Schedule</span>';
                    }elseif($status_opini=='Draft'){
                        return '<span class="badge bg-secondary">Draft</span>';
                    }elseif($status_opini=='trash'){
                        return '<span class="badge bg-danger">Trash</span>';
                    }
                  
                })
                ->html(),

            Column::make("Created at", "date_input_opini")
                ->sortable()
                ->format(function( $date_input_opini ) {
                 
                    echo  Carbon::parse($date_input_opini)->format('j F y H:i:s');
                   })
                   ->html(),
            Column::make("Publish at", "date_publish_opini")
                ->sortable()
                ->format(function( $date_publish_opini ) {
                 
                    echo  Carbon::parse($date_publish_opini)->format('j F y H:i:s');
                   })
                   ->html(),
            Column::make("Actions", "id_opini")
                ->format(function( $id_opini) {
                        $edit = '<a href="'.route('edit.jurnalismewarga','id='.$id_opini.'').'" target="_blank" class="btn btn-xs btn-secondary"><i class="mdi mdi-pencil mdi-12px"></i></a>';
                        $delete = '<a href="'.route('destroy.jurnalismewarga','id='.$id_opini.'').'" class="btn btn-xs btn-danger"><i class="mdi mdi-trash-can mdi-12px"></i></a>';  
                    return $edit.'  '.$delete;
                })
                ->html(),
        ];
    }

    public function bulkActions(): array
    {
        return [
            'activate' => 'Publish',
            'deactivate' => 'Draft',
        ];
    }

    public function filters(): array
    {
        return [
            TextFilter::make('Judul Opini')
                ->config([
                    'maxlength' => 10,
                    'placeholder' => 'Judul Opini',
                ])
                ->filter(function(Builder $builder, string $value) {
                    $builder->where('judul_opini', 'like', '%'.$value.'%');
                }),
            
        
            SelectFilter::make('Status')
                ->setFilterPillTitle('Opini Status')
                ->setFilterPillValues([
                    'Publish' => 'Publish',
                    'Draft' => 'Draft',
                ])
                ->options([
                    '' => 'All',
                    'Publish' => 'Publish',
                    'Draft' => 'Draft',
                ])
                ->filter(function(Builder $builder, string $value) {
                    if ($value === 'Publish') {
                        $builder->where('status_opini', 'Publish');
                    } elseif ($value === 'Draft') {
                        $builder->where('status_opini', 'Draft');
                    }
                }),
           
        ];
    }

    public function activate()
    {
        TbOpini::whereIn('id_opini', $this->getSelected())->update(['status_opini' => 'Publish']);

        $this->clearSelected();
    }

    public function deactivate()
    {
        TbOpini::whereIn('id_opini', $this->getSelected())->update(['status_opini' => 'Draft']);

        $this->clearSelected();
    }



}
