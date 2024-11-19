<?php

namespace App\Livewire\Finance\Payable;

use Carbon\Carbon;
use App\Models\Contact;
use App\Models\Journal;
use App\Models\Payable;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\ChartOfAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CreatePayable extends Component
{
    public $date_issued;
    public $debt_code;
    public $cred_code;
    public $amount;
    public $description;
    public $contact;

    public function mount()
    {
        $this->date_issued = date('Y-m-d H:i');
    }

    #[On('JournalCreated')]
    public function resetDateIssued()
    {
        $this->date_issued = date('Y-m-d H:i');
    }

    public function save()
    {
        $user = Auth::user();
        $dateIssued = Carbon::parse($this->date_issued);
        $invoice_number = Journal::payable_invoice($this->contact);

        $this->validate([
            'date_issued' => 'required',
            'debt_code' => 'required',
            'cred_code' => 'required',
            'amount' => 'required|numeric',
            'contact' => 'required'
        ]);

        try {
            $contact = Contact::findOrFail($this->contact);
        } catch (\Exception $e) {
            session()->flash('error', 'Contact Not Found');
            return redirect()->route('finance.payable');
        }

        try {
            DB::beginTransaction();

            Payable::create([
                'date_issued' => $dateIssued,
                'due_date' => $dateIssued->copy()->addDays(30),
                'invoice' => $invoice_number,
                'description' => $this->description ?? 'Hutang Usaha',
                'bill_amount' => $this->amount,
                'payment_amount' => 0,
                'payment_status' => 0,
                'payment_nth' => 0,
                'contact_id' => $this->contact,
                'user_id' => $user->id,
                'account_code' => $this->cred_code
            ]);

            $journal = new Journal([
                'invoice' => $invoice_number,
                'date_issued' => $this->date_issued,
                'debt_code' => $this->debt_code,
                'cred_code' => $this->cred_code,
                'amount' => $this->amount,
                'fee_amount' => 0,
                'trx_type' => 'Payable',
                'rcv_pay' => 'Payable',
                'payment_status' => 0,
                'payment_nth' => 0,
                'description' => $this->description ?? 'Hutang Usaha',
                'user_id' => $user->id,
                'warehouse_id' => 1
            ]);

            $journal->save();

            DB::commit();

            session()->flash('success', 'Journal Created Successfully');

            $this->dispatch('JournalCreated', $journal);

            $this->reset();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', $e->getMessage());
        }
    }

    #[On('ContactCreated')]
    public function render()
    {
        return view('livewire.finance.payable.create-payable', [
            'credits' => ChartOfAccount::whereIn('account_id', [1, 2])->get(),
            'contacts' => Contact::orderBy('name')->get(),
            'payable_accounts' => ChartOfAccount::whereIn('account_id', [19, 25])->get(),
        ]);
    }
}
