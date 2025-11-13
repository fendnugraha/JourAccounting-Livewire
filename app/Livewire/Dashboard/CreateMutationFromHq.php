<?php

namespace App\Livewire\Dashboard;

use App\Models\Journal;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\ChartOfAccount;
use App\Models\Warehouse;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;

class CreateMutationFromHq extends Component
{
    public $warehouse;
    public $bank_fee = 0;

    #[Validate('required|date')]
    public $date_issued;

    #[Validate('required|exists:chart_of_accounts,id')]
    public $cred_code;

    #[Validate('required|exists:chart_of_accounts,id')]
    public $debt_code;

    #[Validate('required|numeric|min:0')]
    public $amount;

    #[Validate('numeric|min:0')]
    public $admin_fee = 0;

    public $description;

    #[On('journal-created')]
    public function mount()
    {
        $this->date_issued = date('Y-m-d H:i');
    }

    public function createMutation()
    {
        $this->validate();

        $message = Journal::createMutation([
            'date_issued' => $this->date_issued ?? now(),
            'debt_code' => $this->debt_code,
            'cred_code' => $this->cred_code,
            'amount' => $this->amount,
            'trx_type' => 'Mutasi Kas',
            'fee_amount' => 0,
            'admin_fee' => $this->admin_fee,
            'is_confirmed' => true,
            'description' => $this->description,
        ]);

        $this->dispatch('journal-created', $message);

        $this->reset();
    }

    public function render()
    {
        return view('livewire.dashboard.create-mutation-from-hq', [
            'accounts' => ChartOfAccount::whereIn('account_id', [1, 2])->orderBy('acc_code', 'asc')->get(),
            'hq_accounts' => ChartOfAccount::whereIn('account_id', [1, 2])->where('warehouse_id', 1)->orderBy('acc_code', 'asc')->get(),
            'warehouses' => Warehouse::orderBy('name', 'asc')->get(),
            'branch_accounts' => $this->warehouse
                ? ChartOfAccount::whereIn('account_id', [1, 2])
                ->where('warehouse_id', $this->warehouse)
                ->orderBy('acc_code', 'asc')
                ->get()
                : collect(),
        ]);
    }
}
