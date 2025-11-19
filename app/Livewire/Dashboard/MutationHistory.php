<?php

namespace App\Livewire\Dashboard;

use Carbon\Carbon;
use App\Models\Journal;
use Livewire\Component;
use App\Models\LogActivity;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\ChartOfAccount;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class MutationHistory extends Component
{
    use WithPagination;

    public $warehouse;
    public $endDate;
    public $perPage = 5;
    public $search = '';

    #[On('warehouse-changed')]
    public function updateWarehouse($warehouse)
    {
        $this->warehouse = $warehouse;
        $this->resetPage();
    }

    #[On('end-date-changed')]
    public function updateEndDate($endDate)
    {
        $this->endDate = $endDate;
        $this->resetPage();
    }

    #[Computed]
    public function journals()
    {
        $wh_accounts = ChartOfAccount::where('warehouse_id', $this->warehouse)->pluck('id')->toArray();

        $journals = Journal::with([
            'cred:id,acc_name,account_id,warehouse_id',
            'debt:id,acc_name,account_id,warehouse_id',
            'user:id,name',
            'warehouse:id,name'
        ])
            ->where(function ($query) use ($wh_accounts) {
                $query->whereIn('cred_code', $wh_accounts)
                    ->orWhereIn('debt_code', $wh_accounts);
            })
            ->whereDate('date_issued', $this->endDate)
            ->where('trx_type', 'Mutasi Kas')
            ->when($this->search, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->whereHas('cred', fn($q) => $q->where('acc_name', 'like', "%{$this->search}%"))
                        ->orWhereHas('debt', fn($q) => $q->where('acc_name', 'like', "%{$this->search}%"))
                        ->orWhere('invoice', 'like', "%{$this->search}%")
                        ->orWhere('description', 'like', "%{$this->search}%");
                });
            })
            ->latest()
            ->paginate($this->perPage, ['*'], 'journalPage');

        return $journals;
    }

    public function destroy($id)
    {
        $journal = Journal::find($id);
        $transactionsExist = $journal->transaction()->exists();
        // if ($transactionsExist) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Journal cannot be deleted because it has transactions'
        //     ]);
        // }
        if (Carbon::parse($journal->date_issued)->lt(Carbon::now()->startOfDay()) && auth()->user()->role->role !== 'Super Admin') {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus journal. Tanggal journal tidak boleh lebih kecil dari tanggal sekarang.'
            ], 400);
        }


        $log = new LogActivity();
        DB::beginTransaction();
        try {
            $journal->delete();
            if ($transactionsExist) {
                $journal->transaction()->delete();
            }

            $log->create([
                'user_id' => auth()->user()->id,
                'warehouse_id' => $journal->warehouse_id,
                'activity' => 'Deleted Journal',
                'description' => 'Deleted Journal with ID: ' . $journal->id . ' (' . $journal->description . ' from ' . $journal->cred->acc_name . ' to ' . $journal->debt->acc_name . ' with amount: ' . number_format($journal->amount, 0, ',', '.') . ' and fee amount: ' . number_format($journal->fee_amount, 0, ',', '.') . ')',
            ]);

            if ($journal->date_issued) {
                try {
                    $dateIssued = Carbon::parse($journal->date_issued);

                    if ($dateIssued->lt(Carbon::now()->startOfDay())) {
                        $this->_updateBalancesDirectly($dateIssued);
                    }
                } catch (\Exception $e) {
                    Log::warning("Invalid date_issued format: {$journal->date_issued}");
                }
            }

            DB::commit();
            $this->dispatch('journal-deleted', $journal);
        } catch (\Exception $e) {
            DB::rollBack();
            // Flash an error message
            Log::error($e->getMessage());
        }
    }

    #[On('journal-created')]
    public function render()
    {
        return view('livewire.dashboard.mutation-history', [
            'accounts' => ChartOfAccount::whereIn('account_id', [1, 2])->where('warehouse_id', $this->warehouse)->get()
        ]);
    }
}
