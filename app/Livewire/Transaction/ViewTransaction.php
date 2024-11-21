<?php

namespace App\Livewire\Transaction;

use App\Models\Journal;
use Livewire\Component;
use App\Models\Transaction;
use Livewire\Attributes\Layout;

class ViewTransaction extends Component
{
    public $invoice;

    public function mount()
    {
        $transaction = Transaction::where('invoice', $this->invoice)->get();
    }

    public function checkDiscountAndFee()
    {
        $journal = Journal::where('invoice', $this->invoice)
            ->where('debt_code', '60111-001')
            ->orWhere('invoice', $this->invoice)
            ->where('cred_code', '40200-001')->get();

        return $journal;
    }

    #[Layout('layouts.app')]
    public function render()
    {
        // dd($this->checkDiscountAndFee());
        return view('livewire.transaction.view-transaction', [
            'title' => 'View Transaction ' . $this->invoice,
            'transaction' => Transaction::where('invoice', $this->invoice)->get(),
            'discountAndFee' => $this->checkDiscountAndFee()->first()->amount ?? 0
        ]);
    }
}
