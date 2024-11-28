<?php

namespace App\Livewire\Transaction;

use Carbon\Carbon;
use App\Models\Contact;
use App\Models\Journal;
use App\Models\Product;
use Livewire\Component;
use App\Models\Receivable;
use App\Models\Transaction;
use Livewire\Attributes\On;
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
    public $payment_method = 'Cash';
    public int $dueDate = 30; // Default due date in days

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

    #[On('ContactCreated')]
    public function updateContactId($contact_id)
    {
        Contact::all();
    }

    public function updatedDiscount($value)
    {
        // Set discount to 0 if the input is null or empty
        $this->discount = $value === null || $value === '' ? 0 : $value;
    }

    public function updatedserviceFee($value)
    {
        // Set discount to 0 if the input is null or empty
        $this->serviceFee = $value === null || $value === '' ? 0 : $value;
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
        $this->payment = collect($this->salesCart)->sum(fn($item) => $item['price'] * $item['qty']);
    }

    public function clearCart()
    {
        $this->salesCart = []; // Clear the cart array
        session()->forget('salesCart'); // Remove the cart data from the session
    }

    private function addToJournal($invoice, $debt, $cred, $amount, $description = 'Penjualan Barang', $serial = null, $rcv = null)
    {
        $journal = new Journal([
            'date_issued' => date('Y-m-d H:i'),
            'invoice' => $invoice, // Ensure $invoice is defined
            'debt_code' => $debt,
            'cred_code' => $cred,
            'amount' => $amount, // Ensure $jual is defined
            'fee_amount' => 0, // Ensure $fee is defined
            'description' => $description,
            'trx_type' => 'Sales',
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

    public function storeCart()
    {
        if ($this->payment_method == 'Credit') {
            $this->account = '10400-001';

            if ($this->dueDate < 0) {
                return $this->addError('dueDate', 'Jatuh tempo tidak boleh kurang dari 0');
            }

            if ($this->contact_id == 1) {
                return $this->addError('contact_id', 'Contact "General" tidak diperbolehkan untuk metode pembayaran kredit, silahkan pilih contact lain');
            }
        }

        // dd(Carbon::parse(date('Y-m-d H:i'))->addDays($this->dueDate));

        $validate = $this->validate([
            'account' => 'required',
            'payment' => 'required|numeric',
        ]);
        // dd($this->account, $this->payment);

        $invoice = (new Journal())->sales_journal();
        $serial = Transaction::generateSerialNumber('SO', Auth::user()->id);

        try {
            DB::beginTransaction();

            foreach ($this->salesCart as $item) {
                // dd($item, $this->account, $this->payment);
                $product = Product::find($item['id']);
                if (!$product) {
                    continue; // Skip if the product is not found
                }

                $jual = ($item['price'] * $item['qty']) - $this->discount;
                $modal = $product->cost * $item['qty'];
                // $initial_stock = $product->end_stock;
                // $initial_cost = $product->price;
                // $initTotal = $initial_stock * $initial_cost;

                if ($this->payment_method == 'Credit') {
                    $this->addToReceivable($invoice, $this->account, $jual, 'Penjualan Barang (Code:' . $product->code . ') ' . $product->name . ' (' . -$item['qty'] . 'Pcs)', Auth::user(), date('Y-m-d H:i'), $this->dueDate);
                    $rcv = 'Receivable';
                }

                $this->addToJournal($invoice, $this->account, "40100-001", $jual, 'Penjualan Barang (Code:' . $product->code . ') ' . $product->name . ' (' . -$item['qty'] . 'Pcs)', $serial, $rcv ?? null);

                $this->addToJournal($invoice, "50100-001", "10600-001", $modal, 'Pembelian Barang (Code:' . $product->code . ') ' . $product->name . ' (' . -$item['qty'] . 'Pcs)', $serial, $rcv ?? null);


                $transaction = new Transaction([
                    'date_issued' => date('Y-m-d H:i'),
                    'invoice' => $invoice, // Ensure $invoice is defined
                    'product_id' => $product->id,
                    'quantity' => -$item['qty'],
                    'price' => $item['price'],
                    'cost' => $product->cost,
                    'transaction_type' => 'Sales',
                    'contact_id' => $this->contact_id,
                    'warehouse_id' => Auth::user()->id,
                    'user_id' => Auth::user()->role->warehouse_id,
                    'serial_number' => $serial,
                ]);

                $transaction->save();

                $product_log = $transaction->where('product_id', $product->id)->sum('quantity');
                $end_Stock = $product->stock + $product_log;
                Product::where('id', $product->id)->update([
                    'end_Stock' => $end_Stock,
                    'price' => $item['price'],
                ]);

                $updateWarehouseStock = WarehouseStock::where('warehouse_id', Auth::user()->role->warehouse_id)->where('product_id', $product->id)->first();
                $updateCurrentStock = $transaction->where('product_id', $product->id)->where('warehouse_id', Auth::user()->role->warehouse_id)->sum('quantity');
                if ($updateWarehouseStock) {
                    $updateWarehouseStock->current_stock = $updateCurrentStock;
                    $updateWarehouseStock->save();
                } else {
                    $warehouseStock = new WarehouseStock();
                    $warehouseStock->warehouse_id = Auth::user()->role->warehouse_id;
                    $warehouseStock->product_id = $product->id;
                    $warehouseStock->init_stock = 0;
                    $warehouseStock->current_stock = $updateCurrentStock;
                    $warehouseStock->save();
                }
            }

            if ($this->discount > 0) {
                $this->addToJournal($invoice, "60111-001", "40100-001", $this->discount, 'Potongan Penjualan', $serial);
            }

            if ($this->serviceFee > 0) {
                $this->addToJournal($invoice, $this->account, "40100-002", $this->serviceFee, 'Jasa Service', $serial);
                if ($this->payment_method == 'Credit') {
                    $this->addToReceivable($invoice, $this->account, $this->serviceFee, 'Jasa Service', Auth::user(), date('Y-m-d H:i'), $this->dueDate);
                }
            }

            DB::commit();
            session()->flash('success', 'Penjualan berhasil ditambahkan');
            $this->clearCart();
            $this->reset('account', 'contact_id', 'payment');
            $this->total = 0;
            $this->discount = 0;
            $this->serviceFee = 0;
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', $e->getMessage());
        }

        Journal::equityCount(now());

        $this->dispatch('SalesCreated', $this->salesCart);
    }

    public function addToReceivable($invoice, $account, $amount, $description, $user, $dateIssued, $dueDate)
    {
        try {
            DB::beginTransaction();

            Receivable::create([
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

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.transaction.sales', [
            'title' => 'Sales Order',
            'products' => Product::where('name', 'like', '%' . $this->search . '%')->paginate(5),
            'accounts' => ChartOfAccount::whereIn('account_id', [1, 2])->get(),
            'contacts' => Contact::all()
        ]);
    }
}
