<?php

namespace App\Livewire\Setting;

use Livewire\Component;
use Livewire\Attributes\Layout;

class Setting extends Component
{
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.setting.setting', [
            'title' => 'Setting',
        ]);
    }
}
