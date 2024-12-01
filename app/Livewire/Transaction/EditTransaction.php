<?php

namespace App\Livewire\Transaction;

use App\Models\Journal;
use App\Models\Payable;
use App\Models\Product;
use Livewire\Component;
use App\Models\Receivable;
use App\Models\Transaction;
use Livewire\Attributes\On;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use function PHPSTORM_META\type;

class EditTransaction extends Component
{
    public $serial;
    public $invoice;
    public $quantities = [];
    public $prices = [];
    public $productsSelected = [];
    public $discountAndFeeData;
    public $transaction_type;
    public $quantity;
    public $price;
    public $product_id;

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
        $this->transaction_type = $transaction->first()->transaction_type;

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
        // $product = Product::find($productId);
        // dd($id, $productId);
        $trx = Transaction::find($id);
        // dd($trx);
        //Check if the product is already exist
        $checkProduct = Transaction::where('product_id', $productId)->where('invoice', $trx->invoice)->exists();
        if ($checkProduct) {
            session()->flash('error', 'Update Failed! Product already selected');
            return; // Exit the method if the product is already selected
        }

        // $journals = Journal::where('invoice', $trx->invoice)
        //     ->where('description', 'like', '%' . $trx->product->code . '%')
        //     ->get();

        // $receivable = Receivable::where('invoice', $trx->invoice)
        //     ->where('description', 'like', '%' . $trx->product->code . '%')
        //     ->first();

        // $payable = Payable::where('invoice', $trx->invoice)
        //     ->where('description', 'like', '%' . $trx->product->code . '%')
        //     ->first();

        try {
            DB::beginTransaction();
            // dd($journal->description);
            // $journal->amount = $trx->transaction_type == 'Purchase' ? $trx->cost * $trx->quantity : $trx->price * $trx->quantity;
            // $description = $this->transaction_type = "Purchase" ? 'Pembelian Barang (Code:' . $product->code . ') ' . $product->name : 'Penjualan Barang (Code:' . $product->code . ') ' . $product->name;
            if ($trx->transaction_type == "Purchase") {
                Product::updateStock($trx->product_id, -$trx->quantity, $trx->warehouse_id);
                Product::updateCost($trx->product_id, [['invoice', '!=', $trx->invoice]]);
            } else {
                Product::updateStock($trx->product_id, $trx->quantity, $trx->warehouse_id);
            }

            $trx->product_id = $productId;
            // $trx->quantity = $this->quantities[$id];

            // foreach ($journals as $journal) {
            //     $journal->description = $description . ' (' . $trx->quantity . 'Pcs)';
            //     $journal->save(); // Save each updated journal
            // }

            // if ($receivable || $payable) {


            //     if ($this->transaction_type == "Purchase") {
            //         $payable->description = 'Pembelian Barang (Code:' . $product->code . ') ' . $product->name . ' (' . $trx->quantity . 'Pcs)';
            //         $payable->save();
            //     } else {
            //         $receivable->description = 'Penjualan Barang (Code:' . $product->code . ') ' . $product->name . ' (' . $trx->quantity . 'Pcs)';
            //         $receivable->save();
            //     }
            // }

            $trx->save();

            if ($trx->transaction_type == "Purchase") {
                Product::updateStock($productId, $trx->quantity, $trx->warehouse_id);
                Product::updateCost($productId, []);
            } else {
                Product::updateStock($productId, -$trx->quantity, $trx->warehouse_id);
            }


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
            ->first();

        $journalHpp = Journal::where('invoice', $trx->invoice)
            ->where('debt_code', '50100-001')
            ->first();

        $receivable = Receivable::where('invoice', $trx->invoice)
            ->where('payment_nth', 0)
            ->first();

        $payable = Payable::where('invoice', $trx->invoice)
            ->where('payment_nth', 0)
            ->first();


        try {
            DB::beginTransaction();
            if ($trx->transaction_type == 'Purchase') {
                Product::updateStock($trx->product_id, -$trx->quantity, $trx->warehouse_id);
            } else {
                Product::updateStock($trx->product_id, $trx->quantity, $trx->warehouse_id);
            }

            $trx->quantity = $trx->transaction_type == 'Purchase' ? $quantity : -$quantity;
            $trx->save();


            if ($trx->transaction_type == 'Purchase') {
                Product::updateStock($trx->product_id, $quantity, $trx->warehouse_id);
                Product::updateCost($trx->product_id, []);
            } else {
                Product::updateStock($trx->product_id, -$quantity, $trx->warehouse_id);
            }

            $transaction = Transaction::select(
                'product_id',
                'transaction_type',
                DB::raw('SUM(cost * quantity) as total_cost'),
                DB::raw('SUM(price * quantity) as total_price')
            )
                ->where('serial_number', $trx->serial_number)
                ->groupBy('product_id', 'transaction_type')
                ->get();

            // Now, you can sum 'total_cost' after the query result is retrieved
            $totalCost = $transaction->sum('total_cost');
            $totalPrice = $transaction->sum('total_price');

            if ($trx->transaction_type == 'Sales') {
                $journalHpp->amount = $totalCost;
                $journalHpp->save();
            }

            $salePrice = $transaction->first()->transaction_type == 'Purchase' ? $totalCost : $totalPrice;

            $journal->amount = $salePrice;

            if ($receivable || $payable) {
                if ($trx->transaction_type == 'Purchase') {
                    $payable->bill_amount = $salePrice;
                    $payable->save();
                } else {
                    $receivable->bill_amount = $salePrice;
                    $receivable->save();
                }
            }

            $journal->save();

            DB::commit();

            session()->flash('success', 'Transaction updated successfully.');
            $this->dispatch('TransactionUpdated', $trx->invoice);
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Transaction failed.' . $e->getMessage());
        }
    }

