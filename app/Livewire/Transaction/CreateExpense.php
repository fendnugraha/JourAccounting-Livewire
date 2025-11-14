<?php

namespace App\Livewire\Transaction;

use App\Models\Journal;
use Livewire\Component;
use App\Models\ChartOfAccount;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;

class CreateExpense extends Component
{
    #[Validate('required|date')]
    public $date_issued;

    #[Validate('required|exists:chart_of_accounts,id')]
    public $debt_code;

    #[Validate('required|numeric|min:0')]
    public $amount;

    #[Validate('required|string|max:255')]
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
            'debt_code' => $this->debt_code,
            'cred_code' => Auth::user()->roles->warehouse->chart_of_account_id,
            'amount' => 0,
            'trx_type' => 'Pengeluaran',
            'fee_amount' => -$this->amount,
            'admin_fee' => 0,
            'is_confirmed' => true,
            'description' => $this->description,
        ]);

        $this->dispatch('journal-created', $message);

        $this->reset();
    }

    public function render()
    {
        return view('livewire.transaction.create-expense', [
            'accounts' => ChartOfAccount::whereIn('account_id', range(33, 45))->orderBy('acc_code', 'asc')->get()
        ]);
    }
}
