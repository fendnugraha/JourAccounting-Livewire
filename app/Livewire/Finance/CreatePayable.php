<?php

namespace App\Livewire\Finance;

use Carbon\Carbon;
use App\Models\Finance;
use App\Models\Journal;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\ChartOfAccount;
use App\Models\Contact;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CreatePayable extends Component
{
    public $debt_code;
    public $cred_code;
    public $amount;
    public $date_issued;
    public $description;
    public $contact_id;

    protected $rules = [
        'debt_code' => 'required|exists:chart_of_accounts,id',
        'cred_code' => 'required|exists:chart_of_accounts,id',
        'amount' => 'required|numeric',
        'date_issued' => 'required',
        'description' => 'required|min:3',
        'contact_id' => 'required|exists:contacts,id',
    ];

    #[On('finance-created')]
    public function mount()
    {
        $this->date_issued = Carbon::now()->format('Y-m-d H:i');
    }

    public function createPayable()
    {
        $this->validate();

        $dateIssued = $this->date_issued ? Carbon::parse($this->date_issued) : Carbon::now();
        $invoice_number = Finance::invoice_finance($this->contact_id, 'Payable');
        DB::beginTransaction();
        try {
            Finance::create([
                'date_issued' => $dateIssued,
                'due_date' => $dateIssued->copy()->addDays(30),
                'invoice' => $invoice_number,
                'description' => $this->description,
                'bill_amount' => $this->amount,
                'payment_amount' => 0,
                'payment_status' => 0,
                'payment_nth' => 0,
                'finance_type' => 'Payable',
                'contact_id' => $this->contact_id,
                'user_id' => Auth::user()->id,
                'account_code' => $this->cred_code
            ]);

            Journal::create([
                'date_issued' => $dateIssued,
                'invoice' => $invoice_number,
                'description' => $this->description,
                'debt_code' => $this->debt_code,
                'cred_code' => $this->cred_code,
                'amount' => $this->amount,
                'fee_amount' => 0,
                'status' => 1,
                'rcv_pay' => 'Payable',
                'payment_status' => 0,
                'payment_nth' => 0,
                'user_id' => Auth::user()->id,
                'warehouse_id' => 1
            ]);

            DB::commit();

            $this->dispatch('finance-created', $invoice_number);
            $this->reset();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.finance.create-payable', [
            'accounts' => ChartOfAccount::whereIn('account_id', [1, 2])->orderBy('acc_code', 'asc')->get(),
            'payables' => ChartOfAccount::whereIn('account_id', range(19, 25))->orderBy('acc_code', 'asc')->get(),
            'contacts' => Contact::where('id', '!=', 1)->orderBy('name', 'asc')->get()
        ]);
    }
}
