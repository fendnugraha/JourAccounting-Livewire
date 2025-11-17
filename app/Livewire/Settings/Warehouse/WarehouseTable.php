<?php

namespace App\Livewire\Settings\Warehouse;

use App\Models\Journal;
use Livewire\Component;
use App\Models\Warehouse;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\ChartOfAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WarehouseTable extends Component
{
    use WithPagination;

    public string $search = '';

    public function destroy($id)
    {
        $warehouse = Warehouse::find($id);
        if ($warehouse->is_locked) {
            return;
        }

        $journalExists = Journal::where('warehouse_id', $warehouse->id)->exists();
        if ($journalExists || $warehouse->id == 1) {
            return;
        }

        DB::beginTransaction();
        try {
            // Delete the warehouse
            $warehouse->delete();

            // Update the related ChartOfAccount with the warehouse ID
            ChartOfAccount::where('id', $warehouse->chart_of_account_id)->update(['warehouse_id' => null]);

            DB::commit();

            $this->dispatch('warehouse-deleted', $id);

            session()->flash('success', 'Warehouse deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            // Flash an error message
            Log::error($e->getMessage());
            return;
        }
    }

    #[On(['warehouse-created', 'warehouse-updated', 'warehouse-deleted'])]
    public function render()
    {
        $warehouse = Warehouse::with('chartOfAccount')->when($this->search, fn($query) => $query->where('name', 'like', '%' . $this->search . '%'))->orderBy('name', 'asc')->paginate(10)->onEachSide(0);
        return view('livewire.settings.warehouse.warehouse-table', [
            'warehouses' => $warehouse
        ]);
    }
}
