<?php

namespace App\Livewire\Report;

use Carbon\Carbon;
use App\Models\Journal;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ChartOfAccount;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

class GeneralLedger extends Component
{
    use WithPagination;
    public $startDate;
    public $endDate;
    public $warehouse_id;
    public $account;
    public $perPage = 5;
    public $search = '';

    public function mount()
    {
        $this->account = '10100-001';
        $this->startDate = date('Y-m-d H:i');
        $this->endDate = date('Y-m-d H:i');
    }

    public function updateLimitPage($pageName = 'page')
    {
        $this->resetPage(pageName: $pageName);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $journal = new Journal();
        $startDate = Carbon::parse($this->endDate)->startOfDay();
        $endDate = Carbon::parse($this->endDate)->endOfDay();

        $chartOfAccounts = ChartOfAccount::where(fn($query) => Auth::user()->role->role !== 'Administrator' ? $query->where('warehouse_id', $this->warehouse_id) : $query)->orderBy('acc_code', 'asc')->get();
        $journal = new Journal();
        $journals = $journal->with('debt.account', 'cred.account', 'warehouse', 'user')
            ->whereBetween('date_issued', [$startDate, $endDate])
            ->where(function ($query) {
                $query->where('invoice', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%')
                    ->orWhere('amount', 'like', '%' . $this->search . '%');
            })
            ->where(function ($query) {
                $query->where('debt_code', $this->account)
                    ->orWhere('cred_code', $this->account);
            })
            ->orderBy('date_issued', 'asc')
            ->paginate($this->perPage, ['*'], 'mutationHistory');

        $total = $journal->with('debt.account', 'cred.account', 'warehouse', 'user')->where('debt_code', $this->account)
            ->whereBetween('date_issued', [$startDate, $endDate])
            ->orWhere('cred_code', $this->account)
            ->WhereBetween('date_issued', [$startDate, $endDate])
            ->orderBy('date_issued', 'asc')
            ->get();

        $initBalanceDate = Carbon::parse($startDate)->subDay(1)->endOfDay();

        $debt_total = $total->where('debt_code', $this->account)->sum('amount');
        $cred_total = $total->where('cred_code', $this->account)->sum('amount');

        return view('livewire.report.general-ledger', [
            'title' => 'General Ledger',
            'accounts' => $chartOfAccounts,
            'journals' => $journals,
            'initBalance' => $journal->endBalanceBetweenDate($this->account, '0000-00-00', $initBalanceDate),
            'endBalance' => $journal->endBalanceBetweenDate($this->account, '0000-00-00', $endDate),
            'debt_total' => $debt_total,
            'cred_total' => $cred_total
        ]);
    }
}
