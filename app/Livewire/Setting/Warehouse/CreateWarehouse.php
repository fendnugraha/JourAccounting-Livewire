<?php

namespace App\Livewire\Setting\Warehouse;

use Livewire\Component;
use App\Models\Warehouse;
use Livewire\Attributes\On;
use App\Models\ChartOfAccount;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;

class CreateWarehouse extends Component
{
    public $code;
    public $name;
    public $address;
    public $cashAccount;

    public function save()
    {
        $this->validate([
            'code' => 'required|size:3|unique:warehouses,code',
            'name' => 'required|min:3|max:90',
            'address' => 'required|min:3|max:160',
            'cashAccount' => 'required',
        ]);

        try {
            DB::beginTransaction();
            // Create and save the warehouse
            $warehouse = Warehouse::create([
                'code' => strtoupper($this->code),
                'name' => strtoupper($this->name),
                'address' => $this->address,
                'chart_of_account_id' => $this->cashAccount
            ]);

            // Update the related ChartOfAccount with the warehouse ID
            ChartOfAccount::where('id', $this->cashAccount)->update(['warehouse_id' => $warehouse->id]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // Flash an error message
            session()->flash('error', 'Gagal menambahkan gudang. Silakan coba lagi.');
        }

        $this->dispatch('WarehouseCreated', $warehouse->id);

        // Flash success message
        session()->flash('success', 'Gudang berhasil ditambahkan');

        // Reset the form inputs
        $this->reset();
    }

    #[On('WarehouseCreated')]
    #[Layout('layouts.app')]
    public function render()
    {
        $cashAccounts = ChartOfAccount::where('account_id', 1)->where('warehouse_id', null)->get();

        return view('livewire.setting.warehouse.create-warehouse', [
            'title' => 'Warehouse',
            'cashAccounts' => $cashAccounts
        ]);
    }
}
