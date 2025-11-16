<?php

namespace App\Livewire\Finance;

use App\Models\Finance;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;

class FinanceTable extends Component
{
    public $finance_type = 'Payable';
    public $contact = 'All';

    #[Computed]
    public function finances()
    {
        $financeGroupByContactId = Finance::with('contact')->selectRaw('contact_id, SUM(bill_amount) as tagihan, SUM(payment_amount) as terbayar, SUM(bill_amount) - SUM(payment_amount) as sisa, finance_type')
            ->groupBy('contact_id', 'finance_type')->get();
        return $financeGroupByContactId;
    }

    public function changeContact($contact)
    {
        $this->dispatch('contact-changed', $contact);
        $this->contact = $contact;
    }

    #[On('finance-created')]
    public function render()
    {
        return view('livewire.finance.finance-table');
    }
}