    public function updatePrice($id, $price)
    {
        // Find the transaction
        $trx = Transaction::find($id);
        if (!$trx) {
            session()->flash('error', 'Transaction not found.');
            return;
        }

        $journal = Journal::where('invoice', $trx->invoice)
            ->first();

        $journalHpp = Journal::where('invoice', $trx->invoice)
            ->where('debt_code', '50100-001')
            ->first();

        $receivable = Receivable::where('invoice', $trx->invoice)
            ->where('payment_nth', 0)
            ->first();

        $payable = Payable::where('invoice', $trx->invoice)
            ->where('payment_nth', 0)
            ->first();

        try {
            DB::beginTransaction();

            if ($trx->transaction_type == 'Purchase') {
                $trx->cost = $price;
            } else {
                $trx->price = $price;
            }

            $trx->save();

            $transaction = Transaction::select(
                'product_id',
                'transaction_type',
                DB::raw('SUM(cost * quantity) as total_cost'),
                DB::raw('SUM(price * quantity) as total_price')
            )
                ->where('serial_number', $trx->serial_number)
                ->groupBy('product_id', 'transaction_type')
                ->get();

            // Now, you can sum 'total_cost' after the query result is retrieved
            $totalCost = $transaction->sum('total_cost');
            $totalPrice = $transaction->sum('total_price');

            if ($trx->transaction_type == 'Sales') {
                $journalHpp->amount = -$totalCost;
                $journalHpp->save();
            }

            $salePrice = $transaction->first()->transaction_type == 'Purchase' ? $totalCost : -$totalPrice;

            $journal->amount = $salePrice;

            if ($receivable || $payable) {
                if ($trx->transaction_type == 'Purchase') {
                    $payable->bill_amount = $salePrice;
                    $payable->save();
                } else {
                    $receivable->bill_amount = $salePrice;
                    $receivable->save();
                }
            }

            $journal->save();

            Product::updateCost($trx->product_id);

            DB::commit();
            // Dispatch event for UI updates
            $this->dispatch('TransactionUpdated', $trx->invoice);

            session()->flash('success', 'Transaction updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update transaction: ' . $e->getMessage());
            session()->flash('error', 'Failed to update transaction. Please try again.' . $e->getMessage());
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

        $checkPaymentReceivable = Receivable::where('invoice', $trx->invoice)->sum('payment_amount');
        $checkPaymentPayable = Payable::where('invoice', $trx->invoice)->sum('payment_amount');

        if ($checkPaymentReceivable > 0 || $checkPaymentPayable > 0) {
            session()->flash('error', 'Cannot delete transaction. Please void payment first.');
            return;
        }

        $journal = Journal::where('invoice', $trx->invoice)
            ->first();

        $journalHpp = Journal::where('invoice', $trx->invoice)
            ->where('debt_code', '50100-001')
            ->first();

        $receivable = Receivable::where('invoice', $trx->invoice)
            ->where('payment_nth', 0)
            ->first();

        $payable = Payable::where('invoice', $trx->invoice)
            ->where('payment_nth', 0)
            ->first();

        try {
            DB::beginTransaction();
            Product::updateCost($trx->product_id, [['invoice', '!=', $trx->invoice]]);

            $trx->delete();
            if ($trx->transaction_type == 'Purchase') {
                Product::updateStock($trx->product_id, -$trx->quantity, $trx->warehouse_id);
            } else {
                Product::updateStock($trx->product_id, $trx->quantity, $trx->warehouse_id);
            }

            $transaction = Transaction::select(
                'product_id',
                'transaction_type',
                DB::raw('SUM(cost * quantity) as total_cost'),
                DB::raw('SUM(price * quantity) as total_price')
            )
                ->where('serial_number', $trx->serial_number)
                ->groupBy('product_id', 'transaction_type')
                ->get();

            // Now, you can sum 'total_cost' after the query result is retrieved
            $totalCost = $transaction->sum('total_cost');
            $totalPrice = $transaction->sum('total_price');

            if ($trx->transaction_type == 'Sales') {
                $journalHpp->amount = -$totalCost;
                $journalHpp->save();
            }

            $salePrice = $transaction->first()->transaction_type == 'Purchase' ? $totalCost : -$totalPrice;

            $journal->amount = $salePrice;

            if ($receivable || $payable) {
                if ($trx->transaction_type == 'Purchase') {
                    $payable->bill_amount = $salePrice;
                    $payable->save();
                } else {
                    $receivable->bill_amount = $salePrice;
                    $receivable->save();
                }
            }

            $journal->save();

            DB::commit();

            session()->flash('success', 'Transaction deleted successfully.');

            $this->dispatch('TransactionUpdated', $trx->invoice);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete transaction: ' . $e->getMessage());
            session()->flash('error', 'Failed to delete transaction. Please try again.');
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

    public function voidTransaction($id)
    {
        $trx = Transaction::find($id);
        $checkPaymentReceivable = Receivable::where('invoice', $trx->invoice)->sum('payment_amount');
        $checkPaymentPayable = Payable::where('invoice', $trx->invoice)->sum('payment_amount');

        if ($checkPaymentReceivable > 0 || $checkPaymentPayable > 0) {
            session()->flash('error', 'Cannot void transaction. Please void payment first.');
            return;
        }
        $transaction = Transaction::where('serial_number', $this->serial)->get();

        try {
            DB::beginTransaction();

            Journal::where('serial_number', $this->serial)->delete();
            if ($trx->transaction_type == 'Purchase') {
                Payable::where('invoice', $this->invoice)->delete();
            } else { // 'Sales'
                Receivable::where('invoice', $this->invoice)->delete();
            }

            if ($trx->transaction_type == 'Sales') {
                foreach ($transaction as $item) {
                    Product::updateCost($item->product_id, [['invoice', '!=', $trx->invoice]]);
                    Product::updateStock($item->product_id, $item->quantity, $item->warehouse_id);
                    $item->delete();
                }
            } else {
                foreach ($transaction as $item) {
                    Product::updateCost($item->product_id, [['invoice', '!=', $trx->invoice]]);
                    Product::updateStock($item->product_id, -$item->quantity, $item->warehouse_id);
                    $item->delete();
                }
            }

            DB::commit();

            redirect()->to('transaction')->with('success', 'Transaction voided successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to void transaction: ' . $e->getMessage());

            session()->flash('error', 'Failed to void transaction. Please try again.' . $e->getMessage());
        }
    }

    public function addItem()
    {
        $trx = Transaction::where('serial_number', $this->serial)->get();
        if (!$trx) {
            session()->flash('error', 'Transaction not found.');
            return;
        }

        if ($trx->pluck('product_id')->contains($this->product_id)) {
            session()->flash('error', 'Product already exists.');
            return;
        }

        $this->validate([
            'product_id' => 'required',
            'quantity' => 'required|numeric',
            'price' => 'required|numeric',
        ]);
        $checkProduct = Product::find($this->product_id);
        if ($trx->first()->transaction_type == 'Sales') {
            $quantity = -$this->quantity;
            $type = 'Sales';
            $harga = $this->price;
            $cost = $checkProduct->cost;
        } else {
            $quantity = $this->quantity;
            $type = 'Purchase';
            $harga = 0;
            $cost = $this->price;
        }

        $journal = Journal::where('invoice', $trx->first()->invoice)
            ->first();

        $journalHpp = Journal::where('invoice', $trx->first()->invoice)
            ->where('debt_code', '50100-001')
            ->first();

        $receivable = Receivable::where('invoice', $trx->first()->invoice)
            ->where('payment_nth', 0)
            ->first();

        $payable = Payable::where('invoice', $trx->first()->invoice)
            ->where('payment_nth', 0)
            ->first();

        try {
            DB::beginTransaction();
            $transactiontb = new Transaction([
                'date_issued' => $trx->first()->date_issued,
                'invoice' => $trx->first()->invoice, // Ensure $invoice is defined
                'product_id' => $this->product_id,
                'quantity' => $quantity,
                'price' => $harga,
                'cost' => $cost,
                'transaction_type' => $type,
                'contact_id' => $trx->first()->contact_id,
                'warehouse_id' => $trx->first()->warehouse_id,
                'user_id' => $trx->first()->user_id,
                'serial_number' => $trx->first()->serial_number,
            ]);

            $transactiontb->save();

            $transaction = Transaction::select(
                'product_id',
                'transaction_type',
                DB::raw('SUM(cost * quantity) as total_cost'),
                DB::raw('SUM(price * quantity) as total_price')
            )
                ->where('serial_number', $trx->first()->serial_number)
                ->groupBy('product_id', 'transaction_type')
                ->get();

            // Now, you can sum 'total_cost' after the query result is retrieved
            $totalCost = $transaction->sum('total_cost');
            $totalPrice = $transaction->sum('total_price');

            if ($trx->first()->transaction_type == 'Sales') {
                $journalHpp->amount = -$totalCost;
                $journalHpp->save();
            }

            $salePrice = $transaction->first()->transaction_type == 'Purchase' ? $totalCost : -$totalPrice;

            $journal->amount = $salePrice;

            if ($receivable || $payable) {
                if ($trx->first()->transaction_type == 'Purchase') {
                    $payable->bill_amount = $salePrice;
                    $payable->save();
                } else {
                    $receivable->bill_amount = $salePrice;
                    $receivable->save();
                }
            }

            $journal->save();

            $qty = $trx->first()->transaction_type == 'Sales' ? -$this->quantity : $this->quantity;
            Product::updateStock($this->product_id, $qty, $trx->first()->warehouse_id);
            Product::updateCost($this->product_id);

            DB::commit();

            $this->product_id = null;
            $this->quantity = 0;
            $this->price = 0;

            session()->flash('success', 'Item added successfully.');

            $this->dispatch('TransactionUpdated', $trx->first()->invoice);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to add item: ' . $e->getMessage());

            session()->flash('error', 'Failed to add item. Please try again.' . $e->getMessage());
        }
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
