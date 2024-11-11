<?php

namespace App\Livewire\Report;

use Carbon\Carbon;
use App\Models\Journal;
use Livewire\Component;
use App\Models\ChartOfAccount;
use Livewire\Attributes\Layout;

class Cashflow extends Component
{
    public $startDate;
    public $endDate;

    public function mount()
    {
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d H:i');
        $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d H:i');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $journal = new Journal();
        $startDate = Carbon::parse($this->startDate)->startOfDay();
        $endDate = Carbon::parse($this->endDate)->endOfDay();

        $chartOfAccounts = ChartOfAccount::with(['account'])->get();
        $cashBank = $chartOfAccounts->whereIn('account_id', [1, 2]);

        $transactions = $journal->with(['debt', 'cred'])
            ->selectRaw('debt_code, cred_code, SUM(amount) as total')
            ->whereIn('debt_code', $cashBank->pluck('acc_code'))
            ->whereBetween('date_issued', [$startDate, $endDate])
            ->orWhereIn('cred_code', $cashBank->pluck('acc_code'))
            ->whereBetween('date_issued', [$startDate, $endDate])
            ->groupBy('debt_code', 'cred_code')
            ->get();

        foreach ($chartOfAccounts as $value) {
            $debit = $transactions->where('cred_code', $value->acc_code)
                ->sum('total');
            $credit = $transactions->where('debt_code', $value->acc_code)
                ->sum('total');

            $value->balance = $debit - $credit;
        }

        $initBalance = $chartOfAccounts->whereIn('account_id', [1, 2])->sum('st_balance');

        $startBalance = $initBalance + $journal->cashflowCount('0000-00-00', $startDate);
        $endBalance = $initBalance + $journal->cashflowCount('0000-00-00', $endDate);
        // \dd($endDate->subDay(1), $endDate, $startBalance, $endBalance);

        if ($startBalance == 0) {
            $percentageChange = 0;
        } else {
            $percentageChange = ($endBalance - $startBalance) / $startBalance * 100;
        }
        return view('livewire.report.cashflow', [
            'title' => 'Cashflow',
            'pendapatan' => $chartOfAccounts->whereIn('account_id', \range(27, 30))->groupBy('account_id'),
            'piutang' => $chartOfAccounts->whereIn('account_id', [4, 5])->groupBy('account_id'),
            'persediaan' => $chartOfAccounts->whereIn('account_id', [6, 7])->groupBy('account_id'),
            'investasi' => $chartOfAccounts->whereIn('account_id', [10, 11, 12])->groupBy('account_id'),
            'assets' => $chartOfAccounts->whereIn('account_id', [13, 14, 15, 16, 17, 18])->groupBy('account_id'),
            'hutang' => $chartOfAccounts->whereIn('account_id', \range(19, 25))->groupBy('account_id'),
            'modal' => $chartOfAccounts->where('account_id', 26)->groupBy('account_id'),
            'biaya' => $chartOfAccounts->whereIn('account_id', \range(33, 45))->groupBy('account_id'),
            'startBalance' => $startBalance,
            'endBalance' => $endBalance,
            'percentageChange' => $percentageChange,
            'growth' => $endBalance - $startBalance
        ]);
    }
}
