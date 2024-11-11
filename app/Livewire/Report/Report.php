<?php

namespace App\Livewire\Report;

use Livewire\Component;
use Livewire\Attributes\Layout;

class Report extends Component
{
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.report.report', [
            'title' => 'Report'
        ]);
    }
}
