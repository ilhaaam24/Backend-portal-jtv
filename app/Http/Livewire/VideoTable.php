<?php

namespace App\Http\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Columns\ImageColumn;

class VideoTable extends DataTableComponent
{
    protected $model = Video::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id_video')
        ->setThAttributes(function(Column $column) {
            if ($column->isField('thumbnail')) {
              return [
                'width' => '20%',
                'class' => 'd-flex align-items-center',
              ];
            }

            if ($column->isField('judul_video')) {
                return [
                  'width' => '40%',
                ];
              }

            if ($column->isField('date')) {
                return [
                  'width' => '20%',
                ];
              }

              if ($column->isField('id_video')) {
                return [
                  'width' => '15%',
                ];
              }
          
        
            return [];
          })
        ->setPerPageAccepted([10, 25, 50, 100])
        ->setPerPage(10);
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id_video")
                ->sortable()
                ->hideIf(true),
            Column::make("Id Youtube", "id_video_yt")
                ->sortable()
                ->hideIf(true),
            Column::make("Thumbnail", "thumbnail")
                ->sortable()
                ->hideIf(true),

            Column::make("Thumbnails")
                ->label(function($row, Column $column) {
                     return '<img src="'.$row->thumbnail.'" data-link="https://www.youtube.com/watch?v='.$row->id_video_yt.'" class="img_link rounded" width="120px" height="80px" class="rounded">';
                 })
                 ->html(),
            Column::make("Judul Video", "judul_video")
                ->searchable()
                ->sortable(),
            Column::make("Tgl Upload", "date")
                ->sortable()
                ->format(function( $date ) {
                 
                 return  Carbon::parse($date)->format('j F y H:i:s');
                })
                ->html(),
            Column::make("Action", "id_video")
                ->format(function( $id_video) {
                        $edit = '<a href="'.route('video.edit','id='.$id_video.'').'" class="btn btn-xs btn-secondary mb-1"><i class="mdi mdi-pencil mdi-12px"></i></a>';
                        $delete = '<button class="btn btn-xs btn-danger mb-1" data-id="'.$id_video.'" id="delete_video"><i class="mdi mdi-trash-can mdi-12px"></i></button>';  
                    return $edit.'  '.$delete;
                })
                ->html(),
           
        ];
    }

    public function builder(): Builder
    {
        return Video::query()
        ->where('status_video', 1)
        ->latest('date')
       
        ->when($this->columnSearch['judul_video'] ?? null, fn ($query, $judul_video) => $query->where('judul_video', 'like', '%' . $judul_video . '%'));
        
    }
}
