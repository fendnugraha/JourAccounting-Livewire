<?php

namespace App\Livewire\Journal;

use App\Models\Journal;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\ChartOfAccount;
use Illuminate\Support\Facades\Auth;

class CreateDeposit extends Component
{
    public $date_issued;
    public $cred_code;
    public $debt_code;
    public $amount;
    public $description;

    protected $rules = [
        'cred_code' => 'required',
        'debt_code' => 'required',
        'amount' => 'required',
    ];

    #[On('TransferCreated')]
    public function mount()
    {
        $this->date_issued = date('Y-m-d H:i');
    }

    public function save()
    {
        $journal = new Journal();

        $this->validate([
            'date_issued' => 'required',
            'debt_code' => 'required',
            'amount' => 'required',
            // 'fee_amount' => 'required',
            // 'custName' => 'required|regex:/^[a-zA-Z0-9\s]+$/|min:3|max:255',
        ]);

        // $warehouse = Auth::user()->warehouse;
        // $account = $warehouse->ChartOfAccount->acc_code;
        $description = $this->description == '' ? 'Deposit Customer' : $this->description;

        $journal->invoice = $journal->invoice_journal();
        $journal->date_issued = $this->date_issued;
        $journal->debt_code = $this->debt_code;
        $journal->cred_code = "20100-002";
        $journal->amount = $this->amount;
        $journal->fee_amount = 0;
        $journal->trx_type = 'Mutasi Kas';
        $journal->description = $description;
        $journal->user_id = Auth::user()->id;
        $journal->warehouse_id = Auth::user()->role->warehouse_id;
        $journal->save();

        session()->flash('success', 'Journal created successfully');

        $this->dispatch('TransferCreated', $journal->id);

        $this->reset();
    }

    public function render()
    {
        return view('livewire.journal.create-deposit', [
            'credits' => ChartOfAccount::all(),
        ]);
    }
}
