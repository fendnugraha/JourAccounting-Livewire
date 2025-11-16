<?php

namespace App\Livewire\Finance;

use App\Models\Contact;
use App\Models\Finance;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;

class FinanceDetail extends Component
{
    public $contact;
    public $finance_type;
    public $contactName;

    public function mount()
    {
        $this->contactName = Contact::find($this->contact)->name ?? 'All';
    }

    #[On('contact-changed')]
    public function updatedContact($contact)
    {
        $this->contact = $contact;
        $this->contactName = Contact::find($this->contact)->name ?? 'All';
    }

    #[Computed]
    public function finances()
    {
        $finance = Finance::with(['contact', 'account'])
            ->where(fn($query) => $this->contact == "All" ?
                $query : $query->where('contact_id', $this->contact))
            ->where('finance_type', $this->finance_type)
            ->latest('created_at')
            ->paginate(10)
            ->onEachSide(0);

        return $finance;
    }

    public function render()
    {
        return view('livewire.finance.finance-detail');
    }
}
