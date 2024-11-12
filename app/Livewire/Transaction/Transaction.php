<?php

namespace App\Livewire\Transaction;

use App\Models\Transaction as ModelsTransaction;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Transaction extends Component
{
    public $search;
    public $startDate;
    public $endDate;

    public function mount()
    {
        $this->startDate = date('Y-m-d H:i');
        $this->endDate = date('Y-m-d H:i');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $transactions = ModelsTransaction::selectRaw('invoice, warehouse_id, MAX(date_issued) as date_issued, SUM(cost * ABS(quantity)) as totalCost, SUM(price * ABS(quantity)) as totalPrice, max(transaction_type) as transaction_type, MAX(contact_id) as contact_id')
            ->whereBetween('date_issued', [Carbon::parse($this->startDate)->startOfMonth(), Carbon::parse($this->endDate)->endOfMonth()])
            ->where('invoice', 'like', '%' . $this->search . '%')
            ->groupBy('invoice', 'warehouse_id')
            ->orderBy('date_issued', 'desc')
            ->paginate(5);

        return view('livewire.transaction.transaction', [
            'title' => 'Transaction',
            'transactions' => $transactions
        ]);
    }
}
