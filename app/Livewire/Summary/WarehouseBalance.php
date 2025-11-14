<?php

namespace App\Livewire\Summary;

use Carbon\Carbon;
use App\Models\Journal;
use Livewire\Component;
use App\Models\Warehouse;
use App\Models\AccountBalance;
use App\Models\ChartOfAccount;
use Illuminate\Support\Facades\Log;

class WarehouseBalance extends Component
{
    public $endDate;
    public $balances = [];

    public function mount()
    {
        $this->endDate = date('Y-m-d');
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

    public function getWarehouseBalance($endDate)
    {
        $endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay();
        $previousDate = $endDate->copy()->subDay()->toDateString(); // Tanggal untuk mencari saldo awal

        // --- Perbaikan Kinerja: Pre-fetch data jurnal dan saldo sebelumnya dalam satu/dua kueri ---

        // 1. Ambil semua ChartOfAccount yang relevan
        $chartOfAccounts = ChartOfAccount::with('account')->get();

        Log::info("Found " . $chartOfAccounts->count() . " chart of accounts.");

        // Dapatkan semua ID akun untuk kueri berikutnya
        $allAccountIds = $chartOfAccounts->pluck('id')->toArray();

        // 2. Pre-fetch saldo akhir hari sebelumnya untuk SEMUA akun yang relevan
        // Menggunakan array asosiatif [chart_of_account_id => ending_balance] untuk look-up cepat
        $previousDayBalances = AccountBalance::whereIn('chart_of_account_id', $allAccountIds)
            ->where('balance_date', $previousDate)
            ->pluck('ending_balance', 'chart_of_account_id')
            ->toArray();
        Log::info("Fetched " . count($previousDayBalances) . " previous day balances for {$previousDate}.");


        // 3. Pre-fetch total debit aktivitas untuk HANYA tanggal $endDate
        $dailyDebits = Journal::selectRaw('debt_code as account_id, SUM(amount) as total_amount')
            ->whereIn('debt_code', $allAccountIds)
            ->whereBetween('date_issued', [$previousDate, $endDate])
            // HANYA AKTIVITAS HARI INI
            ->groupBy('debt_code')
            ->pluck('total_amount', 'account_id')
            ->toArray();
        Log::info("Fetched " . count($dailyDebits) . " daily debit sums for {$endDate->toDateString()}.");


        // 4. Pre-fetch total credit aktivitas untuk HANYA tanggal $endDate
        $dailyCredits = Journal::selectRaw('cred_code as account_id, SUM(amount) as total_amount')
            ->whereIn('cred_code', $allAccountIds)
            ->whereBetween('date_issued', [$previousDate, $endDate])
            // HANYA AKTIVITAS HARI INI
            ->groupBy('cred_code')
            ->pluck('total_amount', 'account_id')
            ->toArray();
        Log::info("Fetched " . count($dailyCredits) . " daily credit sums for {$endDate->toDateString()}.");


        // --- Logic untuk memeriksa dan memicu update saldo yang hilang ---
        $missingDatesToUpdate = [];
        foreach ($allAccountIds as $accountId) {
            // Memeriksa keberadaan saldo menggunakan chart_of_account_id
            if (!isset($previousDayBalances[$accountId])) {
                // Jika saldo hari sebelumnya tidak ditemukan di account_balances
                $missingDatesToUpdate[$previousDate] = true; // Tambahkan tanggal ini ke daftar
                Log::warning("Missing AccountBalance record for account ID {$accountId} on {$previousDate}. Will trigger update.");
            }
        }

        foreach (array_keys($missingDatesToUpdate) as $date) {
            Log::info("Calling _updateBalancesDirectly for missing date: {$date}");
            // Memanggil fungsi baru secara langsung di controller
            Journal::_updateBalancesDirectly($date);
        }
        // --- Akhir logic pemicu ---

        // --- PENTING: Re-fetch previousDayBalances setelah _updateBalancesDirectly dipanggil ---
        // Ini memastikan bahwa jika _updateBalancesDirectly baru saja menambahkan data,
        // data tersebut akan tersedia untuk perhitungan saldo selanjutnya dalam permintaan ini.
        if (!empty($missingDatesToUpdate)) {
            Log::info("Re-fetching previous day balances after direct updates.");
            $previousDayBalances = AccountBalance::whereIn('chart_of_account_id', $allAccountIds)
                ->where('balance_date', $previousDate)
                ->pluck('ending_balance', 'chart_of_account_id')
                ->toArray();
            Log::info("Re-fetched " . count($previousDayBalances) . " previous day balances.");
        }


        // --- Perhitungan Saldo per Akun ---
        foreach ($chartOfAccounts as $chartOfAccount) {
            // Mengambil saldo awal dari previousDayBalances atau fallback ke st_balance
            // Menggunakan chart_of_account_id untuk look-up di previousDayBalances
            $initBalance = $previousDayBalances[$chartOfAccount->id] ?? ($chartOfAccount->st_balance ?? 0.00);
            $normalBalance = $chartOfAccount->account->status ?? '';

            // Mengambil debit/credit hari ini dari pre-fetched arrays
            $debitToday = $dailyDebits[$chartOfAccount->id] ?? 0.00;
            $creditToday = $dailyCredits[$chartOfAccount->id] ?? 0.00;

            // Hitung saldo akhir
            $chartOfAccount->balance = $initBalance + ($normalBalance === 'D' ? $debitToday - $creditToday : $creditToday - $debitToday);
        }

        // --- Filter cash/bank accounts ---
        // Filter di sini harus menggunakan relasi 'account' karena acc_id ada di sana
        $sumtotalCash = $chartOfAccounts->filter(function ($coa) {
            return ($coa->account && $coa->account->id === 1); // Asumsi acc_id 1 untuk Cash
        });
        $sumtotalBank = $chartOfAccounts->filter(function ($coa) {
            return ($coa->account && $coa->account->id === 2); // Asumsi acc_id 2 untuk Bank
        });


        // Ambil warehouse
        $warehouses = Warehouse::where('status', 1)->orderBy('name')->get();

        $totalProfitMonthly = Journal::selectRaw('
        SUM(CASE WHEN fee_amount > 0 THEN fee_amount ELSE 0 END) as total_fee,
        warehouse_id
                ')
            ->whereBetween('date_issued', [
                Carbon::parse($endDate)->startOfMonth(),
                Carbon::parse($endDate)->endOfMonth()
            ])
            ->where('warehouse_id', '!=', 1)
            ->groupBy('warehouse_id')
            ->get()
            ->keyBy('warehouse_id'); // 👈 ini penting


        $data = [
            'warehouse' => $warehouses->map(function ($w) use ($chartOfAccounts, $totalProfitMonthly, $endDate) {
                return [
                    'id' => $w->id,
                    'name' => $w->name,
                    // Filter di sini juga harus menggunakan relasi 'account'
                    'cash' => $chartOfAccounts->filter(function ($coa) use ($w) {
                        return ($coa->account && $coa->account->id === 1 && $coa->warehouse_id === $w->id);
                    })->sum('balance'),
                    'bank' => $chartOfAccounts->filter(function ($coa) use ($w) {
                        return ($coa->account && $coa->account->id === 2 && $coa->warehouse_id === $w->id);
                    })->sum('balance'),
                    'average_profit' => $w->id === 1
                        ? 0
                        : (
                            isset($totalProfitMonthly[$w->id])
                            ? $totalProfitMonthly[$w->id]->total_fee / $this->countDaysInMonth($endDate)
                            : 0
                        ),

                ];
            }),
            'totalCash' => $sumtotalCash->sum('balance'),
            'totalBank' => $sumtotalBank->sum('balance'),
        ];


        return $data;
    }

    public function updatedEndDate()
    {
        $this->balances = $this->getWarehouseBalance($this->endDate);
    }

    public function render()
    {
        $this->balances = $this->getWarehouseBalance($this->endDate);
        // dd($this->getWarehouseBalance($this->endDate));
        return view('livewire.summary.warehouse-balance', [
            'warehouses' => $this->balances['warehouse'],
            'totalCash' => $this->balances['totalCash'],
            'totalBank' => $this->balances['totalBank'],
        ]);
    }
}
