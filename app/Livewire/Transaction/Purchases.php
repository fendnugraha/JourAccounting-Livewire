<?php

namespace App\Livewire\Transaction;

use App\Models\Journal;
use App\Models\Product;
use Livewire\Component;
use App\Models\ChartOfAccount;
use App\Models\Transaction;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Purchases extends Component
{
    public $search = '';
    public $cart = [];
    public $total = 0;
    public $qty = 1;
    public $product_id;
    public $payment = 0;
    public $account;

    public function mount()
    {
        // Load the cart from the session when the component is mounted
        $this->cart = session()->get('cart', []);
        $this->payment = collect($this->cart)->sum(fn($item) => $item['cost'] * $item['qty']);
    }
    public function addToCart($product_id)
    {
        if (isset($this->cart[$product_id])) {
            $this->cart[$product_id]['qty'] += $this->qty;
        } else {
            $product = Product::find($product_id);
            $this->cart[$product_id] = [
                'id' => $product->id,
                'name' => $product->name,
                'cost' => $product->cost,
                'cost' => $product->cost,
                'qty' => $this->qty,
            ];
        }

        $this->total += $this->cart[$product_id]['cost'] * $this->cart[$product_id]['qty'];
        $this->dispatch('cartUpdated');

        $this->updateSession();
    }

    public function removeFromCart($productId)
    {
        unset($this->cart[$productId]);
        $this->updateSession();
    }

    public function updateQty($productId, $qty)
    {
        if ($qty > 0) {
            $this->cart[$productId]['qty'] = $qty;
        } else {
            $this->removeFromCart($productId);
        }

        $this->updateSession();
    }

    public function updateCost($productId, $cost)
    {
        $this->cart[$productId]['cost'] = $cost;
        $this->updateSession();
    }

    private function updateSession()
    {
        // Save the cart back to the session
        session()->put('cart', $this->cart);
    }

    public function clearCart()
    {
        $this->cart = []; // Clear the cart array
        session()->forget('cart'); // Remove the cart data from the session
    }

    public function storeCart()
    {
        $journal = new Journal();
        $journal->invoice = $journal->sales_journal();

        // dd($this->cart);

        try {
            DB::beginTransaction();

            foreach ($this->cart as $item) {
                $product = Product::find($item['id']);
                if (!$product) {
                    continue; // Skip if the product is not found
                }

                $initial_stock = $product->end_stock;
                $initial_cost = $product->cost;
                $initTotal = $initial_stock * $initial_cost;

                // Create a new journal entry
                $journal = new Journal();
                $journal->date_issued = date('Y-m-d H:i');
                $journal->invoice = $journal->invoice; // Ensure $invoice is defined
                $journal->debt_code = "10600-001";
                $journal->cred_code = $this->account;
                $journal->amount = $initTotal; // Ensure $modal is defined
                $journal->fee_amount = 0; // Ensure $fee is defined
                $journal->description = "Pembelian Barang";
                $journal->trx_type = 'Transaction';
                $journal->user_id = Auth::user()->id;
                $journal->warehouse_id = Auth::user()->warehouse_id;
                $journal->save();

                // Update the product stock
                $product->init_stock += $item['qty'];
                $product->end_stock += $item['qty'];
                $product->save();

                $transaction = new Transaction();
                $transaction->date_issued = date('Y-m-d H:i');
                $transaction->invoice = $journal->invoice; // Ensure $invoice is defined
                $transaction->product_id = $product->id;
                $transaction->quantity = -$item['qty'];
                $transaction->price = 0;
                $transaction->cost = $item['cost'];
                $transaction->transaction_type = 'Purchases';
                $transaction->warehouse_id = Auth::user()->warehouse_id;
                $transaction->user_id = Auth::user()->id;
                $transaction->save();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }

        $this->clearCart();
        return redirect('/transaction/purchases')->with('success', 'Pembelian Berhasil');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.transaction.purchases', [
            'title' => 'Purchases',
            'products' => Product::where('name', 'like', '%' . $this->search . '%')->paginate(10),
            'accounts' => ChartOfAccount::whereIn('account_id', [1, 2])->get(),
        ]);
    }
}
