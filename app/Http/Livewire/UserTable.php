<?php

namespace App\Http\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\User;
use Carbon\Carbon;

use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class UserTable extends DataTableComponent
{
    protected $model = User::class;

    public function builder(): Builder
    {
        return User::query()->oldest('is_active')->latest('id');
 
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
        ->setEmptyMessage('No results found guys')
        ->setSortingStatus(true)
        ->setSortingEnabled()
        ->setSingleSortingEnabled()
        ->setSortingPillsStatus(true)
        ->setSortingPillsEnabled()
        ->setPerPageAccepted([10, 20, 25, 50, 100])
        ->setPerPage(20);
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable()
                ->searchable()
                ->hideIf(true),
            Column::make("is active", "is_active")
                ->sortable()
                ->hideIf(true),
            Column::make("Foto Profile", "pengguna.gambar_pengguna")
                ->format(function( $gambar_pengguna ) {
                    $img = $gambar_pengguna;
                    $filepath = public_path('assets/foto-profil/').$img;
                    $path_img = config('jp.path_url_be').config('jp.path_img_profile').$img;
                    $local_img = url('assets/foto-profil/'.$img);   

                   
                    if (file_exists($filepath) && $img!='') {
                        $filepath_img=  $local_img;
                    }else{
                            $filepath_img= 'https://www.portaljtv.com/images/broken.webp'; 
                    }
                    // $filepath_img =  $path_img;
                        return '<div class="avatar">
                        <img src="'.$filepath_img.'" width="50px" height="60" class="w-px-40 h-auto rounded-circle">
                      </div>';
                    
                  
                })
                ->html(),
            Column::make("Name", "name")
                ->sortable()
                ->searchable(),
          /*   Column::make("Roles", "modelHasRole.role.name")
                ->sortable()
                ->searchable(), */
            Column::make("Roles", "modelHasRole.role.name")
                ->sortable()
                ->searchable()
                ->format(function( $level ) {
                    if($level=='admin'){
                        return '<span class="badge bg-primary">Admin</span>';
                    }elseif($level=='editor'){
                        return '<span class="badge bg-info">Editor</span>';
                    }elseif($level=='author'){
                        return '<span class="badge bg-warning">Author</span>';
                    }else{
                        return '<span class="badge bg-info">'.$level.'</span>';
                    }
                  
                })
                ->html(),
            
            // Column::make("Pengguna", "pengguna.nama_pengguna")
            Column::make("Nama Pengguna", "pengguna.nama_pengguna")
                    ->searchable()
                    ->sortable()
                    ->hideIf(true),
            Column::make("Email/User")
                ->sortable()
                ->searchable()
                ->label(
                    fn ($row, Column $column) => 
                    // '<strong>'.$row->pengguna->nama_pengguna.'</strong>'. 
                    '<br>'.$row->email
                 )
                 ->html(),
            Column::make("Is Active")    
                ->sortable()            
                ->label(function($row) {
                // return $row->is_active;
                    // return '<div class="d-inline-flex"><span class="avatar avatar-md"> <span class="avatar-initial rounded-circle bg-label-success"><span class="mdi mdi-account-off"></span></span></span></div>';
                    if($row->is_active=='aktif'){
                        return '<button id="updateIsActive" data-id="'.$row->id.'" class="btn btn-xs btn-success"><i class="mdi mdi-check mdi-12px"></i></button>';
                    
                        // return '<div class="d-inline-flex"><span class="avatar" id="is_active_user" data-val="1" data-id="'.$row->id.'"><span class="avatar-initial rounded bg-label-success"><span class="mdi mdi-check mdi-18px"></span></span></span></div>';
                    }else{
                        return '<button id="updateIsActive" data-id="'.$row->id.'" class="btn btn-xs btn-secondary"><i class="mdi mdi-minus mdi-12px"></i></button>';
                        // return '<div class="d-inline-flex"><span class="avatar"  id="is_active_user" data-val="0" data-id="'.$row->id.'"><span class="avatar-initial rounded bg-label-secondary"><span class="mdi mdi-minus mdi-18px"></span></span></span></div>';
                    }
                
                })
                ->html(),

                Column::make("Action")    
                ->sortable()            
                ->label(function($row) {
                // return $row->is_active;
                    // return '<div class="d-inline-flex"><span class="avatar avatar-md"> <span class="avatar-initial rounded-circle bg-label-success"><span class="mdi mdi-account-off"></span></span></span></div>';
                    if($row->is_active=='aktif'){
                        return '<button id="editPengguna" data-id="'.$row->id.'" data-stat="'.$row->is_active.'" class="btn btn-xs btn-primary"><i class="mdi mdi-pencil mdi-12px"></i></button>';
                    
                    }else{
                        return '<button id="editPengguna" style="display:none!important;" data-id="'.$row->id.'" data-stat="'.$row->is_active.'" class="btn btn-xs btn-secondary"><i class="mdi mdi-close mdi-12px"></i></button>';
                    }
                
                })
                ->html(),
        
       
            Column::make("Email", "email")
                ->sortable()
                ->searchable()
                ->hideIf(true),

            Column::make("Created at", "created_at")
                ->sortable()
                ->format(function( $created_at ) {
                 
                    echo  Carbon::parse($created_at)->format('j F y H:i:s');
                   })
                   ->html()->hideIf(true),
            Column::make("Updated at", "updated_at")
                ->sortable()
                ->format(function( $updated_at ) {
                 
                    echo  Carbon::parse($updated_at)->format('j F y H:i:s');
                   })
                   ->html()->hideIf(true),
         
            Column::make("Permission")
                ->sortable()
                ->label(function($row) {
                    if($row->is_active=='aktif'){
                      $addrole =  '<a
                        href="javascript:;"
                        data-bs-toggle="modal"
                        data-bs-target="#addRoleModal"
                        data-id="'.$row->id.'"
                        class="btn btn-xs btn-info getdetailUser"
                        ><i class="mdi mdi-account-cog mdi-12px"></i></a>';
                    }else {
                
                        $addrole = '';
                    }
                        return$addrole;
                })
                ->html()->hideIf(true),
        ];
    }
}
