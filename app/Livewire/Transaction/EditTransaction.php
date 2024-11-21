<?php

namespace App\Livewire\Transaction;

use App\Models\Journal;
use App\Models\Product;
use Livewire\Component;
use App\Models\Transaction;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;

class EditTransaction extends Component
{
    public $serial;
    public $invoice;
    public $quantities = [];
    public $prices = [];
    public $productsSelected = [];
    public $discountAndFeeData;

    #[On('TransactionUpdated')]
    public function mount()
    {
        $transaction = Transaction::where('serial_number', $this->serial)->get();
        // dd($transaction);
        $this->serial = $transaction->first()->serial_number;
        $this->invoice = $transaction->first()->invoice;

        foreach ($transaction as $item) {
            $this->quantities[$item->product_id] = $item->quantity;
            $this->prices[$item->product_id] = $item->transaction_type == 'Purchase' ? $item->cost : $item->price;
            $this->productsSelected[$item->product_id] = $item->product_id;
        }

        // dd($this->quantities);
        // Store discount and fee data
        $this->discountAndFeeData = $this->checkDiscountAndFee()->first();
    }
    public function updateProduct($id, $productId)
    {
        $product = Product::find($productId);
        // dd($id, $productId);
        $trx = Transaction::find($id);
        // dd($trx);

        $journal = Journal::where('invoice', $trx->invoice)
            ->where('description', 'like', '%' . $trx->product->code . '%')
            ->first();

        try {
            DB::beginTransaction();
            // dd($journal->description);
            $journal->description = 'Pembelian Barang (Code:' . $product->code . ') ' . $product->name . ' (' . $trx->quantity . 'Pcs)';
            $journal->amount = $trx->transaction_type == 'Purchase' ? $trx->cost * $trx->quantity : $trx->price * $trx->quantity;

            $trx->product_id = $productId;
            $trx->quantity = $this->quantities[$id];
            $trx->save();
            $journal->save();


            DB::commit();

            $this->dispatch('TransactionUpdated', $trx->invoice);

            session()->flash('success', 'Transaction updated successfully.');
            redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Transaction failed.' . $e->getMessage());
        }
    }

    public function checkDiscountAndFee()
    {
        return Journal::where('serial_number', $this->serial)
            ->where(function ($query) {
                $query->where('debt_code', '60111-001')
                    ->orWhere('cred_code', '40200-001');
            })->get();
    }

    public function getDiscountAndFeeProperty()
    {
        return $this->discountAndFeeData->amount ?? 0;
    }

    public function getTotalAmountProperty()
    {
        $total = 0;

        foreach ($this->quantities as $id => $quantity) {
            $price = $this->prices[$id] ?? 0;
            $total += $quantity * $price;
        }

        return $total;
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.transaction.edit-transaction', [
            'title' => 'Edit Transaction ' . $this->invoice,
            'transaction' => Transaction::where('serial_number', $this->serial)->get(),
            'discountAndFee' => $this->discountAndFee,
            'products' => Product::all(),
            'totalAmount' => $this->getTotalAmountProperty()
        ]);
    }
}
