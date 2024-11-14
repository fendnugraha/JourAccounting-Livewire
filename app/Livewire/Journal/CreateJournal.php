<?php

namespace App\Livewire\Journal;

use Livewire\Component;
use App\Models\ChartOfAccount;
use Illuminate\Support\Facades\Auth;

class CreateJournal extends Component
{
    public function render()
    {
        return view('livewire.journal.create-journal', [
            'credits' => ChartOfAccount::where('account_id', 2)->where('warehouse_id', Auth::user()->role->warehouse_id)->get(),
        ]);
    }
}
