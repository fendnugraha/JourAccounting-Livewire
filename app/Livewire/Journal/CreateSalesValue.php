<?php

namespace App\Livewire\Journal;

use App\Models\Journal;
use Livewire\Component;
use App\Models\ChartOfAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CreateSalesValue extends Component
{
    public $date_issued;
    public $debt_code;
    public $cost;
    public $sales;
    public $description;

    public function save()
    {
        $journal = new Journal();

        $this->validate([
            'date_issued' => 'required',
            'debt_code' => 'required',
            'cost' => 'required',
            'sales' => 'required',
            // 'fee_amount' => 'required',
            // 'custName' => 'required|regex:/^[a-zA-Z0-9\s]+$/|min:3|max:255',
        ]);

        $lastInvoice = $journal->invoice_journal();

        try {
            DB::beginTransaction();

            $description = $this->description == '' ? 'Jurnal Umum' : $this->description;
            // Create and save the journal
            $journal->invoice = $lastInvoice;
            $journal->date_issued = $this->date_issued;
            $journal->debt_code = $this->debt_code;
            $journal->cred_code = "40100-001";
            $journal->amount = $this->sales;
            $journal->fee_amount = 0;
            $journal->trx_type = 'Mutasi Kas';
            $journal->description = $description;
            $journal->user_id = Auth::user()->id;
            $journal->warehouse_id = Auth::user()->role->warehouse_id;
            $journal->save();

            $journal->invoice = $lastInvoice;
            $journal->date_issued = $this->date_issued;
            $journal->debt_code = "50100-001";
            $journal->cred_code = "10600-001";
            $journal->amount = $this->cost;
            $journal->fee_amount = 0;
            $journal->trx_type = 'Mutasi Kas';
            $journal->description = $description;
            $journal->user_id = Auth::user()->id;
            $journal->warehouse_id = Auth::user()->role->warehouse_id;
            $journal->save();

            DB::commit();
            session()->flash('success', 'Journal created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Journal failed.');
        }

        $this->dispatch('TransferCreated', $journal->id);

        $this->reset();
    }
    public function render()
    {
        return view('livewire.journal.create-sales-value', [
            'account' => ChartOfAccount::whereIn('account_id', [1, 2, 19])->get()
        ]);
    }
}
