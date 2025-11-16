<?php

namespace App\Livewire\Dashboard;

use Carbon\Carbon;
use App\Models\Journal;
use Livewire\Component;
use App\Models\Warehouse;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class ExpenseTable extends Component
{
    use WithPagination;

    public $warehouse;
    public $startDate;
    public $endDate;
    public $expensePerPage = 5;

    public function mount()
    {
        $this->warehouse = Auth::user()->roles->warehouse_id;
        $this->startDate = date('Y-m-d');
        $this->endDate = date('Y-m-d');
    }

    public function getExpenses($warehouse, $startDate, $endDate)
    {
        $startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfDay();
        $endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay();

        $expenses = Journal::with('warehouse', 'debt')
            ->where(function ($query) use ($warehouse) {
                if ($warehouse === "all") {
                    $query;
                } else {
                    $query->where('warehouse_id', $warehouse);
                }
            })
            ->whereBetween('date_issued', [$startDate, $endDate])
            ->where('trx_type', 'Pengeluaran')
            ->orderBy('id', 'desc')
            ->paginate($this->expensePerPage, ['*'], 'expenses')->onEachSide(0);

        return $expenses;
    }

    public function updated($property)
    {
        if (in_array($property, ['warehouse', 'startDate', 'endDate'])) {
            $this->resetPage();
        }
    }

    public function render()
    {
        return view('livewire.dashboard.expense-table', [
            'expenses' => $this->getExpenses($this->warehouse, $this->startDate, $this->endDate),
            'warehouses' => Warehouse::orderBy('name', 'asc')->get(),
        ]);
    }
}
