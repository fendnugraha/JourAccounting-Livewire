<?php

namespace App\Livewire\Transaction;

use App\Models\Journal;
use Livewire\Component;
use App\Models\Transaction;
use Livewire\Attributes\Layout;

class ViewTransaction extends Component
{
    public $serial;
    public $invoice;

    public function mount()
    {
        $transaction = Transaction::where('serial_number', $this->serial)->get();

        $this->invoice = $transaction->first()->invoice ?? null;
    }

    public function checkDiscountAndFee()
    {
        $journal = Journal::where('serial_number', $this->serial)
            ->where('debt_code', '60111-001')
            ->orWhere('serial_number', $this->serial)
            ->where('cred_code', '40200-001')->get();

        return $journal;
    }

    #[Layout('layouts.app')]
    public function render()
    {
        // dd($this->checkDiscountAndFee());
        return view('livewire.transaction.view-transaction', [
            'title' => 'View Transaction ' . $this->invoice,
            'transaction' => Transaction::where('serial_number', $this->serial)->get(),
            'discountAndFee' => $this->checkDiscountAndFee()->first()->amount ?? 0
        ]);
    }
}
