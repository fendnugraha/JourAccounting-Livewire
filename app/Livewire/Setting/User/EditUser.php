<?php

namespace App\Livewire\Setting\User;

use App\Models\Role;
use App\Models\User;
use Livewire\Component;
use App\Models\Warehouse;
use Livewire\Attributes\Layout;

class EditUser extends Component
{
    public $user_id;
    public $role;
    public $warehouse_id;

    public function mount($user_id)
    {
        $this->user_id = $user_id;
        $user = Role::where('user_id', $this->user_id)->first();
        $this->role = $user->role;
        $this->warehouse_id = $user->warehouse_id;
    }
    public function update()
    {
        Role::where('user_id', $this->user_id)->update([
            'role' => $this->role,
            'warehouse_id' => $this->warehouse_id
        ]);

        session()->flash('success', 'User Updated Successfully');

        return redirect()->route('user.index');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.setting.user.edit-user', [
            'title' => 'Edit User',
            'user' => \App\Models\User::find($this->user_id),
            'warehouses' => Warehouse::all(),
        ]);
    }
}
