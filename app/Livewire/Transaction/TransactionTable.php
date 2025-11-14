<?php

namespace App\Livewire\Transaction;

use Carbon\Carbon;
use App\Models\Journal;
use Livewire\Component;
use App\Models\Warehouse;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\ChartOfAccount;
use Illuminate\Support\Facades\Auth;

class TransactionTable extends Component
{
    use WithPagination;

    public $sort = 'desc';
    public $warehouse;
    public $startDate;
    public $endDate;
    public $cash;
    public $account;
    public $perPage = 5;
    public $search = '';

    public function mount()
    {
        $this->warehouse = Auth::user()->roles->warehouse_id;
        $this->startDate = date('Y-m-d H:i');
        $this->endDate = date('Y-m-d H:i');
        $this->cash = Auth::user()->roles->warehouse->chart_of_account_id;
    }

    public function updatedWarehouse()
    {
        $this->resetPage('journalPage');
        $this->account = null;
    }

    public function updateLimitPage($pageName = 'journalPage')
    {
        $this->resetPage($pageName);
    }

    public function updatedSearch()
    {
        $this->resetPage('journalPage');
    }

    public function getJournalByWarehouse($warehouse, $startDate, $endDate)
    {
        $chartOfAccounts = ChartOfAccount::where('warehouse_id', $warehouse)->pluck('id')->toArray();
        $startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfDay();
        $endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay();

        $journals = Journal::with([
            'debt:id,acc_name,account_id,warehouse_id',
            'cred:id,acc_name,account_id,warehouse_id',
            'transaction.product'
        ])
            ->whereBetween('date_issued', [$startDate, $endDate])
            ->where(function ($query) use ($chartOfAccounts, $warehouse) {
                // Filter by selected account OR by all accounts in chartOfAccounts
                $query->when($this->account, function ($subQuery) {
                    $subQuery->where('cred_code', $this->account)
                        ->orWhere('debt_code', $this->account);
                }, function ($subQuery) use ($chartOfAccounts) {
                    $subQuery->whereIn('cred_code', $chartOfAccounts)
                        ->orWhereIn('debt_code', $chartOfAccounts);
                })
                    // Tambahkan logika inventory di cabang terkait
                    ->orWhere(function ($subQuery) use ($warehouse) {
                        $this->account ? $subQuery : $subQuery->where(function ($inner) {
                            $inner->where('debt_code', ChartOfAccount::INVENTORY)
                                ->orWhere('cred_code', ChartOfAccount::INVENTORY);
                        })
                            ->where('warehouse_id', $warehouse);
                    });
            })
            ->FilterJournals([
                'search' => $this->search,
                'account' => $this->account,
            ])
            ->orderBy('date_issued', $this->sort ?? 'desc')
            ->paginate($this->perPage, ['*'], 'journalPage')
            ->onEachSide(0);

        return $journals;
    }

    #[On('journal-created')]
    public function updateData()
    {
        // Perbarui data yang diperlukan ketika event terjadi
        $this->resetPage('journalPage'); // Reset pagination jika diperlukan
    }

    public function render()
    {
        $accounts = ChartOfAccount::whereIn('account_id', [1, 2])->where('warehouse_id', $this->warehouse)->get();
        return view('livewire.transaction.transaction-table', [
            'journals' => $this->getJournalByWarehouse($this->warehouse, $this->startDate, $this->endDate),
            'accounts' => $accounts,
            'warehouses' => Warehouse::all(),
        ]);
    }
}
