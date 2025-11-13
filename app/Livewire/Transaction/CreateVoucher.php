<?php

namespace App\Livewire\Transaction;

use Carbon\Carbon;
use App\Models\Journal;
use App\Models\Product;
use Livewire\Component;
use App\Models\Transaction;
use Livewire\Attributes\On;
use App\Models\ChartOfAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateVoucher extends Component
{
    public $date_issued;
    public $product_id;
    public $quantity = 1;
    public $description;
    public $price = 0;

    protected $rules = [
        'date_issued' => 'required',
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|numeric',
        'price' => 'required|numeric',
    ];

    #[On('journal-created')]
    public function mount()
    {
        $this->date_issued = Carbon::now()->format('Y-m-d H:i');
    }

    public function createVoucher()
    {
        $this->validate();
        // $modal = $this->modal * $this->quantity;
        $price = $this->price * $this->quantity;
        $cost = Product::find($this->product_id)->cost;
        $modal = $cost * $this->quantity;

        $description = $this->description ?? "Penjualan Voucher & SP";
        $fee = $price - $modal;
        $invoice = Journal::invoice_journal();

        DB::beginTransaction();
        try {
            $journal = Journal::create([
                'invoice' => $invoice,  // Menggunakan metode statis untuk invoice
                'date_issued' => $this->date_issued ?? now(),
                'debt_code' => ChartOfAccount::INVENTORY,
                'cred_code' => ChartOfAccount::INVENTORY,
                'amount' => $modal,
                'fee_amount' => $fee,
                'trx_type' => 'Voucher & SP',
                'description' => $description,
                'user_id' => auth()->user()->id,
                'warehouse_id' => auth()->user()->roles->warehouse_id
            ]);

            Transaction::create([
                'date_issued' => $this->date_issued ?? now(),
                'invoice' => $invoice,
                'product_id' => $this->product_id,
                'quantity' => -$this->quantity,
                'price' => $this->price,
                'cost' => $cost,
                'transaction_type' => 'Sales',
                'contact_id' => 1,
                'warehouse_id' => auth()->user()->roles->warehouse_id,
                'user_id' => auth()->user()->id
            ]);

            // $sold = Product::find($this->product_id)->sold + $this->quantity;
            // Product::find($this->product_id)->update(['sold' => $sold]);

            DB::commit();

            $this->dispatch('journal-created', id: $journal->id);

            $this->reset();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return;
        }
    }

    public function updatedProductId($value)
    {
        $product = Product::find($value);
        $this->price = $product->price;
    }
    public function render()
    {
        return view('livewire.transaction.create-voucher', [
            "products" => Product::orderBy('name')->get()
        ]);
    }
}
