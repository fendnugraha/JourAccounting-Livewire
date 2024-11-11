<?php

namespace App\Livewire\Transaction;

use App\Models\Journal;
use App\Models\Product;
use Livewire\Component;
use App\Models\Transaction;
use App\Models\ChartOfAccount;
use App\Models\Contact;
use App\Models\WarehouseStock;
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
    public $discount = 0;
    public $contact_id = 1;

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
        $validate = $this->validate([
            'account' => 'required',
            'payment' => 'required|numeric',
        ]);

        $invoice = (new Journal())->purchase_journal();

        try {
            DB::beginTransaction();

            foreach ($this->cart as $item) {
                // dd($item, $this->account, $this->payment);
                $product = Product::find($item['id']);
                if (!$product) {
                    continue; // Skip if the product is not found
                }

                $modal = $item['cost'] * $item['qty'];
                $initial_stock = $product->end_stock;
                $initial_cost = $product->cost;
                $initTotal = $initial_stock * $initial_cost;

                $journal = new Journal();
                $journal->date_issued = date('Y-m-d H:i');
                $journal->invoice = $invoice; // Ensure $invoice is defined
                $journal->debt_code = "10600-001";
                $journal->cred_code = $this->account;
                $journal->amount = $modal; // Ensure $modal is defined
                $journal->fee_amount = 0; // Ensure $fee is defined
                $journal->description = "Penjualan Barang";
                $journal->trx_type = 'Accessories';
                $journal->user_id = Auth::user()->id;
                $journal->warehouse_id = Auth::user()->role->warehouse_id;
                $journal->save();

                $transaction = new Transaction();
                $transaction->date_issued = date('Y-m-d H:i');
                $transaction->invoice = $invoice; // Ensure $invoice is defined
                $transaction->product_id = $product->id;
                $transaction->quantity = $item['qty'];
                $transaction->price = 0;
                $transaction->cost = $item['cost'];
                $transaction->transaction_type = 'Purchases';
                $transaction->contact_id = $this->contact_id;
                $transaction->warehouse_id = Auth::user()->id;
                $transaction->user_id = Auth::user()->role->warehouse_id;;
                $transaction->save();

                $newStock = $item['qty'];
                $newCost = $item['cost'];
                $newTotal = $newStock * $newCost;

                $updatedCost = ($initTotal + $newTotal) / ($initial_stock + $newStock);

                $product_log = $transaction->where('product_id', $product->id)->sum('quantity');
                $end_Stock = $product->stock + $product_log;
                Product::where('id', $product->id)->update([
                    'end_Stock' => $end_Stock,
                    'cost' => $updatedCost,
                ]);

                $updateWarehouseStock = WarehouseStock::where('warehouse_id', Auth::user()->role->warehouse_id)->where('product_id', $product->id)->first();
                if ($updateWarehouseStock) {
                    $updateWarehouseStock->current_stock += $item['qty'];
                    $updateWarehouseStock->save();
                } else {
                    $warehouseStock = new WarehouseStock();
                    $warehouseStock->warehouse_id = Auth::user()->role->warehouse_id;
                    $warehouseStock->product_id = $product->id;
                    $warehouseStock->init_stock = $item['qty'];
                    $warehouseStock->current_stock = $item['qty'];
                    $warehouseStock->save();
                }
            }

            DB::commit();
            session()->flash('success', 'Pembelian Berhasil');
            $this->clearCart();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', $e->getMessage());
        }

        $this->dispatch('PurchaseCreated', $this->cart);
    }


    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.transaction.purchases', [
            'title' => 'Purchases',
            'products' => Product::where('name', 'like', '%' . $this->search . '%')->paginate(10),
            'accounts' => ChartOfAccount::whereIn('account_id', [1, 2])->get(),
            'contacts' => Contact::all()
        ]);
    }
}
