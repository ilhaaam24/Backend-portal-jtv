<?php

namespace App\Http\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Footer;
use Illuminate\Database\Eloquent\Builder;

class FooterTable extends DataTableComponent
{
    protected $model = Footer::class;

    public function builder(): Builder
    {
        return Footer::query();
                 
    }


    public function configure(): void
    {
        $this->setPrimaryKey('id_footer');
    }

    public function columns(): array
    {
        return [
            Column::make("Id footer", "id_footer")
                ->sortable()
                ->hideIf(true),
            Column::make("Facebook", "facebook")
                ->sortable(),
            Column::make("Instagram", "instagram")
                ->sortable(),
            Column::make("Youtube", "youtube")
                ->sortable(),
            Column::make("Twitter", "twitter")
                ->sortable(),
            Column::make("Telegram", "telegram")
                ->sortable(),
            Column::make("LinkedIn", "linkedin")
                ->sortable(),
            Column::make("Tiktok", "tiktok")
                ->sortable(),
            Column::make("Copyright", "copyright")
                ->sortable(),
        ];
    }
}
