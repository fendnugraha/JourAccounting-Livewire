<?php

namespace App\Livewire\Settings\Warehouse;

use Livewire\Component;
use App\Models\Warehouse;
use App\Models\ChartOfAccount;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class WarehouseDetail extends Component
{
    use WithPagination;

    public Warehouse $warehouse;
    public $search = '';

    public function mount(Warehouse $warehouse)
    {
        $this->warehouse = $warehouse;
    }

    public function updateAccountList($warehouse, $account_id)
    {
        $chartOfAccount = ChartOfAccount::find($account_id);

        $updateValue = $chartOfAccount->warehouse_id ? null : $warehouse;
        $chartOfAccount->update(['warehouse_id' => $updateValue]);

        $this->dispatch('warehouse-updated', $warehouse);
    }

    #[On('warehouse-updated')]
    public function render()
    {
        $accounts = ChartOfAccount::when($this->search, fn($query) => $query->where('acc_name', 'like', '%' . $this->search . '%'))
            ->whereIn('account_id', [1, 2])
            ->where(fn($query) => $query->where('warehouse_id', $this->warehouse->id)->orWhereNull('warehouse_id'))
            ->orderBy('acc_code', 'asc')
            ->simplePaginate(5)->onEachSide(0);
        return view('livewire.settings.warehouse.warehouse-detail', [
            'accounts' => $accounts
        ]);
    }
}
