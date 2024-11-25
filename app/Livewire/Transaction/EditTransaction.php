<?php

namespace App\Livewire\Transaction;

use App\Models\Journal;
use App\Models\Product;
use App\Models\Receivable;
use Livewire\Component;
use App\Models\Transaction;
use Livewire\Attributes\On;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        if ($this->serial == null) {
            return abort(404);
        }

        $transaction = Transaction::where('serial_number', $this->serial)->get();
        if ($transaction->isEmpty()) {
            return to_route('transaction')->with('error', 'Transaction not found or deleted.');
        }
        // dd($transaction);
        $this->serial = $transaction->first()->serial_number;
        $this->invoice = $transaction->first()->invoice;

        foreach ($transaction as $item) {
            $this->quantities[$item->product_id] = $item->transaction_type == 'Purchase' ? $item->quantity : -$item->quantity;
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
        //Check if the product is already exist
        $checkProduct = Transaction::where('product_id', $productId)->where('invoice', $trx->invoice)->exists();
        if ($checkProduct) {
            session()->flash('error', 'Update Failed! Product already selected');
            return; // Exit the method if the product is already selected
        }

        $journals = Journal::where('invoice', $trx->invoice)
            ->where('description', 'like', '%' . $trx->product->code . '%')
            ->get();

        $receivable = Receivable::where('invoice', $trx->invoice)
            ->where('description', 'like', '%' . $trx->product->code . '%')
            ->first();

        try {
            DB::beginTransaction();
            // dd($journal->description);
            $receivable->description = 'Penjualan Barang (Code:' . $product->code . ') ' . $product->name . ' (' . $trx->quantity . 'Pcs)';
            // $journal->amount = $trx->transaction_type == 'Purchase' ? $trx->cost * $trx->quantity : $trx->price * $trx->quantity;

            $trx->product_id = $productId;
            // $trx->quantity = $this->quantities[$id];
            $trx->save();
            foreach ($journals as $journal) {
                $journal->description = 'Pembelian Barang (Code:' . $product->code  . ') ' . $product->name . ' (' . $trx->quantity . 'Pcs)';
                $journal->save(); // Save each updated journal
            }
            $receivable->save();


            DB::commit();

            $this->dispatch('TransactionUpdated', $trx->invoice);

            session()->flash('success', 'Transaction updated successfully.');
            redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Transaction failed.' . $e->getMessage());
        }
    }

    public function updateQuantity($id, $quantity)
    {
        if ($quantity == 0) {
            session()->flash('error', 'Quantity must be greater than 0. Use delete button instead.');
            return;
        }

        $trx = Transaction::find($id);
        $journal = Journal::where('invoice', $trx->invoice)
            ->where('description', 'like', '%' . $trx->product->code . '%')
            ->first();

        $journalHpp = Journal::where('invoice', $trx->invoice)
            ->where('description', 'like', '%' . $trx->product->code . '%')
            ->where('debt_code', '50100-001')
            ->first();

        $receivable = Receivable::where('invoice', $trx->invoice)
            ->where('description', 'like', '%' . $trx->product->code . '%')
            ->where('payment_nth', 0)
            ->first();

        $journalHpp->amount = $trx->cost * $quantity;
        $salePrice = $trx->transaction_type == 'Purchase' ? $trx->cost * $quantity : $trx->price * $quantity;
        $receivable->bill_amount = $salePrice;

        $journal->amount = $salePrice;
        $trx->quantity = -$quantity;

        $trx->save();
        $journal->save();
        $journalHpp->save();

        if ($receivable->exists()) {
            $receivable->save();
        }
        $this->dispatch('TransactionUpdated', $trx->invoice);

        session()->flash('success', 'Transaction updated successfully.');
    }

    public function updatePrice($id, $price)
    {
        // Find the transaction
        $trx = Transaction::find($id);
        if (!$trx) {
            session()->flash('error', 'Transaction not found.');
            return;
        }

        // Find the main journal entry
        $journal = Journal::where('invoice', $trx->invoice)
            ->where('description', 'like', '%' . $trx->product->code . '%')
            ->first();

        if (!$journal) {
            session()->flash('error', 'Journal entry not found.');
            return;
        }

        // Find the HPP journal entry
        $journalHpp = Journal::where('invoice', $trx->invoice)
            ->where('description', 'like', '%' . $trx->product->code . '%')
            ->where('debt_code', '50100-001')
            ->first();

        if (!$journalHpp) {
            session()->flash('error', 'HPP journal entry not found.');
            return;
        }

        $receivable = Receivable::where('invoice', $trx->invoice)
            ->where('description', 'like', '%' . $trx->product->code . '%')
            ->where('payment_nth', 0)
            ->first();

        try {
            if ($trx->transaction_type == 'Purchase') {
                $quantity = $trx->quantity;
            } else {
                $quantity = -$trx->quantity;
            }

            // Update the journal description
            $journal->description = 'Pembelian Barang (Code:' . $trx->product->code . ') ' . $trx->product->name . ' (' . $quantity . 'Pcs)';
            // Update the journal amounts
            $journal->amount = $price * $quantity;
            $journalHpp->amount = $trx->cost * $quantity;
            $receivable->bill_amount = $price * $quantity;

            // Update transaction price and cost
            if ($trx->transaction_type == 'Purchase') {
                $trx->cost = $price;
            } else {
                $trx->price = $price;
            }

            // Save changes to database
            $trx->save();
            $journal->save();
            $journalHpp->save();
            $receivable->save();

            // Dispatch event for UI updates
            $this->dispatch('TransactionUpdated', $trx->invoice);

            session()->flash('success', 'Transaction updated successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to update transaction: ' . $e->getMessage());
            session()->flash('error', 'Failed to update transaction. Please try again.');
        }
    }


    public function removeItem($id)
    {
        $trx = Transaction::find($id);
        $checkProduct = Transaction::where('invoice', $trx->invoice)->count();
        if ($checkProduct == 1) {
            session()->flash('error', 'Cannot delete all items in transaction. Use Void button instead.');
            return;
        }

        $trx->delete();
        $journal = Journal::where('invoice', $trx->invoice)
            ->where('description', 'like', '%' . $trx->product->code . '%')
            ->delete();

        $this->dispatch('TransactionUpdated', $trx->invoice);

        session()->flash('success', 'Transaction deleted successfully.');
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

    public function voidTransaction()
    {
        Transaction::where('serial_number', $this->serial)->delete();
        Journal::where('serial_number', $this->serial)->delete();
        Receivable::where('invoice', $this->invoice)->delete();
        redirect()->to('transaction')->with('success', 'Transaction voided successfully.');
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
