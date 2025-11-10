<?php

namespace App\Livewire\Settings\Warehouse;

use Livewire\Component;
use App\Models\Warehouse;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class WarehouseTable extends Component
{
    use WithPagination;

    public string $search = '';

    #[On('warehouse-created')]
    public function render()
    {
        $warehouse = Warehouse::with('chartOfAccount')->when($this->search, fn($query) => $query->where('name', 'like', '%' . $this->search . '%'))->orderBy('name', 'asc')->paginate(10)->onEachSide(0);
        return view('livewire.settings.warehouse.warehouse-table', [
            'warehouses' => $warehouse
        ]);
    }
}
