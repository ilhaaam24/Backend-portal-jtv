<?php

namespace App\Http\Livewire;

use App\Models\Berita;
use App\Models\Pengguna;
use App\Models\TbBerita;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\TextFilter;
use Illuminate\Support\Str;

class BeritaTable extends DataTableComponent
{

    public $judul, $idberita, $konten, $seo;

    private function resetInputFields(){
        $this->judul = '';
        $this->idberita = '';
        $this->konten = '';
        $this->seo = '';
    }

    public function generateSlug()
    {
        $this->seo = Str::slug($this->judul);
    }

    public function edit($idberita)
    {
        $post = Berita::where('id_berita',$idberita)->firstOrFail();
        $this->idberita = $idberita;
        $this->judul = $post->judul_berita;
        $this->konten = $post->artikel_berita;
        $this->seo = $post->seo_berita;

        $this->emit('updateModal');
    }

    public function cancel()
    {
        $this->resetInputFields();
    }

    public bool $columnSelect = true;
    public string $tableName = 'tb_berita';
    public array $beritas = [];
    
    public $columnSearch = [
        'judul_berita' => null,
        'date_perubahan_berita' => null,
    ];

    public function configure(): void
    {
        $this->setPrimaryKey('id_berita')
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
        ->setFooterTdAttributes(function(Column $column, $rows) {
            if ($column->isField('is_berita_terbaru')) {
                return [
                    'width' => '10%',
                  ];
            }
            return ['default' => true];
        })
        ->setTdAttributes(function(Column $column) {
            if ($column->isField('hit')) {
              return [
              'class' => 'bg-red-500 text-center',
              ];
            }

            if ($column->isField('is_berita_terbaru1')) {
                return [
                  'class' => 'text-center',
                ];
              }              
            return [];
          })

        ->setUseHeaderAsFooterEnabled()
        ->setHideBulkActionsWhenEmptyEnabled()
        ->setSingleSortingEnabled()
        ;
    }

