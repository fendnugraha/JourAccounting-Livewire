<?php

namespace App\Livewire\Transaction;

use App\Models\ChartOfAccount;
use Carbon\Carbon;
use App\Models\Journal;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;

class CreateDeposit extends Component
{
    public $price, $cost, $description, $date_issued;

    protected $rules = [
        'price' => 'required',
        'cost' => 'required',
    ];

    #[On('journal-created')]
    public function mount()
    {
        $this->date_issued = Carbon::now()->format('Y-m-d H:i');
    }

    public function createDeposit()
    {
        $price = $this->price;
        $cost = $this->cost;

        $description = $this->description ?? "Penjualan Pulsa Dll";
        $fee = $price - $cost;
        $invoice = Journal::invoice_journal();

        DB::beginTransaction();
        try {
            Journal::create([
                'invoice' => $invoice,  // Menggunakan metode statis untuk invoice
                'date_issued' => $this->date_issued ?? now(),
                'debt_code' => ChartOfAccount::INVENTORY,
                'cred_code' => ChartOfAccount::INVENTORY,
                'amount' => $cost,
                'fee_amount' => $fee,
                'trx_type' => 'Deposit',
                'description' => $description,
                'user_id' => auth()->user()->id,
                'warehouse_id' => auth()->user()->roles->warehouse_id
            ]);

            DB::commit();

            $this->dispatch('journal-created', $invoice);

            $this->reset();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.transaction.create-deposit');
    }
}
