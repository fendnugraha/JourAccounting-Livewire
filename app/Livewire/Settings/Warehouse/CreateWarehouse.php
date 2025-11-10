<?php

namespace App\Livewire\Settings\Warehouse;

use Livewire\Component;
use App\Models\Warehouse;
use App\Models\ChartOfAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;

class CreateWarehouse extends Component
{
    #[Validate('required|unique:warehouses,code|size:3')]
    public $prefix;

    #[Validate('required|unique:warehouses,name|max:30')]
    public $name;

    #[Validate('required')]
    public $address;

    #[Validate('required|exists:chart_of_accounts,id')]
    public $account_id;

    public function createWarehouse()
    {
        $this->validate();

        try {
            DB::beginTransaction();
            // Create and save the warehouse
            $warehouse = Warehouse::create([
                'code' => strtoupper($this->prefix),
                'name' => strtoupper($this->name),
                'address' => $this->address,
                'chart_of_account_id' => $this->account_id
            ]);

            // Update the related ChartOfAccount with the warehouse ID
            ChartOfAccount::where('id', $this->account_id)->update(['warehouse_id' => $warehouse->id]);

            $this->dispatch('warehouse-created', $warehouse->id);

            session()->flash('success', 'Cabang baru berhasil ditambahkan');

            $this->reset();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            // Flash an error message
            session()->flash('error', 'Gagal menambahkan gudang. Silakan coba lagi.');
        }
    }

    #[On('account-created')]
    public function render()
    {
        return view('livewire.settings.warehouse.create-warehouse', [
            'accounts' => ChartOfAccount::where('account_id', 1)->where('warehouse_id', null)->orderBy('acc_code', 'asc')->get()
        ]);
    }
}
