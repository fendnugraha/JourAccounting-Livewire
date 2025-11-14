<?php

namespace App\Livewire\Transaction;

use App\Models\Journal;
use Livewire\Component;
use App\Models\ChartOfAccount;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;

class CreateBankExpense extends Component
{
    #[Validate('required|date')]
    public $date_issued;

    #[Validate('required|exists:chart_of_accounts,id')]
    public $cred_code;

    #[Validate('required|numeric|min:0')]
    public $amount;

    #[Validate('max:255')]
    public $description;

    public function mount()
    {
        $this->date_issued = date('Y-m-d H:i');
    }

    public function createMutation()
    {
        $this->validate();

        $message = Journal::createMutation([
            'date_issued' => $this->date_issued ?? now(),
            'debt_code' => Auth::user()->roles->warehouse->chart_of_account_id,
            'cred_code' => $this->cred_code,
            'amount' => $this->amount,
            'trx_type' => 'Pengeluaran',
            'fee_amount' => -$this->amount,
            'admin_fee' => 0,
            'is_confirmed' => true,
            'description' => $this->description ?? 'Biaya Administrasi Bank',
        ]);

        $this->dispatch('journal-created', $message);

        $this->reset();
    }

    public function render()
    {
        return view('livewire.transaction.create-bank-expense', [
            'accounts' => ChartOfAccount::where('account_id', 2)->where('warehouse_id', Auth::user()->roles->warehouse_id)->orderBy('acc_code', 'asc')->get()
        ]);
    }
}
