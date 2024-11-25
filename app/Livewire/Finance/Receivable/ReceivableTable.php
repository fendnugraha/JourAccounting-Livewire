<?php

namespace App\Livewire\Finance\Receivable;

use App\Models\Journal;
use Livewire\Component;
use App\Models\Receivable;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;
use Livewire\WithoutUrlPagination;

class ReceivableTable extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $search;
    public $searchInvoice;

    #[On(['JournalCreated', 'ReceivableDeleted'])]
    public function updateData()
    {
        $this->resetPage();
    }

    public function getData()
    {
        $receivables = Receivable::with(['contact', 'journals'])
            ->where('invoice', 'like', '%' . $this->searchInvoice . '%')
            ->orWhereHas('contact', function ($query) {
                $query->where('name', 'like', '%' . $this->searchInvoice . '%');
            })
            ->latest()
            ->paginate(5, ['*'], 'receivables');

        $receivableContact = Receivable::with('contact')
            ->selectRaw('sum(bill_amount-payment_amount) as total, contact_id')
            ->whereHas('contact', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->groupBy('contact_id')
            ->having('total', '>', 0)
            ->simplePaginate(5, ['*'], 'receivableContact');
        $total = Receivable::selectRaw('SUM(bill_amount - payment_amount) as total')->value('total');

        return [
            'receivables' => $receivables,
            'receivableContact' => $receivableContact,
            'total' => $total
        ];
    }

    public function delete($id)
    {
        $receivable = Receivable::findOrFail($id);

        // Check if there are other payments linked to the same invoice
        $paymentCheck = Receivable::where('invoice', $receivable->invoice)
            ->where('payment_nth', '>', 0)
            ->exists();

        // Case 1: receivable cannot be deleted if payment status is 1 and payment nth is 0
        if ($receivable->payment_status == 1 && $receivable->payment_nth == 0) {
            session()->flash('error', 'Receivable Cannot be Deleted!, Already Fully Paid');
            return;
        }

        // Case 2: receivable cannot be deleted if payment status is 0, payment nth is 0, but there are linked payments
        if ($receivable->payment_status == 0 && $receivable->payment_nth == 0 && $paymentCheck == true) {
            session()->flash('error', 'Receivable Cannot be Deleted!, There are Linked Payments');
            return;
        }

        if ($receivable->payment_status == 0 && $receivable->payment_nth == 0 && $paymentCheck == false) {
            try {
                DB::beginTransaction();
                $receivable->delete();
                Journal::where('invoice', $receivable->invoice)->delete();
                DB::commit();
                session()->flash('success', 'Receivable Deleted Successfully');
            } catch (\Exception $e) {
                DB::rollBack();
                session()->flash('error', 'Error Deleting receivable');
            }

            return;
        }

        // Case 3: Delete receivable if payment status is 1 and payment nth > 0, update linked payments
        if ($receivable->payment_status == 1 && $receivable->payment_nth > 0) {
            try {
                DB::beginTransaction();
                $receivable->delete();

                Receivable::where('invoice', $receivable->invoice)->update([
                    'payment_status' => 0,
                ]);

                Journal::where('invoice', $receivable->invoice)->where('payment_nth', $receivable->payment_nth)->delete();
                DB::commit();

                session()->flash('success', 'Receivable Deleted Successfully');
            } catch (\Exception $e) {
                DB::rollBack();
                session()->flash('error', 'Error Deleting receivable');
            }
            return;
        }

        // Case 4: Delete receivable if payment status is 0 and payment nth > 0
        if ($receivable->payment_status == 0 && $receivable->payment_nth > 0) {
            try {
                DB::beginTransaction();
                $receivable->delete();
                Journal::where('invoice', $receivable->invoice)->where('payment_nth', $receivable->payment_nth)->delete();
                DB::commit();

                session()->flash('success', 'Receivable Deleted Successfully');
            } catch (\Exception $e) {
                DB::rollBack();
                session()->flash('error', 'Error Deleting receivable');
            }
            return;
        }

        $this->dispatch('ReceivableDeleted', $receivable->id);
        // Default: No action taken
        session()->flash('error', 'Unable to process request.');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.finance.receivable.receivable-table', [
            'title' => 'Receivable',
            'receivables' => $this->getData()['receivables'],
            'receivableContact' => $this->getData()['receivableContact'],
            'total' => $this->getData()['total']
        ]);
    }
}
