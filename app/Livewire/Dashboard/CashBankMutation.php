<?php

namespace App\Livewire\Dashboard;

use App\Models\ChartOfAccount;
use Carbon\Carbon;
use App\Models\Journal;
use App\Models\Warehouse;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class CashBankMutation extends Component
{
    public $warehouse;
    public $endDate;
    public $chartOfAccounts = [];

    public function mount()
    {
        $this->warehouse = Auth::user()->roles->warehouse_id ?? null;
        $this->endDate = Carbon::now()->endOfDay()->format('Y-m-d');
        $this->refreshData();
    }

    public function refreshData($warehouse = null, $endDate = null)
    {
        $warehouse = $warehouse ?? $this->warehouse;
        $endDate = $endDate
            ? Carbon::parse($endDate)->endOfDay()
            : Carbon::parse($this->endDate)->endOfDay();

        $this->chartOfAccounts = Journal::balancesByWarehouse($warehouse, $endDate);
    }

    public function updatedWarehouse($value)
    {
        $this->dispatch('warehouse-changed', warehouse: $value); // kirim ke child
        $this->refreshData();
    }

    public function updatedEndDate()
    {
        $this->dispatch('end-date-changed', endDate: $this->endDate);
        $this->refreshData();
    }

    #[On('journal-created')]
    public function onJournalCreated()
    {
        $this->refreshData();
    }
    public function render()
    {
        return view('livewire.dashboard.cash-bank-mutation', [
            'accounts' => $this->chartOfAccounts['chartOfAccounts'] ?? [],
            'warehouses' => Warehouse::orderBy('name', 'asc')->get(),
            'debitMutasi' => $this->chartOfAccounts['debitMutasi'] ?? [],
            'creditMutasi' => $this->chartOfAccounts['creditMutasi'] ?? [],
            'remaining' => array_sum($this->chartOfAccounts['debitMutasi'] ?? [0])
                - array_sum($this->chartOfAccounts['creditMutasi'] ?? [0]),
        ]);
    }
}