    public function columns(): array
    {
        return [
            Column::make("seo", "seo_berita")
                ->searchable()
                ->sortable()
                ->hideIf(true),
            Column::make("judul_berita", "judul_berita")
                ->searchable()
                ->sortable()
                ->hideIf(true),
            Column::make("Id", "id_berita")
                ->searchable()
                ->sortable()
                ->hideIf(true),
            Column::make("berita baru", "is_berita_terbaru")
                ->searchable()
                ->sortable()
                ->hideIf(true),
            Column::make("berita Tervaik", "is_berita_terbaik")
                ->searchable()
                ->sortable()
                ->hideIf(true),
            Column::make("date_publish", "date_publish_berita")
               ->hideIf(true),
            Column::make("id_pengguna", "id_pengguna")
               ->hideIf(true),
            Column::make("berita_terbaik", "id_pengguna")
               ->hideIf(true),
            Column::make("status_berita", "status_berita")
               ->hideIf(true),
            Column::make("Biro")
               ->label(
                function ($row)  { 
                     $id_pengguna = $row->id_pengguna;
                    return $pengguna = Pengguna::where('id_pengguna', $id_pengguna);
                                $getbiro = $pengguna->biro;
                    $nama_biro = '';
                    if($getbiro){
                        $nama_biro = $getbiro->nama_biro; 
                    }
                    return '<span class="badge bg-info">'.$nama_biro.'</span>';
                })
             ->html()
               ->hideIf(true),
           
            Column::make('Judul Berita', "judul_berita")
                ->searchable()
                ->label(
                    function ($row)  { 
                        if($row->status_berita=='Publish') {
                            $id_pengguna = $row->id_pengguna;
                            if($id_pengguna){
                                $pengguna = Pengguna::where('id_pengguna', $id_pengguna)->first();
                                $getbiro = $pengguna->biro;
                                $seo_biro = '';
                                if($getbiro != null){
                                $seo_biro = $getbiro->seo;
                                $nama_biro = $getbiro->nama_biro;
                                }
                            }
                        return  '<a href="https://www.portaljtv.com/news/'.$row->seo_berita.'" target="_blank">'.$row->judul_berita.'</a>' ;
                      }else{
                        return $row->judul_berita; 
                      }
                    })
                 ->html(),

            Column::make("Status")                
                ->searchable()
                ->sortable()
                ->label(function($row) {
                     $id_pengguna = $row->id_pengguna;
                    $pengguna = Pengguna::where('id_pengguna', $id_pengguna)->first();
                    $getbiro = $pengguna->biro;
                  $nama_biro = '';
                  if($getbiro){
                      $nama_biro = $getbiro->nama_biro; 
                  }
                    $bironama = '<span class="badge bg-secondary  ">'.$nama_biro.'</span>';

                    if($row->status_berita=='Publish'){
                        return '<span class="badge bg-success">Publish</span><br>'.$bironama;
                    }elseif($row->status_berita=='Schedule'){
                        return'<span class="badge bg-info">Schedule</span><br>'.$bironama;
                    }elseif($row->status_berita=='Draft'){
                        return'<span class="badge bg-secondary">Draft</span><br>'.$bironama;
                    }elseif($row->status_berita=='trash'){
                        return '<span class="badge bg-danger">Trash</span><br>'.$bironama;
                    }
                })
                ->html(),
                
            Column::make("Terbaru")                
            ->label(function($row) {
                if($row->is_berita_terbaru=='1'){
                    $terbaru = '<span class="badge bg-success" id="terbaru_berita" data-val="1" data-id="'.$row->id_berita.'"><span class="mdi mdi-check"></span></span>';
                }elseif($row->is_berita_terbaru=='0'){
                    $terbaru = '<span class="badge bg-secondary"  id="terbaru_berita" data-val="0" data-id="'.$row->id_berita.'"><span class="mdi mdi-minus"></span></span>';
                }
                return $terbaru;
            })
            ->html(),

            Column::make("Terbaik")                
            ->label(function($row) {
                // return Auth::user()->getRoleNames()[0];
                // return $cekdata = Auth::user()->getRoleNames()[0] == 'admin' ? true : false;
                if($row->is_berita_terbaik=='1'){
                    $terbaru = '<span class="badge bg-warning" id="terbaik_berita" data-val="1" data-id="'.$row->id_berita.'"><span class="mdi mdi-star"></span></span>';
                }elseif($row->is_berita_terbaik=='0'){
                    $terbaru = '<span class="badge bg-secondary"  id="terbaik_berita" data-val="0" data-id="'.$row->id_berita.'"><span class="mdi mdi-star"></span></span>';
                }
                return $terbaru;
            })
            ->html()
            ->hideIf(Auth::user()->getRoleNames()[0] != 'admin'),

            Column::make("Hit", "hit")                
                ->searchable()
                ->sortable(),
            Column::make("Publish", "date_publish_berita")
                ->sortable()
                ->label(function($row) {
                    $date_publish = '<span class="badge bg-secondary" ><span class="mdi mdi-minus"></span></span>';
                    if($row->date_publish_berita != "0000-00-00 00:00:00"){
                        $date_publish =   Carbon::parse($row->date_publish_berita)->format('j F y H:i:s');
                    }
                    return $date_publish;
                })
            
                ->html(),
            Column::make("Updated", "date_perubahan_berita")
                ->sortable()
                ->format(function( $date_perubahan_berita ) {
                 
                    echo  Carbon::parse($date_perubahan_berita)->format('j F y H:i:s');
                   })
                   ->html()
                   ->hideIf(true),
            Column::make("Action")
                   ->label(function($row) {
                    $edit = '';
                    $delete = '';
                    if(filled(auth()->user()->getPermissionsViaRoles()->where('name', 'update berita')))
                            {$edit = '<a href="'.route('berita.update','id='.$row->id_berita.'').'" target="_blank" class="btn btn-xs btn-secondary mb-1"><i class="mdi mdi-pencil mdi-12px"></i></a>';
                            }; 
                    
                    if(filled(auth()->user()->getPermissionsViaRoles()->where('name', 'delete berita')))
                            {$delete = '<button data-id="'.$row->id_berita.'" class="btn btn-xs btn-danger" id="trash_berita"><i class="mdi mdi-trash-can mdi-12px"></i></button>';  
                            }; 
                    
                    if($row->status_berita!='trash') {
                        return $edit.'  '.$delete;
                    }else{
                        return $edit;
                    }   
                               
                   })
                   ->html(),
        ];
    }

