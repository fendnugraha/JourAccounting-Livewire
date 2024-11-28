<?php

namespace App\Livewire\Transaction;

use App\Models\Journal;
use Livewire\Component;
use App\Models\Transaction;
use Carbon\Carbon;
use Livewire\Attributes\Layout;

class ViewTransaction extends Component
{
    public $serial;
    public $invoice;

    public function mount()
    {
        if ($this->serial == null) {
            return abort(404);
        }

        $transaction = Transaction::where('serial_number', $this->serial)->get();
        if ($transaction->isEmpty()) {
            return abort(404);
        }
        $this->invoice = $transaction->first()->invoice ?? null;
    }

    public function checkDiscountAndFee()
    {
        $discount = Journal::where('serial_number', $this->serial)
            ->where(function ($query) {
                $query->where('debt_code', '60111-001')
                    ->orWhere('cred_code', '40200-001');
            })
            ->first()->amount ?? 0;

        $serviceFee = Journal::where('serial_number', $this->serial)
            ->where('cred_code', '40100-002')
            ->first()->amount ?? 0;

        return compact('discount', 'serviceFee');
    }


    #[Layout('layouts.app')]
    public function render()
    {
        // dd($this->checkDiscountAndFee());
        $transaction = Transaction::with(['payable', 'receivable'])->where('serial_number', $this->serial)->get();
        if ($transaction->isEmpty()) {
            return abort(404);
        }

        if ($transaction->first()->receivable || $transaction->first()->payable) {

            if ($transaction->first()->transaction_type == 'Sales') {
                $dueDate = Carbon::parse($transaction->first()->receivable->due_date)->format('F d, Y');
            } elseif ($transaction->first()->transaction_type == 'Purchase') {
                $dueDate = Carbon::parse($transaction->first()->payable->due_date)->format('F d, Y');
            } else {
                $dueDate = null;
            }
        }

        if ($transaction->first()->transaction_type == 'Sales') {
            $payment_method = $transaction->first()->journal->debt->acc_name;
        } elseif ($transaction->first()->transaction_type == 'Purchase') {
            $payment_method = $transaction->first()->journal->cred->acc_name;
        } else {
            $payment_method = null;
        }

        return view('livewire.transaction.view-transaction', [
            'title' => 'View Transaction ' . $this->invoice,
            'transaction' => $transaction,
            'discount' => $this->checkDiscountAndFee()['discount'] ?? 0,
            'serviceFee' => $this->checkDiscountAndFee()['serviceFee'] ?? 0,
            'date_issued' => Carbon::parse($transaction->first()->date_issued)->format('F d, Y'),
            'due_date' => $dueDate ?? 'Full Payment',
            'payment_method' => $payment_method
        ]);
    }
}
