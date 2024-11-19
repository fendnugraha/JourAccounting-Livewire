<?php

namespace App\Livewire\Finance\Payable;

use Livewire\Component;

use Carbon\Carbon;
use App\Models\Contact;
use App\Models\Journal;
use App\Models\Payable;
use Livewire\Attributes\On;
use App\Models\ChartOfAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EditPayable extends Component
{
    public $date_issued;
    public $debt_code;
    public $cred_code;
    public $amount;
    public $description;
    public $contact;
    public $payable_id;

    public function mount()
    {
        $payable = Payable::with('journal')->find($this->payable_id);
        $this->date_issued = $payable->date_issued;
        $this->debt_code = $payable->journal->debt_code;
        $this->cred_code = $payable->journal->cred_code;
        $this->amount = $payable->bill_amount;
        $this->description = $payable->description;
        $this->contact = $payable->contact_id;
    }

    #[On('JournalCreated')]
    public function resetDateIssued()
    {
        $this->date_issued = date('Y-m-d H:i');
    }

    public function render()
    {
        return view('livewire.finance.payable.edit-payable', [
            'credits' => ChartOfAccount::whereIn('account_id', [1, 2])->get(),
            'contacts' => Contact::orderBy('name')->get(),
            'payable_accounts' => ChartOfAccount::whereIn('account_id', [19, 25])->get(),
        ]);
    }
}
