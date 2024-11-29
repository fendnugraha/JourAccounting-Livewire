<?php

namespace App\Livewire\Transaction;

use Carbon\Carbon;
use App\Models\Contact;
use App\Models\Journal;
use App\Models\Payable;
use App\Models\Product;
use Livewire\Component;
use App\Models\Transaction;
use Livewire\Attributes\On;
use App\Models\ChartOfAccount;
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
    public $payment_method = 'Cash';
    public int $dueDate = 30;


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

    #[On('ContactCreated')]
    public function updateContactId($contact_id)
    {
        Contact::all();
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

    private function addToJournal($invoice, $debt, $cred, $amount, $description = 'Pembelian Barang', $serial = null, $rcv = null)
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
            'rcv_pay' => $rcv,
            'payment_status' => $rcv ? 0 : null,
            'payment_nth' => $rcv ? 0 : null,
            'user_id' => Auth::user()->id,
            'warehouse_id' => Auth::user()->role->warehouse_id,
            'serial_number' => $serial,
        ]);

        $journal->save();

        return $journal;
    }

    public function addToPayable($invoice, $account, $amount, $description, $user, $dateIssued, $dueDate)
    {
        try {
            DB::beginTransaction();

            Payable::create([
                'date_issued' => $dateIssued,
                'due_date' => Carbon::parse($dateIssued)->addDays($dueDate),
                'invoice' => $invoice,
                'description' => $description ?? 'Piutang Usaha',
                'bill_amount' => $amount,
                'payment_amount' => 0,
                'payment_status' => 0,
                'payment_nth' => 0,
                'contact_id' => $this->contact_id,
                'user_id' => $user->id,
                'account_code' => $account
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }

    public function storeCart()
    {
        if ($this->payment_method == 'Credit') {
            $this->account = '20100-001';

            if ($this->dueDate < 0) {
                return $this->addError('dueDate', 'Jatuh tempo tidak boleh kurang dari 0');
            }

            if ($this->contact_id == 1) {
                return $this->addError('contact_id', 'Contact "General" tidak diperbolehkan untuk metode pembayaran kredit, silahkan pilih contact lain');
            }
        }

        $validate = $this->validate([
            'account' => 'required',
            'payment' => 'required|numeric',
        ]);

        $invoice = (new Journal())->purchase_journal();
        $serial = Transaction::generateSerialNumber('PO', Auth::user()->id);

        $totalModal = 0;

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
                $totalModal += $modal;

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

                Product::updateCost($item['id']);
                Product::updateStock($item['id'], $item['qty'], Auth::user()->role->warehouse_id);
            }

            if ($this->payment_method == 'Credit') {
                $this->addToPayable($invoice, $this->account, $totalModal - $this->discount, 'Pembelian Barang ' . $invoice, Auth::user(), date('Y-m-d H:i'), $this->dueDate);
                $rcv = 'Payable';
            }

            $this->addToJournal($invoice, "10600-001", $this->account, $totalModal - $this->discount, 'Pembelian Barang ' . $invoice, $serial, $rcv ?? null);

            if ($this->discount > 0) {
                $this->addToJournal($invoice, "10600-001", "40200-001", $this->discount, 'Diskon Pembelian Barang', $serial, $rcv ?? null);
                if ($this->payment_method == 'Credit') {
                    $this->addToPayable($invoice, $this->account, -$this->discount, 'Diskon Pembelian Barang', Auth::user(), date('Y-m-d H:i'), $this->dueDate);
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
