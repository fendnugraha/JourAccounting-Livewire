<?php

namespace App\Livewire\Transaction;

use Carbon\Carbon;
use App\Models\Journal;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;

class CashBankBalance extends Component
{
    public $end_date;
    public $chartOfAccounts = [];
    public $warehouse;

    public function mount()
    {
        $this->warehouse = Auth::user()->roles->warehouse_id;
        $this->end_date = date('Y-m-d H:i');

        $this->refreshData($this->warehouse, $this->end_date);
    }

    public function refreshData($warehouse = null, $endDate = null)
    {
        $warehouse = $warehouse ?? $this->warehouse;
        $endDate = $endDate
            ? Carbon::parse($endDate)->endOfDay()
            : Carbon::parse($this->end_date)->endOfDay();

        $this->chartOfAccounts = Journal::balancesByWarehouse($warehouse, $endDate);
    }

    #[On(['journal-created', 'journal-updated', 'journal-deleted'])]
    public function updatedEndDate()
    {
        $this->refreshData($this->warehouse, $this->end_date);
    }

    public function render()
    {
        return view('livewire.transaction.cash-bank-balance', [
            'accounts' => $this->chartOfAccounts['chartOfAccounts'],
            'total' => $this->chartOfAccounts['sumtotalBank'] + $this->chartOfAccounts['sumtotalCash'],
        ]);
    }
}
