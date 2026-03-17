<?php

namespace App\Http\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Instagram;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
class InstagramTable extends DataTableComponent
{
    protected $model = Instagram::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id_instagram_api')
        ->setThAttributes(function(Column $column) {
            if ($column->isField('token_api')) {
              return [
                'width' => '60%',
              ];
            }

            if ($column->isField('tgl_daftar_api')) {
                return [
                  'width' => '15%',
                ];
              }

              if ($column->isField('tgl_expired_api')) {
                return [
                  'width' => '15%',
                ];
              }
            return [];
          });
    }

    public function columns(): array
    {
        return [
            Column::make("No", "id_instagram_api")
                ->searchable()
                ->hideIf(true),
            Column::make("Token", "token_api")
                ->searchable(),
            Column::make("Tgl Daftar", "tgl_daftar_api")
                ->format(function( $tgl_daftar_api ) {
                 
                    echo  Carbon::parse($tgl_daftar_api)->format('j F y');
                   })
                   ->html(),
            Column::make("Tgl Expired", "tgl_expired_api")
                ->format(function( $tgl_expired_api ) {
                 
                    echo  Carbon::parse($tgl_expired_api)->format('j F y');
                   })
                   ->html(),
        ];
    }

    public function builder(): Builder
    {
        return  Instagram::query()
                    ->oldest('id_instagram_api');
    }
}
