<?php

namespace App\Livewire\Summary;

use Carbon\Carbon;
use App\Models\Journal;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ChartOfAccount;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;

class Generaledger extends Component
{
    use WithPagination;

    public $account = 1;
    public $startDate;
    public $endDate;
    public $search = '';

    public $history = [];

    public function mount()
    {
        $this->startDate = date('Y-m-d');
        $this->endDate = date('Y-m-d');
        $this->account = Auth::user()->roles->warehouse->chart_of_account_id;
    }

    public function mutationHistory($account, $startDate, $endDate)
    {
        $startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfDay();
        $endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay();

        $total = Journal::with('debt.account', 'cred.account', 'warehouse', 'user')->where('debt_code', $account)
            ->whereBetween('date_issued', [$startDate, $endDate])
            ->orWhere('cred_code', $account)
            ->WhereBetween('date_issued', [$startDate, $endDate])
            ->orderBy('date_issued', 'asc')
            ->get();

        $initBalanceDate = Carbon::parse($startDate)->subDay(1)->endOfDay();

        $debt_total = $total->where('debt_code', $account)->sum('amount');
        $cred_total = $total->where('cred_code', $account)->sum('amount');

        $data = [
            'initBalance' => Journal::endBalanceBetweenDate($account, '0000-00-00', $initBalanceDate) ?? 0,
            'endBalance' => Journal::endBalanceBetweenDate($account, '0000-00-00', $endDate) ?? 0,
            'debt_total' => $debt_total,
            'cred_total' => $cred_total,
        ];

        return $data;
    }

    #[Computed]
    public function journals()
    {
        $startDate = Carbon::parse($this->startDate)->startOfDay();
        $endDate = Carbon::parse($this->endDate)->endOfDay();

        $journals = Journal::with('debt.account', 'cred.account', 'warehouse', 'user')
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
            ->paginate(10, ['*'], 'mutationHistory');

        return $journals;
    }


    public function updated($property)
    {
        if (in_array($property, ['account', 'startDate', 'endDate'])) {
            $this->history = $this->mutationHistory($this->account, $this->startDate, $this->endDate);
        }
    }

    public function render()
    {
        $this->history = $this->mutationHistory($this->account, $this->startDate, $this->endDate);

        return view('livewire.summary.generaledger', [
            'initBalance' => $this->history['initBalance'],
            'endBalance' => $this->history['endBalance'],
            'debt_total' => $this->history['debt_total'],
            'cred_total' => $this->history['cred_total'],
            'accounts' => ChartOfAccount::orderBy('acc_code', 'asc')->get(),
        ]);
    }
}
