<?php

namespace App\Livewire\Journal;

use Livewire\Component;
use Livewire\Attributes\Layout;

class Journal extends Component
{
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.journal.journal', [
            'title' => 'Journal',
        ]);
    }
}
