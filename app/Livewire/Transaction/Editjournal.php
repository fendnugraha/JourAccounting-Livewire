<?php

namespace App\Livewire\Transaction;

use App\Models\Journal;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\ChartOfAccount;
use Illuminate\Support\Facades\Auth;

class Editjournal extends Component
{
    public $journalId;
    public $date_issued;
    public $debt_code;
    public $cred_code;
    public $amount;
    public $fee_amount;
    public $description;
    public $cashAccount;

    public function mount()
    {
        $this->cashAccount = Auth::user()->roles->warehouse->chart_of_account_id;
    }

    #[On('journal-selected')]
    public function loadJournal($id)
    {
        $this->journalId = $id;
        // $this->loadData();
        $journal = Journal::find($id);
        $this->date_issued = $journal->date_issued;
        $this->debt_code = $journal->debt_code;
        $this->cred_code = $journal->cred_code;
        $this->amount = $journal->amount;
        $this->fee_amount = $journal->fee_amount;
        $this->description = $journal->description;
    }


    public function updateJournal()
    {
        $journal = Journal::find($this->journalId);
        $journal->update([
            'date_issued' => $this->date_issued,
            'debt_code' => $this->debt_code,
            'cred_code' => $this->cred_code,
            'amount' => $this->amount,
            'fee_amount' => $this->fee_amount,
            'description' => $this->description,
        ]);
        $this->dispatch('journal-updated', $journal);
    }

    public function render()
    {
        return view('livewire.transaction.edit-journal', [
            "accounts" => ChartOfAccount::whereIn('account_id', [1, 2])->where('warehouse_id', Auth::user()->roles->warehouse_id)->get()
        ]);
    }
}
