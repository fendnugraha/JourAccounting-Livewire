<?php

namespace App\Livewire\Dashboard;

use Carbon\Carbon;
use App\Models\Journal;
use Livewire\Component;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Auth;

class DailyDashboard extends Component
{
    public $warehouse = 'all';
    public $startDate = null;
    public $endDate = null;

    public function mount()
    {
        // $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        // $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        $this->warehouse = Auth::user()->roles->warehouse_id;
    }

    function countDaysInMonth($date)
    {
        $parsed = Carbon::parse($date);
        $selectedMonth = Carbon::create($parsed->year, $parsed->month, 1);
        $now = Carbon::now();

        return $selectedMonth->isSameMonth($now)
            ? $now->day
            : $selectedMonth->daysInMonth;
    }

    public function getDailyReport()
    {
        $warehouse = $this->warehouse;
        $startDate = $this->startDate ? Carbon::parse($this->startDate)->startOfDay() : Carbon::now()->startOfDay();
        $endDate = $this->endDate ? Carbon::parse($this->endDate)->endOfDay() : Carbon::now()->endOfDay();

        $diffDays = Carbon::parse($endDate)->startOfDay()->diffInDays(Carbon::parse($startDate)->startOfDay());

        $warehouseBalance = Journal::balancesByWarehouse($warehouse, $endDate);

        $trxForSalesCount = Journal::selectRaw('
        trx_type,
        SUM(amount) as total_amount,
        SUM(fee_amount) as total_fee,
        COUNT(*) as total_count
    ')
            ->whereBetween('date_issued', [$startDate, $endDate])
            ->when($warehouse !== 'all', fn($q) => $q->where('warehouse_id', $warehouse))
            ->groupBy('trx_type')
            ->get()
            ->keyBy('trx_type');

        $totalFee = Journal::selectRaw('
                SUM(fee_amount) as total_fee,
                SUM(CASE WHEN fee_amount > 0 THEN fee_amount ELSE 0 END) as total_fee_positive,
                SUM(CASE WHEN fee_amount < 0 THEN fee_amount ELSE 0 END) as total_fee_negative
            ')
            ->whereBetween('date_issued', [$startDate, $endDate])
            ->when($warehouse !== 'all', fn($q) => $q->where('warehouse_id', $warehouse))
            ->first();

        $countTrxByType = Journal::whereBetween('date_issued', [$startDate, $endDate])
            ->when($warehouse !== 'all', fn($q) => $q->where('warehouse_id', $warehouse))
            ->whereIn('trx_type', ['Transfer Uang', 'Tarik Tunai', 'Deposit', 'Voucher & SP', 'Accessories', 'Bank Fee'])
            ->count();

        $dailyReport = [
            'totalCash' => (int) $warehouseBalance['sumtotalCash'],
            'totalBank' => (int) $warehouseBalance['sumtotalBank'],
            'totalTransfer' => [
                'total' => (int) ($trxForSalesCount['Transfer Uang']->total_amount ?? 0),
                'count' => (int) ($trxForSalesCount['Transfer Uang']->total_count ?? 0)
            ],
            'totalCashWithdrawal' => [
                'total' => (int) ($trxForSalesCount['Tarik Tunai']->total_amount ?? 0),
                'count' => (int) ($trxForSalesCount['Tarik Tunai']->total_count ?? 0)
            ],
            'totalCashDeposit' => [
                'total' => (int) ($trxForSalesCount['Deposit']->total_amount ?? 0),
                'count' => (int) ($trxForSalesCount['Deposit']->total_count ?? 0)
            ],
            'totalVoucher' => [
                'total' => (int) ($trxForSalesCount['Voucher & SP']->total_amount ?? 0),
                'count' => (int) ($trxForSalesCount['Voucher & SP']->total_count ?? 0)
            ],
            'totalAccessories' => [
                'total' => (int) ($trxForSalesCount['Accessories']->total_amount ?? 0),
                'count' => (int) ($trxForSalesCount['Accessories']->total_count ?? 0)
            ],
            'totalExpense' => (int) ($trxForSalesCount['Pengeluaran']->total_fee ?? 0),
            'totalBankFee' => (int) ($trxForSalesCount['Bank Fee']->total_amount ?? 0),
            'totalFee' => (int) ($totalFee->total_fee_positive ?? 0),
            'totalCorrection' => (int) ($trxForSalesCount['Correction']->total_fee ?? 0),
            'profit' => (int) ($totalFee->total_fee ?? 0),
            'countDays' => $this->countDaysInMonth($startDate),
            'diffDays' => $diffDays,
            'averageProfit' => (int) (
                $diffDays == 0
                ? ($totalFee->total_fee_positive ?? 0)
                : (($totalFee->total_fee_positive ?? 0) / $this->countDaysInMonth($startDate))
            ),
            'salesCount' => $countTrxByType,
            'totalRevenue' => array_sum([
                $warehouseBalance['sumtotalCash'],
                $trxForSalesCount['Voucher & SP']->total_amount ?? 0,
                $trxForSalesCount['Deposit']->total_amount ?? 0,
                $trxForSalesCount['Accessories']->total_amount ?? 0,
                $totalFee->total_fee ?? 0
            ]),
        ];

        return $dailyReport;
    }

    public function render()
    {
        return view('livewire.dashboard.daily-dashboard', [
            'dailyReport' => $this->getDailyReport(),
            'warehouses' => Warehouse::orderBy('name', 'asc')->get()
        ]);
    }
}
