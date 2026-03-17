<?php

namespace App\Http\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Sorot;
use Illuminate\Database\Eloquent\Builder;

class SorotTable extends DataTableComponent
{
    protected $model = Sorot::class;

    public function configure(): void
    {
        $this->setPrimaryKey('no_urut')
        ->setEmptyMessage('No results found guys')
        ->setSortingStatus(true)
        ->setSortingEnabled()
        ->setSingleSortingEnabled()
        ->setDefaultSort('no_urut', 'asc')
        ->setSortingPillsStatus(true)
        ->setSortingPillsEnabled()

        ->setPerPageAccepted([10, 20, 25, 50, 100])
        ->setPerPage(20);
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "no_urut")
                ->searchable()
                ->sortable() 
                ->hideIf(true),
            Column::make("tagging", "tag")
                ->searchable()
                ->sortable() 
                ->hideIf(true),
            Column::make("nama_tag", "tag.nama_tag")
                ->searchable()
                ->sortable() 
                ->hideIf(true),
            Column::make("Judul", "judul")
                ->searchable()
                ->sortable(),
            Column::make("photo", "photo")
                ->format(function( $photo ) {
                    $img = $photo;
                    $filepath = public_path('assets/sorot/').$img;
		            $imglocal = url('').'/assets/sorot/'.$img;

                    $filepath_img = config('jp.path_url_be').config('jp.path_img_sorot').$img; //path server
        
              /*   if (@fopen($img, "r")) {
                    $path_server =  $img;
                    // echo "File Exist";
                } else {
                           if (@fopen($filepath_img, "r")&& $img!='') {
                        $path_server = $filepath_img;
                    }else 

                    } */

                 if (file_exists($filepath) && $img!='') {
                        $path_server = $imglocal;

                    } else {
                        $path_server = 'https://www.portaljtv.com/images/broken.webp';       
                    }
                    return '<img src="'.$path_server.'" width="90px" height="60px" class="rounded"> ';

                
                })
                ->html(),
            Column::make("Tag" )
                ->searchable()
                ->sortable()
                ->label(
                    fn ($row) => 
                    '<a href="https://www.portaljtv.com/sorot/'.$row->tag.'" target="_blank">'.$row->tag.'</a>' 
                  )
                 ->html(),
            Column::make("Action", "no_urut")
                ->format(function( $no_urut) {
                    $edit = '<a href="'.route('konten.editsorot','id='.$no_urut.'').'" target="_blank" class="btn btn-xs btn-secondary mb-1"><i class="mdi mdi-pencil mdi-12px"></i></a>';
                  
                    $delete = '<button data-id="'.$no_urut.'" class="btn btn-xs btn-danger  mb-1" id="delete_sorot"><i class="mdi mdi-trash-can mdi-12px"></i></a>';  
                return $edit.'  '.$delete;
            })
            ->html(),
        
        ];
    }

    public function builder(): Builder
    {
        return Sorot::query()
        ->latest('no_urut')
        ->where('status','1');
        
    }
}
