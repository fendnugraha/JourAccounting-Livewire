<?php

namespace App\Livewire\Finance;

use Carbon\Carbon;
use App\Models\Contact;
use App\Models\Finance;
use App\Models\Journal;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\ChartOfAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use function Pest\Laravel\session;

class CreatePayment extends Component
{
    public $contact;
    public $contactName;
    public $invoice;
    public $invoices = [];
    public $date_issued;
    public $account_id;
    public $notes;
    public $amount;

    protected $rules = [
        'invoice' => 'required|exists:finances,invoice',
        'date_issued' => 'required',
        'account_id' => 'required|exists:chart_of_accounts,id',
        'amount' => 'required|numeric|min:0',
        'notes' => 'nullable',
    ];

    #[On(['finance-created', 'finance-deleted'])]
    public function mount()
    {
        $this->date_issued = Carbon::now()->format('Y-m-d H:i');
    }

    #[On('contact-selected')]
    public function loadContact($contact)
    {
        $this->contact = $contact;
        $this->contactName = Contact::find($this->contact)->name ?? 'All';

        $finance = Finance::selectRaw('invoice, SUM(bill_amount) - SUM(payment_amount) as sisa')
            ->where('contact_id', $contact)
            ->groupBy('invoice')
            ->get();

        $this->invoices = $finance;

        return $finance;
    }

    public function getInvoiceValue(?string $invoice = null, ?int $contactId = null)
    {
        $query = Finance::selectRaw('SUM(bill_amount) - SUM(payment_amount) as sisa');

        if ($invoice) {
            // Hitung per invoice
            $query->where('invoice', $invoice)->groupBy('invoice');
        } elseif ($contactId) {
            // Hitung per contact
            $query->where('contact_id', $contactId)->groupBy('contact_id');
        } else {
            // Kalau tidak ada filter, mungkin return semua?
            return 0;
        }

        return $query->value('sisa') ?? 0;
    }

    public function getFinanceData($invoice)
    {
        $pay_nth = Finance::where('invoice', $invoice)->where('payment_nth', 0)->first();
        return $pay_nth;
    }

    public function createPayment()
    {
        $this->validate();

        $sisa = $this->getInvoiceValue(invoice: $this->invoice);
        if ($sisa <= 0) {
            return;
        }

        Log::info($sisa);

        $finance = $this->getFinanceData(invoice: $this->invoice);

        $payment_nth = Finance::selectRaw('MAX(payment_nth) as payment_nth')->where('invoice', $this->invoice)->first()->payment_nth + 1;
        $payment_status = $this->getInvoiceValue(invoice: $this->invoice) == 0 ? 1 : 0;

        DB::beginTransaction();
        try {
            Finance::create([
                'date_issued' => $this->date_issued ?? now(),
                'due_date' => $finance->due_date,
                'invoice' => $this->invoice,
                'description' => $this->notes ?? 'Pembayaran',
                'bill_amount' => 0,
                'payment_amount' => $this->amount,
                'payment_status' => $payment_status,
                'payment_nth' => $payment_nth,
                'finance_type' => $finance->finance_type,
                'contact_id' => $this->contact,
                'user_id' => Auth::user()->id,
                'account_code' => $this->account_id,
            ]);

            Journal::create([
                'date_issued' => $this->date_issued ?? now(),
                'invoice' => $this->invoice,
                'description' => $this->notes ?? 'Pembayaran',
                'debt_code' => $finance->finance_type == 'Receivable' ? $this->account_id : $finance->account_code,
                'cred_code' => $finance->finance_type == 'Receivable' ? $finance->account_code : $this->account_id,
                'amount' => $this->amount,
                'fee_amount' => 0,
                'status' => 1,
                'rcv_pay' => $this->getFinanceData(invoice: $this->invoice)->finance_type,
                'payment_status' => $payment_status,
                'payment_nth' => $payment_nth,
                'user_id' => Auth::user()->id,
                'warehouse_id' => 1
            ]);

            DB::commit();

            $this->dispatch('finance-created', $this->contact);
            $this->reset();
            $this->contact = null;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage());
        }
    }

    public function render()
    {
        // dd($this->invoices);
        return view('livewire.finance.create-payment', [
            'accounts' => ChartOfAccount::whereIn('account_id', [1, 2])->orderBy('acc_code', 'asc')->get(),
        ]);
    }
}
