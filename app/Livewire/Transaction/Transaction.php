<?php

namespace App\Livewire\Transaction;

use Livewire\Attributes\Layout;
use Livewire\Component;

class Transaction extends Component
{
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.transaction.transaction', [
            'title' => 'Transaction',
        ]);
    }
}
