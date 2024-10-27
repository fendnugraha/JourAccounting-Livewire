<?php

namespace App\Livewire\User;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;

class UserTable extends Component
{
    #[On('UserCreated')]
    public function render()
    {
        $users = User::with('role')->get();
        // dd($users);
        return view('livewire.user.user-table', [
            'title' => 'User Table',
            'users' => User::with('role')->get(),
        ], compact('users'))->layout('layouts.app');
    }
}
