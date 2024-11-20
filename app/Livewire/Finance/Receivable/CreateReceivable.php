<?php

namespace App\Livewire\Finance\Receivable;

use Carbon\Carbon;
use App\Models\Contact;
use App\Models\Journal;
use App\Models\Receivable;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\ChartOfAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CreateReceivable extends Component
{
    public $date_issued;
    public $debt_code;
    public $cred_code;
    public $amount;
    public $description;
    public $contact;
    public int $dueDate = 30;

    public function mount()
    {
        $this->date_issued = date('Y-m-d H:i');
        $this->dueDate = 30;
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
        $invoice_number = Journal::receivable_invoice($this->contact);

        $this->validate([
            'date_issued' => 'required',
            'dueDate' => 'required|numeric',
            'debt_code' => 'required',
            'cred_code' => 'required',
            'amount' => 'required|numeric',
            'contact' => 'required'
        ], [
            'contact.required' => 'Contact harus diisi.',
            'cred_code.required' => 'Category Piutang harus diisi.',
            'debt_code.required' => 'Sumber Dana harus diisi.',
            'dueDate.required' => 'Jatuh Tempo harus diisi.',
            'dueDate.numeric' => 'Jatuh Tempo harus berupa angka.',
            'amount.required' => 'Jumlah Piutang harus diisi.',
        ]);

        try {
            $contact = Contact::findOrFail($this->contact);
        } catch (\Exception $e) {
            session()->flash('error', 'Contact Not Found');
            return redirect()->route('finance.receivable');
        }

        try {
            DB::beginTransaction();

            Receivable::create([
                'date_issued' => $dateIssued,
                'due_date' => $dateIssued->copy()->addDays($this->dueDate),
                'invoice' => $invoice_number,
                'description' => $this->description ?? 'Piutang Usaha',
                'bill_amount' => $this->amount,
                'payment_amount' => 0,
                'payment_status' => 0,
                'payment_nth' => 0,
                'contact_id' => $this->contact,
                'user_id' => $user->id,
                'account_code' => $this->debt_code
            ]);

            $journal = new Journal([
                'invoice' => $invoice_number,
                'date_issued' => $this->date_issued,
                'debt_code' => $this->debt_code,
                'cred_code' => $this->cred_code,
                'amount' => $this->amount,
                'fee_amount' => 0,
                'trx_type' => 'Receivable',
                'rcv_pay' => 'Receivable',
                'payment_status' => 0,
                'payment_nth' => 0,
                'description' => $this->description ?? 'Piutang Usaha',
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
        return view('livewire.finance.receivable.create-receivable', [
            'credits' => ChartOfAccount::whereIn('account_id', [1, 2])->get(),
            'contacts' => Contact::orderBy('name')->get(),
            'receivable_accounts' => ChartOfAccount::whereIn('account_id', [4, 5])->get(),
        ]);
    }
}
