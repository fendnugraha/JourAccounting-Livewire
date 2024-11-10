<?php

namespace App\Livewire\Setting\Warehouse;

use Livewire\Component;
use App\Models\Warehouse;
use App\Models\ChartOfAccount;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;

class EditWarehouse extends Component
{
    public $warehouse_id;
    public $name;
    public $address;
    public $cashAccount;

    public function mount($warehouse_id)
    {
        $this->warehouse_id = $warehouse_id;
        $warehouse = Warehouse::find($warehouse_id);
        $this->name = $warehouse->name;
        $this->address = $warehouse->address;
        $this->cashAccount = $warehouse->chart_of_account_id;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|min:3|max:90|unique:warehouses,name,' . $this->warehouse_id,
            'address' => 'required|max:160|min:3',
            'cashAccount' => 'required'
        ]);

        try {
            DB::beginTransaction();
            // Create and save the warehouse
            $warehouse = Warehouse::find($this->warehouse_id);

            // ChartOfAccount::where('id', $warehouse->chart_of_account_id)->update(['warehouse_id' => null]);

            $warehouse->update([
                'name' => $this->name,
                'address' => $this->address,
                'chart_of_account_id' => $this->cashAccount
            ]);

            ChartOfAccount::where('id', $this->cashAccount)->update(['warehouse_id' => $warehouse->id]);

            DB::commit();

            return redirect('/setting/warehouse')->with('success', 'Gudang ' . $warehouse->name . ' Telah Diupdate');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect('/setting/warehouse')->with('error', $e->getMessage());
        }
    }
    #[Layout('layouts.app')]
    public function render()
    {
        $ChartOfAccount = ChartOfAccount::where('account_id', 1)->whereIn('warehouse_id', [0, $this->warehouse_id])->get();

        return view('livewire.setting.warehouse.edit-warehouse', [
            'title' => 'Warehouse',
            'warehouse' => Warehouse::find($this->warehouse_id),
            'ChartOfAccount' => $ChartOfAccount
        ]);
    }
}
