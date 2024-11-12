<?php

namespace App\Livewire\Transaction;

use App\Models\Contact;
use App\Models\Journal;
use App\Models\Product;
use Livewire\Component;
use App\Models\Transaction;
use App\Models\ChartOfAccount;
use App\Models\WarehouseStock;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Sales extends Component
{
    public $search = '';
    public $salesCart = [];
    public $total = 0;
    public $qty = 1;
    public $product_id;
    public $payment = 0;
    public $account;
    public $discount = 0;
    public $serviceFee = 0;
    public $contact_id = 1;

    public function mount()
    {
        // Load the cart from the session when the component is mounted
        $this->salesCart = session()->get('salesCart', []);
        $this->payment = collect($this->salesCart)->sum(fn($item) => $item['price'] * $item['qty']);
    }
    public function addToCart($product_id)
    {
        if (isset($this->salesCart[$product_id])) {
            $this->salesCart[$product_id]['qty'] += $this->qty;
        } else {
            $product = Product::find($product_id);
            $this->salesCart[$product_id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'qty' => $this->qty,
            ];
        }

        $this->total += $this->salesCart[$product_id]['price'] * $this->salesCart[$product_id]['qty'];
        $this->dispatch('cartUpdated');

        $this->updateSession();
    }

    public function updatedDiscount($value)
    {
        // Set discount to 0 if the input is null or empty
        $this->discount = $value === null || $value === '' ? 0 : $value;
    }

    public function removeFromCart($productId)
    {
        unset($this->salesCart[$productId]);
        $this->updateSession();
    }

    public function updateQty($productId, $qty)
    {
        if ($qty > 0) {
            $this->salesCart[$productId]['qty'] = $qty;
        } else {
            $this->removeFromCart($productId);
        }

        $this->updateSession();
    }

    public function updateCost($productId, $cost)
    {
        $this->salesCart[$productId]['price'] = $cost;
        $this->updateSession();
    }

    private function updateSession()
    {
        // Save the cart back to the session
        session()->put('salesCart', $this->salesCart);
    }

    public function clearCart()
    {
        $this->salesCart = []; // Clear the cart array
        session()->forget('salesCart'); // Remove the cart data from the session
    }

    private function addToJournal($invoice, $debt, $cred, $amount)
    {
        $journal = new Journal();
        $journal->date_issued = date('Y-m-d H:i');
        $journal->invoice = $invoice; // Ensure $invoice is defined
        $journal->debt_code = $debt;
        $journal->cred_code = $cred;
        $journal->amount = $amount; // Ensure $jual is defined
        $journal->fee_amount = 0; // Ensure $fee is defined
        $journal->description = "Penjualan Barang";
        $journal->trx_type = 'Sales';
        $journal->user_id = Auth::user()->id;
        $journal->warehouse_id = Auth::user()->role->warehouse_id;
        $journal->save();

        return $journal;
    }

    public function storeCart()
    {
        $validate = $this->validate([
            'account' => 'required',
            'payment' => 'required|numeric',
        ]);

        $invoice = (new Journal())->sales_journal();

        try {
            DB::beginTransaction();

            foreach ($this->salesCart as $item) {
                // dd($item, $this->account, $this->payment);
                $product = Product::find($item['id']);
                if (!$product) {
                    continue; // Skip if the product is not found
                }

                $jual = $item['price'] * $item['qty'];
                $modal = $product->cost * $item['qty'];
                $initial_stock = $product->end_stock;
                $initial_cost = $product->price;
                $initTotal = $initial_stock * $initial_cost;

                $this->addToJournal($invoice, $this->account, "40100-001", $jual);
                $this->addToJournal($invoice, "50100-001", "10600-001", $modal);

                $transaction = new Transaction();
                $transaction->date_issued = date('Y-m-d H:i');
                $transaction->invoice = $invoice; // Ensure $invoice is defined
                $transaction->product_id = $product->id;
                $transaction->quantity = -$item['qty'];
                $transaction->price = $item['price'];
                $transaction->cost = $product->cost;
                $transaction->transaction_type = 'Sales';
                $transaction->contact_id = $this->contact_id;
                $transaction->warehouse_id = Auth::user()->id;
                $transaction->user_id = Auth::user()->role->warehouse_id;;
                $transaction->save();

                $product_log = $transaction->where('product_id', $product->id)->sum('quantity');
                $end_Stock = $product->stock + $product_log;
                Product::where('id', $product->id)->update([
                    'end_Stock' => $end_Stock,
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
            session()->flash('success', 'Penjualan berhasil ditambahkan');
            $this->clearCart();
            $this->reset('account', 'contact_id', 'payment');
            $this->total = 0;
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', $e->getMessage());
        }

        $this->dispatch('SalesCreated', $this->salesCart);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.transaction.sales', [
            'title' => 'Sales',
            'products' => Product::where('name', 'like', '%' . $this->search . '%')->paginate(10),
            'accounts' => ChartOfAccount::whereIn('account_id', [1, 2])->get(),
            'contacts' => Contact::all()
        ]);
    }
}
