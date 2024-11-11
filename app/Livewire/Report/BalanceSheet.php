<?php

namespace App\Livewire\Report;

use Carbon\Carbon;
use App\Models\Journal;
use Livewire\Component;
use App\Models\ChartOfAccount;
use Livewire\Attributes\Layout;

class BalanceSheet extends Component
{
    public $endDate;

    public function mount()
    {
        $journal = new Journal();
        $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d H:i');
        $journal->profitLossCount('0000-00-00', $this->endDate);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $journal = new Journal();
        $journal->profitLossCount('0000-00-00', $this->endDate);

        $transactions = $journal->with(['debt', 'cred'])
            ->selectRaw('debt_code, cred_code, SUM(amount) as total')
            ->whereBetween('date_issued', [Carbon::create(0000, 1, 1)->endOfDay(), $this->endDate])
            ->groupBy('debt_code', 'cred_code')
            ->get();

        $chartOfAccounts = ChartOfAccount::with(['account'])->get();

        foreach ($chartOfAccounts as $value) {
            $debit = $transactions->where('debt_code', $value->acc_code)->sum('total');
            $credit = $transactions->where('cred_code', $value->acc_code)->sum('total');

            $value->balance = ($value->account->status == "D") ? ($value->st_balance + $debit - $credit) : ($value->st_balance + $credit - $debit);
        }

        $initEquity = $chartOfAccounts->where('acc_code', '30100-001')->first()->st_balance;
        $assets = $chartOfAccounts->whereIn('account_id', \range(1, 18));
        $liabilities = $chartOfAccounts->whereIn('account_id', \range(19, 25));
        $equity = $chartOfAccounts->where('account_id', 26);

        ChartOfAccount::where('acc_code', '30100-001')->update([
            'st_balance' => $initEquity + $assets->sum('balance') - $liabilities->sum('balance') - $equity->sum('balance'),
        ]);

        return view('livewire.report.balance-sheet', [
            'title' => 'Balance Sheet',
            'assets' => $assets->groupBy('account_id'),
            'liabilities' => $liabilities->groupBy('account_id'),
            'equity' => $equity->groupBy('account_id'),
        ]);
    }
}
