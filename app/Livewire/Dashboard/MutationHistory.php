<?php

namespace App\Livewire\Dashboard;

use Carbon\Carbon;
use App\Models\Journal;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\ChartOfAccount;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Log;

class MutationHistory extends Component
{
    use WithPagination;

    public $warehouse;
    public $endDate;
    public $perPage = 5;
    public $search = '';

    #[On('warehouse-changed')]
    public function updateWarehouse($warehouse)
    {
        $this->warehouse = $warehouse;
        $this->resetPage();
    }

    #[On('end-date-changed')]
    public function updateEndDate($endDate)
    {
        $this->endDate = $endDate;
        $this->resetPage();
    }

    #[Computed]
    public function journals()
    {
        $wh_accounts = ChartOfAccount::where('warehouse_id', $this->warehouse)->pluck('id')->toArray();

        $journals = Journal::with([
            'cred:id,acc_name,account_id,warehouse_id',
            'debt:id,acc_name,account_id,warehouse_id',
            'user:id,name',
            'warehouse:id,name'
        ])
            ->where(function ($query) use ($wh_accounts) {
                $query->whereIn('cred_code', $wh_accounts)
                    ->orWhereIn('debt_code', $wh_accounts);
            })
            ->whereDate('date_issued', $this->endDate)
            ->where('trx_type', 'Mutasi Kas')
            ->when($this->search, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->whereHas('cred', fn($q) => $q->where('acc_name', 'like', "%{$this->search}%"))
                        ->orWhereHas('debt', fn($q) => $q->where('acc_name', 'like', "%{$this->search}%"))
                        ->orWhere('invoice', 'like', "%{$this->search}%")
                        ->orWhere('description', 'like', "%{$this->search}%");
                });
            })
            ->latest()
            ->paginate($this->perPage, ['*'], 'journalPage');

        return $journals;
    }

    #[On('journal-created')]
    public function render()
    {
        return view('livewire.dashboard.mutation-history');
    }
}
