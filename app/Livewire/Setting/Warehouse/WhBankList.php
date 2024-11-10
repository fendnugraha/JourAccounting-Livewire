<?php

namespace App\Livewire\Setting\Warehouse;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\ChartOfAccount;

class WhBankList extends Component
{
    use WithPagination;

    public $search;
    public $warehouse;
    public $account_id;
    public $warehouse_id;

    #[On('AccountCreated')]
    public function updateList($account_id)
    {
        $this->updateBankList($this->account_id, $this->warehouse_id);
    }

    public function updateBankList($account_id, $warehouse_id)
    {
        $checkAccount = ChartOfAccount::where('id', $account_id)->where('warehouse_id', $warehouse_id)->exists();

        if ($checkAccount) {
            ChartOfAccount::where('id', $account_id)->where('warehouse_id', $warehouse_id)->update([
                'warehouse_id' => null
            ]);
        } else {
            ChartOfAccount::where('id', $account_id)->update([
                'warehouse_id' => $warehouse_id
            ]);
        }
    }

    public function render()
    {
        $banks = ChartOfAccount::whereIn('account_id', [1, 2])
            ->where('acc_name', 'like', '%' . $this->search . '%')
            ->simplePaginate(5);

        return view('livewire.setting.warehouse.wh-bank-list', [
            'banks' => $banks

        ]);
    }
}
