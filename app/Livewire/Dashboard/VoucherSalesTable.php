<?php

namespace App\Livewire\Dashboard;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Warehouse;
use App\Models\Transaction;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class VoucherSalesTable extends Component
{
    use WithPagination;

    public $warehouse;
    public $startDate;
    public $endDate;
    public $voucherPerPage = 5;
    public $accessoryPerPage = 5;

    public function mount()
    {
        $this->warehouse = Auth::user()->roles->warehouse_id;
        $this->startDate = date('Y-m-d');
        $this->endDate = date('Y-m-d');
    }

    public function updatedVoucherPerPage()
    {
        $this->resetPage('voucherPage');
    }

    public function updatedAccessoryPerPage()
    {
        $this->resetPage('accessoryPage');
    }

    public function getTransactionByWarehouse($warehouse, $startDate, $endDate, $pageName, $perPage = 5, $is_vcr = true)
    {
        $startDate = $startDate
            ? Carbon::parse($startDate)->startOfDay()
            : Carbon::now()->startOfDay();

        $endDate = $endDate
            ? Carbon::parse($endDate)->endOfDay()
            : Carbon::now()->endOfDay();

        $transactions = Transaction::with('product')
            ->selectRaw('
            product_id,
            SUM(quantity) as quantity,
            SUM(quantity * cost) as total_cost,
            SUM(quantity * price) as total_price,
            SUM(quantity * price - quantity * cost) as total_fee
        ')
            ->where('invoice', 'like', 'JR.BK%')
            ->where('transaction_type', 'Sales')
            ->whereBetween('date_issued', [$startDate, $endDate])
            ->when($is_vcr, function ($query) {
                $query->whereHas('product', fn($q) => $q->where('category', 'Voucher & SP'));
            }, function ($query) {
                $query->whereHas('product', fn($q) => $q->where('category', '!=', 'Voucher & SP'));
            })
            ->when($warehouse !== 'all', function ($query) use ($warehouse) {
                $query->where('warehouse_id', $warehouse);
            })
            ->groupBy('product_id')
            ->paginate($perPage, ['*'], $pageName)
            ->onEachSide(0);

        return $transactions;
    }

    public function updated($property)
    {
        if (in_array($property, ['warehouse', 'startDate', 'endDate'])) {
            $this->resetPage();
        }
        // $this->dispatch('warehouse-changed', warehouse: $this->warehouse);
    }


    // #[On('warehouse-changed')]
    public function render()
    {
        return view('livewire.dashboard.voucher-sales-table', [
            'voucher' => $this->getTransactionByWarehouse($this->warehouse, $this->startDate, $this->endDate, 'voucherPage', $this->voucherPerPage, true),
            'accessory' => $this->getTransactionByWarehouse($this->warehouse, $this->startDate, $this->endDate, 'accessoryPage', $this->accessoryPerPage, false),
            'warehouses' => Warehouse::all(),
        ]);
    }
}
