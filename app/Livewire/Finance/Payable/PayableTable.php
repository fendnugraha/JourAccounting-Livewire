<?php

namespace App\Livewire\Finance\Payable;

use App\Models\Journal;
use App\Models\Payable;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;
use Livewire\WithoutUrlPagination;

class PayableTable extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $search;
    public $searchInvoice;

    #[On(['JournalCreated', 'PayableDeleted'])]
    public function updateData()
    {
        $this->resetPage();
    }

    public function getData()
    {
        $payables = Payable::with('contact')
            ->where('invoice', 'like', '%' . $this->searchInvoice . '%')
            ->orWhereHas('contact', function ($query) {
                $query->where('name', 'like', '%' . $this->searchInvoice . '%');
            })
            ->latest()
            ->paginate(5, ['*'], 'payables');

        $payableContact = Payable::with('contact')
            ->selectRaw('sum(bill_amount-payment_amount) as total, contact_id')
            ->whereHas('contact', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->groupBy('contact_id')
            ->having('total', '>', 0)
            ->simplePaginate(5, ['*'], 'payableContact');
        $total = Payable::selectRaw('SUM(bill_amount - payment_amount) as total')->value('total');

        return [
            'payables' => $payables,
            'payableContact' => $payableContact,
            'total' => $total
        ];
    }

    public function delete($id)
    {
        $payable = Payable::findOrFail($id);

        // Check if there are other payments linked to the same invoice
        $paymentCheck = Payable::where('invoice', $payable->invoice)
            ->where('payment_nth', '>', 0)
            ->exists();

        // Case 1: Payable cannot be deleted if payment status is 1 and payment nth is 0
        if ($payable->payment_status == 1 && $payable->payment_nth == 0) {
            session()->flash('error', 'Payable Cannot be Deleted!, Already Fully Paid');
            return;
        }

        // Case 2: Payable cannot be deleted if payment status is 0, payment nth is 0, but there are linked payments
        if ($payable->payment_status == 0 && $payable->payment_nth == 0 && $paymentCheck == true) {
            session()->flash('error', 'Payable Cannot be Deleted!, There are Linked Payments');
            return;
        }

        if ($payable->payment_status == 0 && $payable->payment_nth == 0 && $paymentCheck == false) {
            try {
                DB::beginTransaction();
                $payable->delete();
                Journal::where('invoice', $payable->invoice)->delete();
                DB::commit();
                session()->flash('success', 'Payable Deleted Successfully');
            } catch (\Exception $e) {
                DB::rollBack();
                session()->flash('error', 'Error Deleting Payable');
            }

            return;
        }

        // Case 3: Delete payable if payment status is 1 and payment nth > 0, update linked payments
        if ($payable->payment_status == 1 && $payable->payment_nth > 0) {
            try {
                DB::beginTransaction();
                $payable->delete();

                Payable::where('invoice', $payable->invoice)->update([
                    'payment_status' => 0,
                ]);

                Journal::where('invoice', $payable->invoice)->where('payment_nth', $payable->payment_nth)->delete();
                DB::commit();

                session()->flash('success', 'Payable Deleted Successfully');
            } catch (\Exception $e) {
                DB::rollBack();
                session()->flash('error', 'Error Deleting Payable');
            }
            return;
        }

        // Case 4: Delete payable if payment status is 0 and payment nth > 0
        if ($payable->payment_status == 0 && $payable->payment_nth > 0) {
            try {
                DB::beginTransaction();
                $payable->delete();
                Journal::where('invoice', $payable->invoice)->where('payment_nth', $payable->payment_nth)->delete();
                DB::commit();

                session()->flash('success', 'Payable Deleted Successfully');
            } catch (\Exception $e) {
                DB::rollBack();
                session()->flash('error', 'Error Deleting Payable');
            }
            return;
        }

        $this->dispatch('PayableDeleted', $payable->id);
        // Default: No action taken
        session()->flash('error', 'Unable to process request.');
    }



    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.finance.payable.payable-table', [
            'title' => 'Payable',
            'payables' => $this->getData()['payables'],
            'payableContact' => $this->getData()['payableContact'],
            'total' => $this->getData()['total']
        ]);
    }
}
