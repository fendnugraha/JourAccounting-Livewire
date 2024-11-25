<?php

namespace App\Livewire\Finance\Receivable;

use Carbon\Carbon;
use App\Models\Contact;
use App\Models\Journal;
use Livewire\Component;
use App\Models\Receivable;
use Livewire\Attributes\On;
use App\Models\ChartOfAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CreateReceivablePayment extends Component
{
    public $date_issued;
    public $invoice;
    public $debt_code;
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
        $this->validate([
            'date_issued' => 'required',
            'invoice' => 'required',
            'debt_code' => 'required',
            'amount' => 'required|numeric',
            'contact' => 'required'
        ]);

        $user = Auth::user();
        $dateIssued = Carbon::parse($this->date_issued);
        $receivable = Receivable::with('journals')->where('invoice', $this->invoice)->where('contact_id', $this->contact)->where('payment_nth', 0)->first();
        $trx_type = $receivable->journals->first()->trx_type;

        $sisa = Receivable::selectRaw('sum(bill_amount - payment_amount) as total')
            ->where('invoice', $this->invoice)
            ->where('contact_id', $this->contact)
            ->value('total');

        if ($sisa < $this->amount) {
            session()->flash('error', 'Jumlah Pembayaran Tidak Sesuai');
            return redirect()->route('finance.receivable');
        }

        $cred_code = $receivable->account_code;
        $payment_nth = Receivable::getLastPayment($this->invoice) + 1;
        if ($sisa == $this->amount) {
            $payment_status = 1;
        } else {
            $payment_status = 0;
        }

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
                'due_date' => $receivable->due_date,
                'invoice' => $this->invoice,
                'description' => $this->description ?? 'Pembayaran piutang usaha',
                'bill_amount' => 0,
                'payment_amount' => $this->amount,
                'payment_status' => $payment_status,
                'payment_nth' => $payment_nth,
                'contact_id' => $this->contact,
                'user_id' => $user->id,
                'account_code' => $this->debt_code
            ]);

            $journal = new Journal([
                'invoice' => $this->invoice,
                'date_issued' => $this->date_issued,
                'debt_code' => $this->debt_code,
                'cred_code' => $cred_code,
                'amount' => $this->amount,
                'fee_amount' => 0,
                'trx_type' => $trx_type,
                'rcv_pay' => 'Receivable',
                'payment_status' => $payment_status,
                'payment_nth' => $payment_nth,
                'description' => $this->description ?? 'Pembayaran piutang usaha',
                'user_id' => $user->id,
                'warehouse_id' => 1
            ]);

            $journal->save();

            if ($sisa == $this->amount) {
                Receivable::where('invoice', $this->invoice)->where('contact_id', $this->contact)->update([
                    'payment_status' => 1,
                ]);
            }

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
        $receivables = Receivable::selectRaw('sum(bill_amount-payment_amount) as total, invoice, contact_id')
            ->groupBy('invoice', 'contact_id')
            ->having('total', '>', 0)
            ->get();

        $contacts = Contact::whereHas('receivables', function ($query) {
            $query->where('payment_status', 0);
        })->with('receivables')->get();

        return view('livewire.finance.receivable.create-receivable-payment', [
            'credits' => ChartOfAccount::whereIn('account_id', [1, 2])->get(),
            'contacts' => $contacts,
            'receivables' => $receivables,
        ]);
    }
}
