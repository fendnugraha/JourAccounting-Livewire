<?php

namespace App\Livewire\Setting\User;

use App\Models\User;
use App\Models\Journal;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Layout;

class UserTable extends Component
{
    public function delete($id)
    {
        $user = User::find($id);

        $journalExists = Journal::where('user_id', $id)->exists();
        if ($journalExists) {
            session()->flash('error', 'User Cannot be Deleted!');
        } else {
            $user->delete();
            session()->flash('success', 'User Deleted Successfully');
        }

        $this->dispatch('UserDeleted', $user->id);
    }

    #[Layout('layouts.app')]
    #[On('UserCreated')]
    public function render()
    {
        return view('livewire.setting.user.user-table', [
            'title' => 'User',
            'users' => User::with('role')->paginate(10),
        ]);
    }
}
