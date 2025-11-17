<?php

namespace App\Livewire\Settings\Warehouse;

use Livewire\Component;
use App\Models\Warehouse;
use App\Models\ChartOfAccount;
use Livewire\Attributes\Validate;

class EditWarehouse extends Component
{
    public Warehouse $warehouse;

    #[Validate('required|unique:warehouses,name|max:30')]
    public $name;

    #[Validate('required')]
    public $address;

    #[Validate('required|exists:chart_of_accounts,id')]
    public $account_id;

    public function mount(Warehouse $warehouse)
    {
        $this->warehouse = $warehouse;
        $this->name = $warehouse->name;
        $this->address = $warehouse->address;
        $this->account_id = $warehouse->chart_of_account_id;
    }

    public function editWarehouse()
    {
        $warehouse = Warehouse::find($this->warehouse->id);
        $warehouse->update([
            'name' => $this->name,
            'address' => $this->address,
            'chart_of_account_id' => $this->account_id
        ]);

        $this->dispatch('warehouse-updated', $warehouse->id);
    }

    public function render()
    {
        return view('livewire.settings.warehouse.edit-warehouse', [
            'accounts' => ChartOfAccount::where('account_id', 1)
                ->where(fn($query) => $query->where('warehouse_id', $this->warehouse->id)->orWhereNull('warehouse_id'))
                ->orderBy('acc_code', 'asc')
                ->get(),
        ]);
    }
}