    public function filters(): array
    {
        return [
            TextFilter::make('Judul Berita')
                ->config([
                    'maxlength' => 10,
                    'placeholder' => 'Judul Berita',
                ])
                ->filter(function(Builder $builder, string $value) {
                    $builder->where('judul_berita', 'like', '%'.$value.'%');
                }),
        
            SelectFilter::make('Active')
                ->setFilterPillTitle('User Status')
                ->setFilterPillValues([
                    'Publish' => 'Publish',
                    'Terbaru' => 'Terbaru',
                    'Schedule' => 'Schedule',
                    'Draft' => 'Draft',
                    'Trash' => 'Trash',
                ])
                ->options([
                    '' => 'All',
                    'Terbaru' => 'Terbaru',
                    'Terbaik' => 'Terbaik',
                    'Publish' => 'Publish',
                    'Schedule' => 'Schedule',
                    'Draft' => 'Draft',
                    'Trash' => 'Trash',
                ])
                ->filter(function(Builder $builder, string $value) {
                    if ($value === 'Publish') {
                        $builder->where('status_berita', 'Publish');
                    } elseif ($value === 'Schedule') {
                        $builder->where('status_berita', 'Schedule');
                    } elseif ($value === 'Draft') {
                        $builder->where('status_berita', 'Draft');
                    }elseif ($value === 'Trash') {
                        $builder->where('status_berita', 'Trash');
                    }
                    elseif ($value === 'Terbaru') {
                        $builder->where('is_berita_terbaru', '1')->where('status_berita', 'Publish');
                    }
                    elseif ($value === 'Terbaik') {
                        $builder->where('is_berita_terbaik', '1')->where('status_berita', 'Publish');
                    }
                }),
           
        ];
    }

    public function builder(): Builder
    {
        $role_id = auth()->user()->roles[0]->id;
        $penggunas = auth()->user()->pengguna->biro->penggunaz->pluck('id_pengguna');
        return Berita::query()
        ->when($role_id, function ($query) use ($role_id,  $penggunas) {
            if($role_id == 4){
            return $query->whereIn('id_pengguna',  $penggunas);
            }
         })
        ->latest('is_berita_terbaru')
        ->latest('date_publish_berita')
        ->when($this->columnSearch['judul_berita'] ?? null, fn ($query, $judul_berita) => $query->where('judul_berita', 'like', '%' . $judul_berita . '%'));
    }

    public function bulkActions(): array
    {
        return [
            'activate' => 'Publish',
            'deactivate' => 'Draft',
            'trashed' => 'Trash',
        ];
    }

    public function activate()
    {
        TbBerita::whereIn('id_berita', $this->getSelected())->update(['status_berita' => 'Publish']);
        $this->clearSelected();
        $this->setRefreshTime(1000); 
        $this->setRefreshMethod('refresh');
    }

    public function deactivate()
    {
        TbBerita::whereIn('id_berita', $this->getSelected())->update(['status_berita' => 'Draft']);
        $this->clearSelected();
    }

    public function trashed()
    {
        TbBerita::whereIn('id_berita', $this->getSelected())->update(['status_berita' => 'trash']);
        $this->clearSelected();
    }

}
