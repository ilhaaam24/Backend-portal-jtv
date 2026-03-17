<?php

namespace App\Http\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Penulis;
use Illuminate\Database\Eloquent\Builder;

class PenulisTable extends DataTableComponent
{
    protected $model = Penulis::class;
    protected $index = 0;

    public function configure(): void
    {
        $this->setPrimaryKey('id_penulis')
        ->setEmptyMessage('No results found guys')
        ->setSortingStatus(true)
        ->setSortingEnabled()
        ->setSingleSortingEnabled()
        ->setDefaultSort('id_penulis', 'desc')
        ->setSortingPillsStatus(true)
        ->setSortingPillsEnabled()

        ->setPerPageAccepted([10, 20, 25, 50, 100])
        ->setPerPage(20);
    }

    public function columns(): array
    {
        return [
            Column::make("No", "id_penulis")->format(fn () => ++$this->index +  ($this->page - 1) * $this->perPage),
            Column::make("Id", "id_penulis")
                ->sortable()
                ->hideIf(true),
            Column::make("Nama Penulis", "nama_penulis")
                ->searchable()
                ->sortable(),
            Column::make("Username", "usernames")
                ->searchable()
                ->sortable(),
            Column::make("Email", "email_penulis")
                ->searchable()
                ->sortable(),
            Column::make("No Telp", "telp_penulis")
                ->searchable()
                ->sortable(),
        ];
    }

    public function builder(): Builder
    {
        return Penulis::query()
       
        ->when($this->columnSearch['nama_penulis'] ?? null, fn ($query, $nama_penulis) => $query->where('nama_penulis', 'like', '%' . $nama_penulis . '%'));
        
    }
}
