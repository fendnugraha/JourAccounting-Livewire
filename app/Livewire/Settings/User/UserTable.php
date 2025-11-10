<?php

namespace App\Livewire\Settings\User;

use App\Models\User;
use Livewire\Component;

class UserTable extends Component
{
    public string $search = '';
    public function render()
    {
        $users = User::with(['roles'])->when($this->search, fn($query) => $query->where('name', 'like', '%' . $this->search . '%'))->orderBy('name', 'asc')->paginate(10)->onEachSide(0);
        return view('livewire.settings.user.user-table', [
            'users' => $users
        ]);
    }
}
