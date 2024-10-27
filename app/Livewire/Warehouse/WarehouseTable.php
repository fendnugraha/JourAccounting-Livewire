<?php

namespace App\Livewire\Warehouse;

use App\Models\Journal;
use Livewire\Component;
use App\Models\Warehouse;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class WarehouseTable extends Component
{
    use WithPagination;

    public $search = '';

    public $warehouseId;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete($warehouse_id)
    {
        $warehouse = Warehouse::find($warehouse_id);

        $journalExists = Journal::where('warehouse_id', $warehouse_id)->exists();
        // dd($journalExists);
        if ($journalExists || $warehouse_id == 1) {
            session()->flash('error', 'Warehouse Cannot be Deleted!');
        } else {
            $warehouse->delete();
            session()->flash('success', 'Warehouse Deleted Successfully');
        }

        $this->dispatch('WarehouseDeleted', $warehouse->id);
    }

    #[On('WarehouseCreated', 'WarehouseDeleted')]
    public function updateStatus($warehouseId)
    {
        $warehouse = Warehouse::find($warehouseId);

        if ($warehouse) {
            $newStatus = !$warehouse->status;
            $warehouse->update(['status' => $newStatus]);

            // Optionally, you can update the local property if needed
            // $this->warehouses[$warehouseId]['status'] = $newStatus;

            // $this->emit('statusUpdated'); // Emit an event if you want to handle UI updates
        } else {
            throw new ModelNotFoundException("Warehouse not found.");
        }
    }

    #[On('WarehouseCreated', 'WarehouseDeleted')]
    public function render()
    {
        $warehouses = Warehouse::with('ChartOfAccount')
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('address', 'like', '%' . $this->search . '%')
            ->orWhere('code', 'like', '%' . $this->search . '%')
            ->paginate(10);
        return view(
            'livewire.warehouse.warehouse-table',
            [
                'warehouses' => $warehouses,
            ]
        );
    }
}
