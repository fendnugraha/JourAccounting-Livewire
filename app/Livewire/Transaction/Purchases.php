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
    public $purchaseCart = [];
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
        $this->purchaseCart = session()->get('purchaseCart', []);
        $this->payment = collect($this->purchaseCart)->sum(fn($item) => $item['cost'] * $item['qty']);
    }
    public function addToCart($product_id)
    {
        if (isset($this->purchaseCart[$product_id])) {
            $this->purchaseCart[$product_id]['qty'] += $this->qty;
        } else {
            $product = Product::find($product_id);
            $this->purchaseCart[$product_id] = [
                'id' => $product->id,
                'name' => $product->name,
                'cost' => $product->cost,
                'qty' => $this->qty,
            ];
        }

        $this->total += $this->purchaseCart[$product_id]['cost'] * $this->purchaseCart[$product_id]['qty'];
        $this->dispatch('cartUpdated');

        $this->updateSession();
    }

    public function removeFromCart($productId)
    {
        unset($this->purchaseCart[$productId]);
        $this->updateSession();
    }

    public function updateQty($productId, $qty)
    {
        if ($qty > 0) {
            $this->purchaseCart[$productId]['qty'] = $qty;
        } else {
            $this->removeFromCart($productId);
        }

        $this->updateSession();
    }

    public function updateCost($productId, $cost)
    {
        $this->purchaseCart[$productId]['cost'] = $cost;
        $this->updateSession();
    }

    private function updateSession()
    {
        // Save the cart back to the session
        session()->put('purchaseCart', $this->purchaseCart);
    }

    public function clearCart()
    {
        $this->purchaseCart = []; // Clear the cart array
        session()->forget('purchaseCart'); // Remove the cart data from the session
    }

    private function addToJournal($invoice, $debt, $cred, $amount, $description = 'Pembelian Barang', $serial = null)
    {
        $journal = new Journal([
            'date_issued' => date('Y-m-d H:i'),
            'invoice' => $invoice, // Ensure $invoice is defined
            'debt_code' => $debt,
            'cred_code' => $cred,
            'amount' => $amount, // Ensure $jual is defined
            'fee_amount' => 0, // Ensure $fee is defined
            'description' => $description,
            'trx_type' => 'Purchase',
            'user_id' => Auth::user()->id,
            'warehouse_id' => Auth::user()->role->warehouse_id,
            'serial_number' => $serial,
        ]);

        $journal->save();

        return $journal;
    }

    public function storeCart()
    {
        $validate = $this->validate([
            'account' => 'required',
            'payment' => 'required|numeric',
        ]);

        $invoice = (new Journal())->purchase_journal();
        $serial = Transaction::generateSerialNumber('PO', Auth::user()->id);

        try {
            DB::beginTransaction();

            foreach ($this->purchaseCart as $item) {
                // dd($item, $this->account, $this->payment);
                $product = Product::find($item['id']);
                if (!$product) {
                    continue; // Skip if the product is not found
                }

                $modal = $item['cost'] * $item['qty'];
                // $initial_stock = $product->end_stock;
                // $initial_cost = $product->cost;
                // $initTotal = $initial_stock * $initial_cost;

                $this->addToJournal($invoice, "10600-001", $this->account, $modal, 'Pembelian Barang (Code:' . $product->code . ') ' . $product->name . ' (' . $item['qty'] . 'Pcs)', $serial);

                $transaction = new Transaction([
                    'date_issued' => date('Y-m-d H:i'),
                    'invoice' => $invoice, // Ensure $invoice is defined
                    'product_id' => $product->id,
                    'quantity' => $item['qty'],
                    'price' => 0,
                    'cost' => $item['cost'],
                    'transaction_type' => 'Purchase',
                    'contact_id' => $this->contact_id,
                    'warehouse_id' => Auth::user()->id,
                    'user_id' => Auth::user()->role->warehouse_id,
                    'serial_number' => $serial,
                ]);

                $transaction->save();

                Product::udpateCostAndStock($product->id, $item['qty'], $item['qty'], $item['cost'], Auth::user()->role->warehouse_id);

                if ($this->discount > 0) {
                    $this->addToJournal($invoice, "10600-001", "40200-001", $this->discount, 'Diskon Pembelian Barang');
                }
            }

            DB::commit();
            session()->flash('success', 'Pembelian Berhasil');
            $this->clearCart();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', $e->getMessage());
        }

        $this->dispatch('PurchaseCreated', $this->purchaseCart);
    }


    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.transaction.purchases', [
            'title' => 'Purchase Order',
            'products' => Product::where('name', 'like', '%' . $this->search . '%')->paginate(5),
            'accounts' => ChartOfAccount::whereIn('account_id', [1, 2])->get(),
            'contacts' => Contact::all()
        ]);
    }
}
