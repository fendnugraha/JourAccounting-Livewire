<?php

namespace App\Livewire\Transaction;

use Carbon\Carbon;
use App\Models\Journal;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\ChartOfAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;

class CreateTransfer extends Component
{
    #[Validate('required|date')]
    public $date_issued;

    #[Validate('required|exists:chart_of_accounts,id')]
    public $cred_code;

    #[Validate('required|numeric|min:0')]
    public $amount;

    #[Validate('required|numeric|min:0')]
    public $fee_amount;

    public $description;

    #[Validate('required|regex:/^[a-zA-Z0-9\s]+$/|min:3|max:255')]
    public $custName;

    #[On('journal-created')]
    public function mount()
    {
        $this->date_issued = date('Y-m-d H:i');
    }

    public function createTransfer()
    {
        $description = $this->description ? $this->description . ' - ' . strtoupper($this->custName) : 'Transfer Dana - ' . strtoupper($this->custName);

        if (Carbon::parse($this->date_issued)->lt(Carbon::now()->startOfDay()) && auth()->user()->role->role !== 'Super Admin') {
            return;
        }

        $this->validate();
        DB::beginTransaction();
        try {
            $journal = Journal::create([
                'invoice' => Journal::invoice_journal(),  // Menggunakan metode statis untuk invoice
                'date_issued' => $this->date_issued ?? now(),
                'debt_code' => auth()->user()->roles->warehouse->chart_of_account_id,
                'cred_code' => $this->cred_code,
                'amount' => $this->amount,
                'fee_amount' => $this->fee_amount,
                'trx_type' => 'Transfer Uang',
                'description' => $description ?? 'Transfer Uang',
                'user_id' => auth()->user()->id,
                'warehouse_id' => auth()->user()->roles->warehouse_id
            ]);

            if ($this->date_issued) {
                try {
                    $dateIssued = Carbon::parse($this->date_issued);

                    if ($dateIssued->lt(Carbon::now()->startOfDay())) {
                        Journal::_updateBalancesDirectly($dateIssued);
                    }
                } catch (\Exception $e) {
                    Log::warning("Invalid date_issued format: {$this->date_issued}");
                }
            }

            DB::commit();

            $this->dispatch('journal-created', $journal);

            $this->reset(['date_issued', 'amount', 'fee_amount', 'description', 'custName']);

            session()->flash('success', 'Journal created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            session()->flash('error', 'Failed to create journal. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.transaction.create-transfer', [
            "accounts" => ChartOfAccount::where('account_id', 2)->where('warehouse_id', Auth::user()->roles->warehouse_id)->get()
        ]);
    }
}
