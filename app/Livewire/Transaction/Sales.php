<?php

namespace App\Livewire\Transaction;

use Livewire\Component;
use Livewire\Attributes\Layout;

class Sales extends Component
{
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.transaction.sales', [
            'title' => 'Sales',
        ]);
    }
}
