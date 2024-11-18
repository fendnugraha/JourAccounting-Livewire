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
    public $profitLossData;
    public $transactions;
    public $chartOfAccounts;

    public function mount()
    {
        // Set the end date for the balance sheet calculation
        $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d H:i');

        // Update profitLossData
        $this->updateProfitLossData();

        // Run the transactions and balance calculation logic
        $this->updateTransactionsAndBalances();
    }

    public function updateProfitLossData()
    {
        $journal = new Journal();
        $this->profitLossData = $journal->profitLossCount('0000-00-00', $this->endDate);
    }

    public function updateTransactionsAndBalances()
    {
        // Fetch transactions
        $journal = new Journal();
        $this->transactions = $journal->with(['debt', 'cred'])
            ->selectRaw('debt_code, cred_code, SUM(amount) as total')
            ->whereBetween('date_issued', [Carbon::create(0000, 1, 1)->endOfDay(), $this->endDate])
            ->groupBy('debt_code', 'cred_code')
            ->get();

        // Fetch chart of accounts
        $this->chartOfAccounts = ChartOfAccount::with(['account'])->get();

        // Process chart of accounts and update balances
        foreach ($this->chartOfAccounts as $value) {
            $debit = $this->transactions->where('debt_code', $value->acc_code)->sum('total');
            $credit = $this->transactions->where('cred_code', $value->acc_code)->sum('total');

            $value->balance = ($value->account->status == "D")
                ? ($value->st_balance + $debit - $credit)
                : ($value->st_balance + $credit - $debit);
        }

        // Calculate equity and update balances for the chart of accounts
        $initEquity = $this->chartOfAccounts->where('acc_code', '30100-001')->first()->st_balance;
        $assets = $this->chartOfAccounts->whereIn('account_id', \range(1, 18));
        $liabilities = $this->chartOfAccounts->whereIn('account_id', \range(19, 25));
        $equity = $this->chartOfAccounts->where('account_id', 26);

        // Update equity balance in the database
        ChartOfAccount::where('acc_code', '30100-001')->update([
            'st_balance' => $initEquity + $assets->sum('balance') - $liabilities->sum('balance') - $equity->sum('balance'),
        ]);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        // The data is already populated in mount() and updateTransactionsAndBalances()
        // Just pass the data to the view

        $assets = $this->chartOfAccounts->whereIn('account_id', \range(1, 18));
        $liabilities = $this->chartOfAccounts->whereIn('account_id', \range(19, 25));
        $equity = $this->chartOfAccounts->where('account_id', 26);

        return view('livewire.report.balance-sheet', [
            'title' => 'Balance Sheet',
            'assets' => $assets->groupBy('account_id'),
            'liabilities' => $liabilities->groupBy('account_id'),
            'equity' => $equity->groupBy('account_id'),
        ]);
    }
}
