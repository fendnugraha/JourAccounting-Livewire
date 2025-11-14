<?php

namespace App\Livewire\Summary;

use App\Models\Journal;
use Carbon\Carbon;
use Livewire\Component;

class RevenueReport extends Component
{
    public $startDate;
    public $endDate;
    public $revenue = [];

    public function mount()
    {
        $this->startDate = date('Y-m-d');
        $this->endDate = date('Y-m-d');
    }

    public function getRevenueReport($startDate, $endDate)
    {
        $startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfDay();
        $endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay();

        $revenue = Journal::with(['warehouse'])
            ->selectRaw('SUM(amount) as total, warehouse_id, SUM(fee_amount) + 0 as sumfee')
            ->whereBetween('date_issued', [$startDate, $endDate])
            ->groupBy('warehouse_id')
            ->orderBy('sumfee', 'desc')
            ->get();

        $data = [
            'revenue' => $revenue->map(function ($r) use ($startDate, $endDate) {
                $rv = $r->whereBetween('date_issued', [
                    Carbon::parse($startDate)->startOfDay(),
                    Carbon::parse($endDate)->endOfDay()
                ])
                    ->where('trx_type', '!=', 'Jurnal Umum')
                    ->where('warehouse_id', $r->warehouse_id)->get();
                return [
                    'warehouse' => $r->warehouse->name,
                    'warehouseId' => $r->warehouse_id,
                    'warehouse_code' => $r->warehouse->code,
                    'cash' => $rv->where('debt_code', (int) 2)->where('warehouse_id', '!=', (int) 1)->sum('amount'),
                    'transfer' => $rv->where('trx_type', 'Transfer Uang')->sum('amount'),
                    'withdrawal' => $rv->where('trx_type', 'Tarik Tunai')->sum('amount'),
                    'voucher' => $rv->where('trx_type', 'Voucher & SP')->sum('amount'),
                    'accessory' => $rv->where('trx_type', 'Accessories')->sum('amount'),
                    'deposit' => $rv->where('trx_type', 'Deposit')->sum('amount'),
                    'bank_fee' => $rv->where('trx_type', 'Bank Fee')->sum('fee_amount'),
                    'trx' => $rv->count() - $rv->whereIn('trx_type', ['Pengeluaran', 'Mutasi Kas'])->count(),
                    'expense' => -$rv->where('trx_type', 'Pengeluaran')->sum('fee_amount'),
                    'profit' => doubleval($r->sumfee ?? 0)
                ];
            })
        ];

        return $data;
    }

    public function updated($property)
    {
        if (in_array($property, ['startDate', 'endDate'])) {
            $this->revenue = $this->getRevenueReport($this->startDate, $this->endDate);
        }
    }

    public function render()
    {
        $this->revenue = $this->getRevenueReport($this->startDate, $this->endDate);
        // dd($this->revenue);
        return view('livewire.summary.revenue-report', [
            'revenues' => $this->revenue['revenue']
        ]);
    }
}
