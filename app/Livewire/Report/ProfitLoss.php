<?php

namespace App\Livewire\Report;

use Carbon\Carbon;
use App\Models\Journal;
use Livewire\Component;
use App\Models\ChartOfAccount;
use Livewire\Attributes\Layout;

class ProfitLoss extends Component
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
        // $journal->profitLossCount('0000-00-00', $endDate);

        $transactions = $journal->with(['debt', 'cred'])
            ->selectRaw('debt_code, cred_code, SUM(amount) as total')
            ->whereBetween('date_issued', [$this->startDate, $this->endDate])
            ->groupBy('debt_code', 'cred_code')
            ->get();

        $chartOfAccounts = ChartOfAccount::with(['account'])->get();

        foreach ($chartOfAccounts as $value) {
            $debit = $transactions->where('debt_code', $value->acc_code)->sum('total');
            $credit = $transactions->where('cred_code', $value->acc_code)->sum('total');

            $value->balance = ($value->account->status == "D") ? ($value->st_balance + $debit - $credit) : ($value->st_balance + $credit - $debit);
        }

        return view('livewire.report.profit-loss', [
            'title' => 'Profit Loss',
            'revenue' => $chartOfAccounts->whereIn('account_id', \range(27, 30))->groupBy('account_id'),
            'cost' => $chartOfAccounts->whereIn('account_id', \range(31, 32))->groupBy('account_id'),
            'expense' => $chartOfAccounts->whereIn('account_id', \range(33, 45))->groupBy('account_id'),
        ]);
    }
}
