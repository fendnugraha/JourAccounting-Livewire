<?php

namespace App\Livewire\Finance\Payable;

use App\Models\Payable;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

class PayableTable extends Component
{
    use WithPagination;

    public $search;
    public $searchInvoice;

    #[On(['JournalCreated'])]
    public function updateData()
    {
        $this->resetPage();
    }

    public function getData()
    {
        $payables = Payable::with('contact')->where('invoice', 'like', '%' . $this->searchInvoice . '%')->latest()->paginate(5, ['*'], 'payables');
        $payableContact = Payable::with('contact')
            ->selectRaw('sum(bill_amount-payment_amount) as total, contact_id')
            ->whereHas('contact', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->groupBy('contact_id')
            ->having('total', '>', 0)
            ->paginate(5, ['*'], 'payableContact');
        $total = Payable::selectRaw('SUM(bill_amount - payment_amount) as total')->value('total');

        return [
            'payables' => $payables,
            'payableContact' => $payableContact,
            'total' => $total
        ];
    }

    public function delete($id)
    {
        $payable = Payable::findOrfail($id);
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
