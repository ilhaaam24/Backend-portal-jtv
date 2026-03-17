<?php

namespace App\Http\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Pengguna;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;

class PenggunaTable extends DataTableComponent
{
    protected $model = Pengguna::class;

    public function builder(): Builder
    {
        return Pengguna::query()->oldest('users.is_active')->latest('users.id')
        ->when($this->columnSearch['nama_pengguna'] ?? null, fn ($query, $nama_pengguna) => $query->where('nama_pengguna', 'like', '%' . $nama_pengguna . '%'));
        
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id_pengguna')
        ->setEmptyMessage('No results found guys')
        ->setSortingStatus(true)
        ->setSortingEnabled()
        ->setSingleSortingEnabled()
        ->setDefaultSort('id_pengguna', 'asc')
        ->setSortingPillsStatus(true)
        ->setSortingPillsEnabled()
        ->setPerPageAccepted([10, 20, 25, 50, 100])
        ->setPerPage(20);
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id_pengguna")
                ->sortable()
                ->hideIf(true),
            Column::make("UserID", "user.id")
                ->searchable()
                ->sortable()
                ->hideIf(true),
            Column::make("Username", "username")
                ->searchable()
                ->sortable()
                ->hideIf(true),
            Column::make("Email", "user.email")
                ->searchable()
                ->sortable()
                ->hideIf(true),
            Column::make("IsAktif", "user.is_active")
                ->searchable()
                ->sortable()
                ->hideIf(true),
       
            Column::make("Foto Profile", "gambar_pengguna")
            ->format(function( $gambar_pengguna ) {
                $img = $gambar_pengguna;
                $filepath = public_path('assets/foto-profil/').$img;
                $path_img = config('jp.path_url_be').config('jp.path_img_profile').$img;
                $local_img = url('assets/foto-profil/'.$img);   

               
                if (file_exists($filepath) && $img!='') {
                    $filepath_img=  $local_img;
                }else{
                        $filepath_img= asset(config('jp.path_url_no_img'));
                }
                // $filepath_img =  $path_img;
                    return '<div class="avatar">
                    <img src="'.$filepath_img.'" width="50px" height="60" class="w-px-40 h-auto rounded-circle">
                  </div>';
                
              
            })
                ->html(),
            Column::make("Nama Pengguna", "nama_pengguna")
                ->searchable()
                ->sortable(),
          
          
            Column::make("Level", "user.modelHasRole.role.name")
                ->searchable()
                ->sortable()
                ->format(function( $level ) {
                  
                    if($level=='admin'){
                        return '<span class="badge bg-primary">Admin</span>';
                    }elseif($level=='editor'){
                        return '<span class="badge bg-info">Editor</span>';
                    }elseif($level=='author'){
                        return '<span class="badge bg-warning">Author</span>';
                    }else{
                        return '<span class="badge bg-warning">'.$level.'</span>';
                    }
                  
                })
                ->html(),
            Column::make("Email/User")
                ->label(
                    fn ($row, Column $column) => 
                    '<strong>'.$row->username.'</strong>'. 
                    '<br>'.$row['user.email']
                 )
                 ->html(),
            Column::make("Is Active")    
                ->sortable()            
                ->label(function($row) {
                    // return $row;
                // return $row->is_active;
                    // return '<div class="d-inline-flex"><span class="avatar avatar-md"> <span class="avatar-initial rounded-circle bg-label-success"><span class="mdi mdi-account-off"></span></span></span></div>';
                    if($row['user.is_active']=='aktif'){
                        return '<button id="updateIsActive" data-id="'.$row['user.id'].'" class="btn btn-xs btn-success"><i class="mdi mdi-check mdi-12px"></i></button>';
                    
                        // return '<div class="d-inline-flex"><span class="avatar" id="is_active_user" data-val="1" data-id="'.$row->id.'"><span class="avatar-initial rounded bg-label-success"><span class="mdi mdi-check mdi-18px"></span></span></span></div>';
                    }else{
                        return '<button id="updateIsActive" data-id="'.$row['user.id'].'" class="btn btn-xs btn-secondary"><i class="mdi mdi-minus mdi-12px"></i></button>';
                        // return '<div class="d-inline-flex"><span class="avatar"  id="is_active_user" data-val="0" data-id="'.$row->id.'"><span class="avatar-initial rounded bg-label-secondary"><span class="mdi mdi-minus mdi-18px"></span></span></span></div>';
                    }
                
                })
                ->html(),
     

               
        ];
    }

}
