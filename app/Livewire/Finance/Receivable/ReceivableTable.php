<?php

namespace App\Livewire\Finance\Receivable;

use Livewire\Component;
use Livewire\Attributes\Layout;

class ReceivableTable extends Component
{
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.finance.receivable.receivable-table', [
            'title' => 'Receivable',
        ]);
    }
}
