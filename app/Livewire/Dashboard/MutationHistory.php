<?php

namespace App\Livewire\Dashboard;

use Carbon\Carbon;
use App\Models\Journal;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\ChartOfAccount;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Log;

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
        // LOGIKA PENTING: Langsung update properti dan reset page.
        // Livewire akan melihat properti publik $this->warehouse berubah 
        // dan akan menjalankan Computed Property `journals()`.
        Log::info('MutationHistory: Event "warehouse-changed" received and applying update.', [
            'old_warehouse' => $this->warehouse,
            'new_warehouse' => $warehouse
        ]);

        $this->warehouse = $warehouse;
        $this->resetPage();
        // Tidak perlu cek `if ($this->warehouse != $warehouse)` karena 
        // Livewire sudah cukup pintar untuk melakukan re-render.
    }

    #[Computed]
    public function journals()
    {
        Log::debug('MutationHistory: Computing journals.', ['warehouse_id' => $this->warehouse, 'endDate' => $this->endDate]);

        if (!$this->warehouse) {
            Log::warning('MutationHistory: Warehouse ID is null. Returning empty set.');
            return Journal::whereRaw('1 = 0')->paginate($this->perPage, ['*'], 'journalPage');
        }

        $wh_accounts = ChartOfAccount::where('warehouse_id', $this->warehouse)->pluck('id')->toArray();

        if (empty($wh_accounts)) {
            Log::info('MutationHistory: No ChartOfAccount found for the warehouse. Returning empty set.');
            return Journal::whereRaw('1 = 0')->paginate($this->perPage, ['*'], 'journalPage');
        }

        // QUERY LOGIC
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

        Log::debug('MutationHistory: Query executed successfully.', ['results_count' => $journals->count(), 'total_items' => $journals->total()]);

        return $journals;
    }

    #[On('journal-created')]
    public function render()
    {
        return view('livewire.dashboard.mutation-history');
    }
}
