<?php

namespace App\Livewire\Finance\Payable;

use App\Models\Payable;
use Livewire\Component;
use Livewire\Attributes\Layout;

class PayableTable extends Component
{
    #[Layout('layouts.app')]
    public function render()
    {
        $payables = Payable::latest()->paginate(5, ['*'], 'payables');
        return view('livewire.finance.payable.payable-table', [
            'title' => 'Payable',
            'payables' => $payables
        ]);
    }
}
